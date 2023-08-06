<?php


namespace App\Repositories\Eloquent;


use App\Models\Department;
use Exception;
use Illuminate\Support\Facades\DB;

class DepartmentsRepository extends BaseRepository
{
    public function model()
    {
        return Department::class;
    }

    public function getById($id)
    {
        return $this->getModelClass()->where('id', $id)->first();
    }


    public function createDepartment(array $data)
    {
        try {
            DB::beginTransaction();
            $department = parent::create($data);
            DB::commit();

            return $department;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateDepartment($id, array $data)
    {
        try {
            DB::beginTransaction();
            $department = parent::update($id, $data);
            DB::commit();

            return $department;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
