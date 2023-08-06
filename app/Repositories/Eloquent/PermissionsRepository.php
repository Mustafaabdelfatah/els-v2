<?php


namespace App\Repositories\Eloquent;


use App\Models\User;
use App\Repositories\Contracts\IRole;
use Spatie\Permission\Models\Permission;

class PermissionsRepository extends BaseRepository implements IRole
{
    public function model()
    {
        return Permission::class;
    }

    public function parentPermissions() {
        /*get admin Permissions*/
        return User::with('permissions')->get();
    }
}
