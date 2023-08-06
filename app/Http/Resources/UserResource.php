<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_reset_password' => $this->user_reset_password,
            'email' => $this->email,
            'phone' => $this->phone,
            'name' => $this->name,
            'type' => $this->type,
            'avatar' => $this->avatar ? asset("storage/$this->avatar") : '',
            'active' => $this->active,
            'deleted_at' => $this->deleted_at,
            'department_id' => $this->department_id,
            'organization_id' => $this->organization_id,
            'organization' => $this->organization,
            'notifications' => $this->notifications,
            'permissions' => PermissionResource::collection($this->getAllPermissions()),
            'organizations' => $this->organization_admin,
            'templates' => $this->adminTemplates,
            'roles' => $this->getRoleNames(),
            'create_dates' => [
//                 'created_at_human' => $this->created_at->diffForHumans(),
                'created_at' => $this->created_at
            ],
            'updated_dates' => [
                // 'updated_at_human' => $this->updated_at->diffForHumans(),
                'updated_at' => $this->updated_at
            ]
        ];
    }
}
