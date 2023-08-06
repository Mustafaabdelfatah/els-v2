<?php

namespace App\Http\Resources;

use App\Models\Organization;
use App\Models\Template;
use App\Models\User;
use App\Models\UserTemplate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use DB;

class TemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = \Auth::user();
//        $admins = [];
//        $organizations = [];

//        if (!in_array('Root', $user->getRoleNames()->toArray()))
//        {
//            $userOrganizations = $user->organization_admin()->pluck('organization_id');
//            $adminIds = UserTemplate::where('template_id',$this->id)->whereIn('organization_id',$userOrganizations)->groupBy('user_id')->pluck('user_id');
//            $organizationIds = UserTemplate::where('template_id',$this->id)->whereIn('organization_id',$userOrganizations)->groupBy('organization_id')->pluck('organization_id');
//        }else{
//            $adminIds = UserTemplate::where('template_id',$this->id)->groupBy('user_id')->pluck('user_id');
//            $organizationIds = UserTemplate::where('template_id',$this->id)->groupBy('organization_id')->pluck('organization_id');
//        }
//
//        foreach ($organizationIds as $organizationId)
//        {
//            $organization = Organization::find($organizationId);
//            $organizations[] = $organization;
//        }
//
//        $users = User::whereIn('id',$adminIds)->get();
//        if($users->count() > 0)
//        {
//            $admins[] = [
//                'organization_id' => $organizationId,
//                'admins' => $users
//            ];
//        }

//        Template::with(['organizations' => function($query){
//            $query->with('user')
//        }])

//        $adminIds = UserTemplate::where('template_id',$this->id)->with(['user','organization'])->get();
//        dd($adminIds);


//        dd($result);
//        $organizationIds = UserTemplate::where('template_id', $this->id)->groupBy('organization_id')->pluck('organization_id');

        $template = UserTemplate::where('template_id', $this->id)->select('user_id', 'organization_id')->get()->toArray();

        $data = [];
        $datas = [];
        foreach ($template as $item) {
            $data[$item['user_id']][] = $item['organization_id'];
        }

//        dd($data);
        $result = [];
        foreach ($data as $user => $orgs) {
            $result[] = [
                'organization_id' => User::find($user)->organization_id,
                'admins' => $user,
                'organizations' => $orgs,
            ];
        }
//        dd($result);
        $results = collect($result)->chunkWhile(function ($value, $key, $chunk) {
//            dd($value);
            return $value['organizations'] === $chunk->last()['organizations'];
        });

//        dd($result->toArray());

//        $data = collect($result)->each(function ($item) {
        foreach ($results as $result) {
             $datas[] = collect($result)->groupBy('organization_id')->map(function ($item, $key) {
                return [
                    'organization_id' => $key,
                    'admins' => User::find(array_column($item->toArray(), 'admins'))->toArray(),
                    'organizations' => Organization::find($item->first()['organizations'])->toArray(),
                ];
            })->toArray();
        }
//        })->toArray();

//        dd(array_values(\Arr::flatten($datas,1)));
        return [
            'id' => $this->id,
            'name' => $this->name,
            'ar_name' => $this->ar_name,
            'icon' => $this->icon,
            'organizations' => $this->organizations,
            'created_at' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->diffForHumans(),
            'user' => $this->user,
//            'organization' => $this->organization,
            'selectedAdmins' => array_values(\Arr::flatten($datas,1)),
//            'selectedOrganizations' => $organizations,
            'pages' => TemplatePageResource::collection($this->pages),
            'deleted_at' => $this->deleted_at,
            'requests_count' => DB::table('forms')->where('template_id', $this->id)->count()
        ];
    }
}
