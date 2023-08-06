<?php


namespace App\Repositories\Eloquent;


use App\Models\Role;
use App\Repositories\Contracts\IRole;
use Exception;
use Illuminate\Support\Facades\DB;

class RolesRepository extends BaseRepository implements IRole
{
    public function model()
    {
        return Role::class;
    }

    public function all()
    {
        // return $this->getModelClass()->where('name', 'like', primaryID() . ".%")->with('permissions')->get();
        return $this->getModelClass()->with('permissions')->get();
    }

    public function getById($id)
    {
        return $this->getModelClass()->with('permissions','organizations')->where('id',$id)->first();
    }

    public function getByNameIn(array $roles)
    {
        return $this->getModelClass()
            ->whereIn('name', $roles)
            ->get();
    }

    public function createWithPermissions(array $data, array $permissions = null, array $organizations)
    {
        try {
            DB::beginTransaction();
            $role = parent::create($data);
            if($permissions)
                $role->syncPermissions($permissions);
            if (count($organizations) > 0)
                $role->organizations()->attach($organizations);
            DB::commit();

            return $role;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateWithPermissions($id, array $data, array $permissions = null, array $organizations)
    {
        try {
            DB::beginTransaction();
            $role = parent::update($id, $data);
            if($permissions)
            {
                $role->syncPermissions($permissions);
            }
            else
            {
                $perms = $role->getAllPermissions();
                foreach ($perms as $perm)
                {
                    $role->revokePermissionTo($perm);
                }
            }
            DB::table('role_organization')->where('role_id','=',$id)->delete();
            if (count($organizations) > 0) {
                $role->organizations()->attach($organizations);
            }
            DB::commit();

            return $role;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
