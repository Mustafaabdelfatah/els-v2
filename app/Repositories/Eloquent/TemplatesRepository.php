<?php

namespace App\Repositories\Eloquent;

use App\Models\Form;
use App\Models\Template;
use App\Models\User;
use App\Models\UserForm;
use App\Models\UserTemplate;
use App\Models\TemplateOrganization;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class TemplatesRepository extends BaseRepository
{
    public function model()
    {
        return Template::class;
    }

    public function getUserTemplates($user)
    {
        $templates = [];
        $templateOrganizations = TemplateOrganization::pluck('template_id');
       
        if(in_array('Root', $user->getRoleNames()->toArray()))
        {
            $templates = Template::all();
            return $templates;
        }
        else{
            $templates = Template::whereHas('organizations', function($q) use ($user){
                $q->whereIn('organization_id',userOrganization());
                $q->Orwhere('organization_id',$user->organization_id);
            })->orWhereNotIn('id',$templateOrganizations)->get();
        }

        return $templates;
    }

    public function createTemplate($name,$ar_name, $user_id, $template_id=null, $icon = 'cs-star-full',$organizations)
    {
        // create template
        $organizationIds = [];
        foreach ($organizations as $organization)
        {
            $organizationIds[] = $organization['id'];
        }
        $template = Template::create([
            'name' => $name,
            'ar_name' => $ar_name,
            'type' => $request->type ?? 'form',
            'user_id' => $user_id,
            'icon' => $icon,
        ]);
        $template->organizations()->sync($organizationIds);

        // Add track Permission
        Permission::create([
            'name' => 'tracking-' . $name,
            'display_name' => 'tracking-' . $name,
            'guard_name' => "api",
            'group' => str_replace(' ', '-', $name),
        ]);
        // Add assign Permission
        Permission::create([
            'name' => 'assign-' . $name,
            'display_name' => 'assign-' . $name,
            'guard_name' => "api",
            'group' => str_replace(' ', '-', $name),
        ]);
        if ($template_id) {
            $temp = $this->find($template_id);

            foreach ($temp->pages as $temp_page) {
                $items = $temp_page->items()->get();
                unset($temp_page->id, $temp_page->template_id, $temp_page->creates_at, $temp_page->updated_at);
                $page = $template->pages()->create($temp_page->toArray());

                foreach ($items as $temp_item) {
                    unset($temp_item->id, $temp_item->template_page_id, $temp_item->creates_at, $temp_item->updated_at);
                    $page->items()->create($temp_item->toArray());
                }
            }
        } else {
            // add default page
            $template->pages()->create([
                'title' => 'Title here ..',
                'template_id' => $template->id
            ]);
        }

        // return template
        return $template;
    }

    public function updateTemplate($id,$name,$ar_name, $icon = 'cs-star-full',$organizations)
    {
        try {
            DB::beginTransaction();
            $organizationIds = [];
            foreach ($organizations as $organization)
            {
                $organizationIds[] = $organization['id'];
            }
            $template = Template::where('id',$id)->first();

            $template->update([
                'name' => $name,
                'ar_name' => $ar_name,
                'icon' => $icon,
            ]);
//            \Log::info( collect($organizationIds)->toJson());
            $template->organizations()->sync($organizationIds);
            DB::commit();

            return $template;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function update($request, $template)
    {
        // create template
        $template->update([
            //'name' => $request->name,
            'user_id' => auth()->user()->id,
            'updated_at' => Carbon::now()
        ]);
        // update pages
        DB::table((new \App\Models\TemplatePageItem())->getTable())->whereIn('template_page_id', $template->pages->pluck('id'))->delete();
        DB::table((new \App\Models\TemplatePage)->getTable())->whereIn('id', $template->pages->pluck('id'))->delete();
        foreach ($request->pages as $page) {
            $page = (object)$page;
            $template_page = $template->pages()->create([
                'title' => $page->title['title'],
            ]);
            // create items
            foreach ($page->items as $item) {
                $item = (object)$item;
                if ($item->removed == 'true' || $item->removed == true) continue;

                $create['items'][] = $template_page->items()->create([
                    'type' => $item->type,
                    'label' => $item->label,
                    'notes' => $item->notes,
                    'excel_name' => $item->excel_name ?? '',
                    'width' => $item->width,
                    'height' => $item->height,
                    'length' => $item->length ?? null,
                    'input_type' => $item->input_type ?? 'text',
                    'enabled' => filter_var($item->enabled, FILTER_VALIDATE_BOOLEAN),
                    'required' => filter_var($item->required, FILTER_VALIDATE_BOOLEAN),
                    'website_view' => filter_var($item->website_view, FILTER_VALIDATE_BOOLEAN),
                    'childList' => json_encode($item->childList),
                ]);
            }
        }
        // return template
        return $create;
    }

    public function assignUsers($request, $template)
    {
        // create form
        $form = Form::create([
            'name' => $request->form_title,
            'department' => $request->form_department,
            'job_level' => $request->form_job_level,
            'expires_at' => $request->expires_at,
            'user_id' => auth()->id(),
            'template_id' => $template->id
        ]);
        // create pages
        foreach ($template->pages as $page) {
            $form_page = $form->pages()->create([
                'title' => $page->title,
            ]);
            // create items
            foreach ($page->items as $item) {
                $form_page->items()->create([
                    'type' => $item->type,
                    'label' => $item->label,
                    'notes' => $item->notes,
                    'excel_name' => $item->excel_name,
                    'width' => $item->width,
                    'height' => $item->height,
                    'enabled' => $item->enabled,
                    'required' => $item->required || $item->required === 'true',
                    'website_view' => $item->website_view || $item->website_view === 'true',
                    'childList' => $item->childList,
                ]);
            }
        }
        // assign users to this form
        if (!count($request->users)) {
            $userForm = UserForm::create([
                'form_id' => $form->id,
                'user_id' => auth()->id(),
                'assigned_by' => auth()->id(),
                'type' => 'application',
                'job_request_id' => $request->job_request_id
            ]);
//            $userForm->refresh();
//            try {
//                if ($userForm->user->mail_notify == true) {
//                    $url = 'https://jas.ncms.sa/user/forms?id=' . $userForm->id;
//                    dispatch(new SendNotifyJob($userForm->user->email, $url, $userForm->assigner->department->name, $userForm->form->name, $userForm->user->name, $userForm->action, $userForm->status, 'assign'));
//                }
//            } catch (\Exception $e) {
//
//            }
        } else
            foreach ($request->users as $user) {
                $userForm = UserForm::create([
                    'form_id' => $form->id,
                    'user_id' => $user['id'],
                    'assigned_by' => auth()->user()->id,
                ]);
                $userForm->refresh();
                try {
                    if ($userForm->user->mail_notify == true) {
                        $url = 'https://jas.ncms.sa/user/forms?id=' . $userForm->id;
                        dispatch(new SendNotifyJob($userForm->user->email, $url, $userForm->assigner->department->name, $userForm->form->name, $userForm->user->name, $userForm->action, $userForm->status, 'assign'));
                    }
                } catch (\Exception $e) {

                }
            }

        return $form;
    }

    public function assignAdmin($templateId,$selectedAdmins)
    {
        $user = \Auth::user();
//        if (!in_array('Root', $user->getRoleNames()->toArray()))
//        {
//            $userOrganizations = $user->organization_admin()->pluck('organization_id');
//            $usersInThisOrganizations = User::whereIn('organization_id',$userOrganizations)->pluck('id');
//            DB::table('user_templates')->whereIn('organization_id',$userOrganizations)
//                ->whereIn('user_id',$usersInThisOrganizations)
//                ->where('template_id',$templateId)->delete();
//        }else{
            DB::table('user_templates')->where('template_id',$templateId)->delete();
//        }

//        foreach ($selectedOrganizations as $selectedOrganization)
//        {
            if($selectedAdmins){
                foreach ($selectedAdmins as $selectedAdmin)
                {
                    foreach ($selectedAdmin['admins'] as $admin)
                    {
                        foreach ($selectedAdmin['organizations'] as $organization)
                        {
                            UserTemplate::firstOrCreate([
                                'template_id'=>$templateId,
                                'user_id'=>$admin['id'],
                                'organization_id'=>$organization['id']
                            ]);
                        }
                    }
                }
            }
//        }
        return $selectedAdmins;

    }

    public function getUsersOfOrganizations($organizationId): array
    {
        $employees = [];
        if($organizationId)
        {
            $users = User::with('roles')->where('organization_id',$organizationId)->select('id','name')->get();
            if($users)
            {
                foreach ($users as $user)
                {
//                    $adminOrganizations = $user->organization_admin()->pluck('organization_id')->toArray();
//                    if($adminOrganizations)
//                    {
//                        foreach ($selectedOrganizationIds as $selectedOrganizationId)
//                        {
//                            if(!in_array($selectedOrganizationId, $adminOrganizations))
//                            {
//                                if($user->roles->contains('name', 'Employee')) {
//                                    $employees [] = $user;
//                                }
//                            }
//                        }
//                    }
//                    else{
                        if($user->roles->contains('name', 'Employee')) {
                            $employees [] = $user;
                        }
//                    }
                }
            }
        }
        return $employees;
    }

    public function delete($template)
    {
        return $template->delete();
    }
}
