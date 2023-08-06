<?php


namespace App\Repositories\Eloquent;


use App\Models\Organization;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrganizationsRepository extends BaseRepository
{
    public function model()
    {
        return Organization::class;
    }

    public function getById($id)
    {
        return $this->getModelClass()->with('admins','admins.user')->where('id',$id)->first();
    }


    public function createOrganization(array $data)
    {
        try {
            DB::beginTransaction();
            $organization = parent::create($data);
            DB::commit();

            return $organization;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateOrganization($id, array $data)
    {
        try {
            DB::beginTransaction();
            $organization = parent::update($id, $data);
            DB::commit();
            return $organization;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function organizationByAdmin() {
        $data = array();
        $user = Auth::user();
        if (in_array('Root', $user->getRoleNames()->toArray()))
            $data = parent::all();
        elseif (in_array('Employee', $user->getRoleNames()->toArray()) || in_array('Admin', $user->getRoleNames()->toArray()))
            $data = Organization::whereIn('id',userOrganization())->get();
//        } else {
//            $data = parent::all();
//        }
        return $data;
    }
}
