<?php


namespace App\Repositories\Eloquent;

use App\Models\Package;
use App\Repositories\Contracts\IPackage;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class PackageRepository extends BaseRepository implements IPackage
{
    public function model()
    {
        return Package::class;
    }
    public function getPackages()
    {
        return $this->getModelClass()->where('is_used', false)->get();
    }

    public function getAvailablePackages()
    {
        return $this->getModelClass()
            ->where('is_used', false)
            ->where(function ($query) {
                $query->where('expire_date', '>', Carbon::now());
                $query->orWhere('expire_date', null);
                $query->orWhere('expire_date', '');
            })
            ->orderBy('price', 'ASC')
            ->get();
    }

    public function createWithFeatures(array $data, array $features)
    {
        try {
            DB::beginTransaction();
            $package = parent::create($data);
            foreach ($features as $key => $fet) {
                $package->features()->attach($fet['id'], [
                    "max_count" => $fet['max_count'],
                ]);
            }
            DB::commit();
            return $package;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateWithFeatures($id, array $data, array $features)
    {
        try {
            DB::beginTransaction();
            $package =  $this->model->find($id);
            if ($package->users()->count() > 0) {
                $package->is_used = true;
                $package->save();
                $package = parent::create($data);
                foreach ($features as $key => $fet) {
                    $package->features()->attach($fet['id'], [
                            "max_count" => $fet['max_count'],
                        ]);
                }
            } else {
                $package = parent::update($id, $data);
                $package->features()->detach();
                foreach ($features as $key => $fet) {
                    $package->features()->attach($fet['id'], [
                            "max_count" => $fet['max_count'],
                        ]);
                }
            }
            DB::commit();
            return $package;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
