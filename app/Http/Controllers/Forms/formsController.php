<?php

namespace App\Http\Controllers\Forms;

use Exception;
use Carbon\Carbon;
use App\Models\Form;
use App\Models\User;
use http\Env\Response;
use App\Models\Template;
use App\Models\RequestFile;
use App\Models\Organization;
use Illuminate\Http\Request;
use App\Models\ReturnRequest;
use App\Models\ApproveRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Http\Requests\AssignRequest as FormAssignRequest;
use App\Models\AssignRequest;
use App\Http\Requests\RejectRequest;
use App\Http\Resources\FormResource;
use App\Models\TemplateOrganization;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\formFillsRequest;
use App\Notifications\MailNotification;
use Symfony\Component\Console\Helper\Helper;
use App\Repositories\Eloquent\FormsRepository;
use App\Repositories\Eloquent\UsersRepository;
use App\Http\Resources\ReturnRequestCollection;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class formsController extends Controller
{

    private $repo;
    protected $permissions;

    public function __construct(FormsRepository $repo)
    {
        $this->repo = $repo;

    }

    public function getUserPer()
    {
        $data = [];
        foreach (user::select('id', 'name', 'email')->get() as $user) {
            $Permissions = $user->getAllPermissions();
            $userPermission = \Arr::pluck($Permissions, 'name');
            if (in_array('review', $userPermission))
                $user->notify(new MailNotification('requests', ['user' => Auth::user()], 'testing user has per'));
        }
        return response()->json($data);
    }


    public function index(Request $request)
    {

        $user = Auth::user();
//        return response()->json($user->adminTemplates()->get());
//        return $user->getRoleNames()->toArray();
//        return \response()->json($request->input('length'));
        $form_names = array();
        $form_organizations = array();
        $orders = [];
        $wheres = [];
        if ($request->has('order') && count($request->input('order')))
            foreach ($request->input('order') as $order) {
                $column = isset($request->input("columns")[$order['column']]['data']) ? $request->input("columns")[$order['column']]['data'] : null;
                if ($column)
                    $orders[$column] = $order['dir'];
                else {

                }
            }

        $length = ($request->input('length')) ? $request->input('length') : 10; // meta length
        $start = ($request->has('start')) ? $request->start : 0; // meta start
        $columns = ($request->has('columns')) ? $request->columns : []; // meta start
        if ($request->input('search')) {
            $deleteFilter = ["All", "Not trashed", "Trashed"];
            $search = (!in_array($request->input('search')['value'], $deleteFilter)) ?
                $request->input('search') : [];
            $filter = (in_array($request->input('search')['value'], $deleteFilter)) ?
                $request->input('search')['value'] : null;
        } else {
            $search = [];
            $filter = '';
        }
        if ($request->input('review') == 'me') {
            $wheres['user_id'] = auth::user()->id; //got just request form user auth created
        }

//        return $user->adminTemplates()->get();
        if (in_array('Employee', $user->getRoleNames()->toArray()) || in_array('Admin', $user->getRoleNames()->toArray())) {
            foreach ($user->adminTemplates()->get() as $template) {
                $form_names[] = $template->name;
                $organizationIds[] = $template->pivot->organization_id;
                $form_organizations = array_unique($organizationIds);
            }
        }
//        return $form_organizations;
        $query = $this->repo->ajaxPaginate($length, $start, $wheres, $filter, $search, $columns, $orders, [], true);


        if (!$request->input('review') && !in_array('Root', $user->getRoleNames()->toArray())) {
//            if (count($form_organizations) > 0) {
//                $query->WhereIn('organization_id', $form_organizations);
//            }
//            return Template::whereDoesntHave('admins')->pluck('id')->toArray();
//            return $user->organization_admin()->pluck('organization_id');
//            return $user->adminTemplates()->pluck('template_id');
            $query->where(function ($q) use ($user) {
                if ($user->organization_admin()->count())
                    $q->whereIn('organization_id', $user->organization_admin()->pluck('organization_id'));

                $q->orWhere(function ($q2) use ($user) {
                    $q2->whereIn('template_id', $user->adminTemplates()->pluck('template_id'))
                        ->whereIn('organization_id', $user->adminTemplates()->pluck('organization_id'));
                });
            });
//            if ($user->organization_admin()->count()) {
//                $query->whereIn('organization_id', $user->organization_admin()->pluck('organization_id'));
//            } else {
//                $query->where(function ($q) use ($user) {
//                    $q->whereIn('template_id', $user->adminTemplates()->pluck('template_id'))
//                        ->whereIn('organization_id', $user->adminTemplates()->pluck('organization_id'));
//                });
//            }
        }

        if ($request->input('department_id', 0))
            $query->whereHas('user.department', function ($query) use ($request) {
                $query->where('id', '=', $request->department_id);
            });
        if ($request->input('organization_id', 0))
            $query->whereHas('user.organization', function ($query) use ($request) {
                $query->where('id', '=', $request->organization_id);
            });

        if ($request->input('user_id', 0))
            $query->whereHas('assignedRequests.user', function ($query) use ($request) {
                $query->where('id', '=', $request->user_id);
            });

        if ($request->input('template_id', 0))
            $query->where('template_id', '=', $request->template_id);


        if ($request->input('status', 0))
            $query->where('status', '=', $request->status);


        $start_date = $request->input('start_date');
        $to_date = $request->input('to_date');
        if ($start_date && $to_date) {
            $query->whereDate('created_at', '>=', Carbon::parse($start_date)->startOfDay())
                  ->whereDate('created_at', '<=', Carbon::parse($to_date)->endOfDay());
        }

        if ($request->input('priority', 0)){
            $priority = $request->input('priority');

            $returnRequestIds = ReturnRequest::where('priority', $priority)->pluck('form_id')->toArray();
            $approveRequestIds = ApproveRequest::where('priority', $priority)->pluck('form_id')->toArray();

            $query->whereIn('id', array_merge($returnRequestIds, $approveRequestIds));
        }



        $query->doesntHave('children');

        $data = $query->select('forms.*')->paginate($length);

        return response()->json([
            'data' => FormResource::make($data),
            'draw' => $request->input('draw'),
            'recordsFiltered' => (int)$data->total(),
            'recordsTotal' => (int)$data->total(),
        ]);
    }

    public function json(Request $request)
    {
        if ($request->department_id) {
            $forms = Form::whereHas('user.department')->when($request->department_id, function ($query) use ($request) {
                $query->where('id', '=', $request->department_id);
            })->get();

            return response()->json(FormResource::make($forms));
        } else {
            $forms = Form::get();
            return response()->json(FormResource::make($forms));
        }
    }

    public function assignRequest(FormAssignRequest $request)
    {
        try {
            $form_id = $request->form_id;
            // check if  form_id has record or not
            foreach ($form_id as $form_id) {
                $checkIfAssigned = AssignRequest::where('form_id', $form_id)->where('status','active')->first();

                if ($checkIfAssigned) {
                    if ($checkIfAssigned->status !== "deleted") {
                        $checkIfAssigned->update([
                            'status' => "deleted",
                        ]);
                    }
                }
                $form_user_id = Form::where('id', $form_id)->pluck('user_id');
                $assignNew = $this->repo->assignNewRequest([
                    'form_id' => $form_id,
                    'user_id' => $request->user_id,
                    'date' => $request->date,
                    'assigner_id' => Auth::user()->id,
                    'status' => 'active',
                    'form_user_id' => $form_user_id,
                ]);

                Form::where('id', $form_id)->update(['status' => 'processing']);
            }
            return $assignNew;
        } catch (Exception $e) {
            return $e;
            return response()->json(['message' => 'Unknown error', $e], 500);
        }
        // try {
        //     $form_id = $request->form_id[0];
        //     $user_ids = $request->input('user_id');
        //     $form = Form::find($form_id);
        //     $existingUserIds = AssignRequest::where('form_id', $form_id)->pluck('user_id')->toArray();
        //     foreach ($user_ids as $user_id) {
        //         if (!in_array($user_id, $existingUserIds)) {
        //             $assignRequest = AssignRequest::create([
        //                 'user_id' => $user_id,
        //                 'form_id' => (int) $form_id,
        //                 'assigner_id' => Auth::user()->id,
        //                 'date' => $request->date,
        //                 'status' => 'active',
        //             ]);
        //         }
        //     }
        //     // Sync the user assignments for the form
        //     AssignRequest::where('form_id', $form_id)
        //     ->whereNotIn('user_id', $user_ids)
        //     ->delete();

        //     Form::where('id', $form_id)->update(['status' => 'processing']);
        // } catch (Exception $e) {
        //     return $e;
        //     return response()->json(['message' => 'Unknown error', $e], 500);
        // }
    }

    public function rejectRequest(RejectRequest $request)
    {
        try {
            $form_ids = $request->form_id;
            foreach ($form_ids as $form_id) {
                $rejectRequest = $this->repo->rejectRequest([
                    'form_id' => $form_id,
                    'status' => 'reject',
                    'notes' => $request->notes,
                    'assigner_id' => Auth::user()->id,
                ]);
                Form::where('id', $form_id)->update(['status' => 'rejected']);
                $closed = RequestFile::create([
                    'comment' => 'rejected',
                    'form_id' => $form_id,
                    'user_id' => Auth::user()->id,
                ]);
            }
            return $rejectRequest;
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error', $e], 500);
        }
    }

    public function getAssignedRequest($id)
    {
        $data = $this->repo->getAssignedById($id);
        try {
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    public function editAssignedRequest(AssignRequest $request)
    {

        try {
            $existingRequests = \App\Models\AssignRequest::where('form_id', $request->form_id)
                ->where('status', 'active')
                ->get();

            // Deactivate existing requests
            foreach ($existingRequests as $existingRequest) {
                $existingRequest->status = 'deleted';
                $existingRequest->save();
            }

            // Create new active requests for each user
            $users = $request->input('users'); // Assuming the request contains an array of user IDs

            foreach ($users as $userId) {
                \App\Models\AssignRequest::create([
                    'form_id' => $request->form_id,
                    'user_id' => $userId,
                    'date' => $request->date,
                    'assigner_id' => Auth::user()->id,
                    'status' => 'active',
                ]);
            }

            // Update the form status
            \App\Models\Form::where('id', $request->form_id)
                ->update(['status' => 'processing']);

            return response()->json(['message' => 'Assignments updated successfully']);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Unknown error', $e], 500);
        }

        // try {
        //     if (\App\Models\AssignRequest::findOrFail($request->id))
        //         \App\Models\AssignRequest::where('id', $request->id)->update(['status' => 'deleted']);
        //     return $this->repo->assignNewRequest([
        //         'form_id' => $request->form_id,
        //         'user_id' => $request->user_id,
        //         'date' => $request->date,
        //         'assigner_id' => Auth::user()->id,
        //         'status' => 'active',
        //     ]);
        //     Form::where('id', $request->form_id)->update(['status' => 'processing']);
        // } catch (Exception $e) {
        //     DB::rollBack();
        //     return response()->json(['message' => 'Unknown error', $e], 500);
        // }
    }

    public function getFormById(Request $request, $id)
    {
        $data = $this->repo->getById($id);
        try {
            return response()->json($data);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    public function fillsRequest(formFillsRequest $request)
    {
//        return json_decode($request->pages);
        try {
            return $this->repo->create_form_fills(auth()->user()->id, $request->id, json_decode($request->pages), $request->fileName);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function update_fills(Request $request)
    {
        $data = [];
        $pages = json_decode($request->pages);
        $form_id = $request->form_id;
        $parentFormId = $this->getParentFormId($form_id);
        foreach ($pages as $key => $page) {
            foreach ($page->items as $item) {
                $data[] = $item->filling;
            }
        }
        return $this->repo->updateFills(auth()->user()->id, $form_id, $pages,$parentFormId, $request->fileName);
    }

    public function destroy(Request $request)
    {
        try {
            return $this->repo->bulkDelete($request->ids ?? [], false);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    public function restore(Request $request)
    {
        try {
            return $this->repo->bulkRestore($request->ids ?? [], false);
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    public function deleteAssignedUser(Request $request)
    {
//        return Response()->json($request->all());
        $allRows = \App\Models\AssignRequest::find($request->all());
        foreach ($allRows as $row) {
            $row->delete();
        }
        try {
            return true;
        } catch (Exception $e) {
            return response()->json(['message' => 'Unknown error'], 500);
        }
    }

    public function action(Request $request)
    {
        $parentFormId = $this->getParentFormId($request->form_id);
        return $this->repo->changeStatus($request->form_id, $request->status, $request->comment,$parentFormId);
    }


    private function getParentFormId($prop='id')
    {
        $form = Form::find($prop);
        $value = $prop;
        do {
            if ($form->parent_form) {
                $value = $form->parent ?? null;
                $form = $form->parent_form;
            }
        }
        while ($form->parent_form);
        return $value;
    }

    public function approve(Request $request)
    {
        $parentFormId = $this->getParentFormId($request->form_id);
        return $this->repo->approve($request->all(),$parentFormId);
    }

    public function approveSecret(Request $request)
    {
        $form_id = ReturnRequest::where('id',$request->id)->pluck('form_id')[0];
        $parentFormId = $this->getParentFormId($form_id);
        return $this->repo->approveSecretData($request->id,$parentFormId);
    }

    public function return_request(Request $request)
    {
        $parentFormId = $this->getParentFormId($request->form_id);
        return $this->repo->returnRequest($request,$parentFormId);
    }

    public function formsReview()
    {
//        $permissions = auth()->user();
//        return $permissions;
//        return $this->repo->reviewFormsWithTracked();
    }

    public function history(Request $request)
    {
        $data = $this->repo->getById($request->form_id);
        $status = $this->repo->lastStatus($request->form_id);
        return response()->json(['data' => $data, 'status' => $status], 200);
    }

    public function RequestValue(Request $request)
    {
        return $this->repo->UpdateValue($request->form_id, $request->value);
    }

    public function getAssigned(Request $request)
    {
        $orders = [];
        $query = [];
        if ($request->has('order') && count($request->input('order')))
            foreach ($request->input('order') as $order) {
                $column = isset($request->input("columns")[$order['column']]['data']) ? $request->input("columns")[$order['column']]['data'] : null;
                if ($column)
                    $orders[$column] = $order['dir'];
                else {

                }
            }
        $length = ($request->has('lenght')) ? $request->lenght : 10; // meta length
        $start = ($request->has('start')) ? $request->start : 0; // meta start
        $columns = ($request->has('columns')) ? $request->columns : []; // meta start
        if ($request->input('search')) {
            $deleteFilter = ["All", "Not trashed", "Trashed"];
            $assignFilter = ["pending", "Processing", "Rejected", "All"];
            $search = (!in_array($request->input('search')['value'], $assignFilter) or !in_array($request->input('search')['value'], $deleteFilter)) ?
                $request->input('search') : [];
            $filter = (in_array($request->input('search')['value'], $deleteFilter)) ?
                $request->input('search')['value'] : null;
            $assign = (in_array($request->input('search')['value'], $assignFilter)) ?
                $request->input('search')['value'] : null;
        } else {
            $search = [];
            $filter = '';
            $assign = '';
        }

        $start_date = $request->input('start_date');
        $to_date = $request->input('to_date');
        if ($start_date && $to_date) {

            $wheres['created_at'] = [
                '>=',
                Carbon::parse($start_date)->startOfDay()
            ];
            $wheres['created_at'] = [
                '<=',
                Carbon::parse($to_date)->endOfDay()
            ];
        }

        $query['status'] = 'processing';
        $data = $this->repo->ajaxPaginate($length, $start, $query, $filter, $search, $columns, $orders, ["user", "template", "template.pages", "template.pages.items", "assignedRequests", "assignedRequests.user"], $assign);
        return response()->json([
            'data' => FormResource::make($data),
            'draw' => $request->input('draw'),
            'recordsFiltered' => (int)$data->total(),
            'recordsTotal' => (int)$data->total(),
        ]);
    }

    public function endRequest(Request $request)
    {
//        return $request;
//        return \response()->json($request->all());
        $parentFormId = $this->getParentFormId($request->form_id);

        $data = [];
        $data['form_id'] = $request->form_id;
        $data['comment'] = $request->comment;
        $data['file'] = null;
        $data['filelitigation'] = null;
        $data['company_judgment'] = $request->company_judgment;
        $data['type_of_judge'] = $request->type_of_judge;
        $data['case_date'] = $request->case_date == 'null' ? null : $request->case_date;
        $data['case_number'] = $request->case_number == 'null' ? null : $request->case_number;
        $data['expected_result'] = $request->expected_result == 'null' ? null : $request->expected_result;
        $data['fileNames'] = $request->fileNames;
        $data['litigationFileNames'] = $request->litigationFileNames;

        if (!empty($request['files']) && count($request['files']) > 0)
            foreach ($request['files'] as $key => $file) {
              
                $data['files'][] = uploadFile($file, 'media/closed_request', 'public', explode('.',$request->fileNames[$key])[0], $request->fileNames[$key]);
            }

        if (!empty($request['litigation_files']) && count($request['litigation_files']) > 0)
            foreach ($request['litigation_files'] as $key => $file) {
                $data['litigation_files'][] = uploadFile($file, 'media/closed_request', 'public', explode('.',$request->litigationFileNames[$key])[0], $request->litigationFileNames[$key]);
            }

        return $this->repo->closeRequest($data,$parentFormId);

//        if ($request->hasFile('file')) {
//            if (!empty($request->input('folder'))) {
//                $destination_path = 'public/' . $request->folder;
//            } else {
//                $destination_path = 'public/media/closed_request';
//            }
//            $file = $request->file('file');
//            $fileName = time() . rand(0, 999999999) . '.' . $file->getClientOriginalExtension();
//            $path = $request->file('file')->storeAs($destination_path, $fileName);
//            $path = url('/storage') . '/' . str_replace('public/', '', $path);
//            $data['file'] = $path;
//        }

//        if ($request->hasFile('filelitigation')) {
//            if (!empty($request->input('folder'))) {
//                $destination_path = 'public/' . $request->folder;
//            } else {
//                $destination_path = 'public/media/closed_request';
//            }
//            $filelitigation = $request->file('filelitigation');
//            $fileName = time() . rand(0, 999999999) . '.' . $filelitigation->getClientOriginalExtension();
//            $path = $request->file('file')->storeAs($destination_path, $fileName);
//            $path = url('/storage') . '/' . str_replace('public/', '', $path);
//            $data['filelitigation'] = $path;
//        }
    }

    public function filterByDate(Request $request){
        $fromDate = $request->input('fromDate');
        $toDate = $request->input('toDate');
        $data = Form::whereBetween('created_at', [$fromDate, $toDate])->get();
        // Return the filtered data
        return response()->json($data);
    }
    public function getEmployeeUsers()
    {
        $employees = [];
        $users = User::with('roles')->where('name', '!=', 'ROOT')->where('id', '!=', auth()->user()->id)->get();
        foreach ($users as $user) {
            if ($user->roles->contains('name', 'Employee')) {
                $employees[] = $user;
            }
        }
        return response()->json(['data' => $employees], 200);
    }

    public function getEmployeeWithoutper()
    {
        $users_take_permissions = \DB::table('model_has_permissions')
            ->get()
            ->pluck('model_id')
            ->toArray();
        $employees = [];
        $users = User::with('roles')
            ->where('name', '!=', 'ROOT')
            ->where('id', '!=', auth()->user()->id)
//            ->whereNotIn('id',$users_take_permissions)
            ->get();
        foreach ($users as $user) {
            if ($user->roles->contains('name', 'Employee')) {
                $employees[] = $user;
            }
        }
        return response()->json(['data' => $employees], 200);
    }

    public function getFillterFormData()
    {

        $organizations = [];
        $templates = [];
        $employees = [];
        $user = Auth::user();
        $templateOrganizations = TemplateOrganization::pluck('template_id');

        if ($user->hasRole('Root')) {
        $templates = Template::whereNotIn('id', $templateOrganizations)->get();

            $users = User::with('roles')->where('name', '!=', 'ROOT')
                ->where('id', '!=', auth()->user()->id)->select('id', 'name')->get();
            foreach ($users as $user) {
                if ($user->roles->contains('name', 'Employee')) {
                    $employees[] = $user;
                }
            }
            $organizations = Organization::select('id', 'name')->get();
        }
        elseif ($user->hasRole('Employee') && !$user->hasRole('Admin')) {
            $organizations [] = Organization::where('id', $user->organization_id)->select('id', 'name')->first();
            foreach ($user->adminTemplates()->get() as $template) {
                $templatesArray [] = Template::where('id', $template->id)->first();
                $templates = array_unique($templatesArray);
            }
        } elseif ($user->hasRole('Admin') || ($user->hasRole('Admin') && $user->hasRole('Employee'))) {
            $templates = Template::whereHas('organizations', function ($q) {
                $q->whereIn('organization_id', userOrganization());
            })->orWhereNotIn('id', $templateOrganizations)->get();
            foreach (userOrganization() as $organizationId) {
                $organization = Organization::find($organizationId);
                $organizations[] = $organization;

                $users = User::with('roles')->where('name', '!=', 'ROOT')->where('id', '!=', auth()->user()->id)->where('organization_id', $organizationId)
                    ->select('id', 'name')->get();
                foreach ($users as $user) {
                    if ($user->roles->contains('name', 'Employee')) {
                        $employeesArray[] = $user;
                        $employees = array_unique($employeesArray);
                    }
                }
            }
        }
        return response()->json(['organizations' => $organizations, 'employees' => $employees, 'templates' => $templates], 200);
    }
}
