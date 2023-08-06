<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Organization extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    public function users() {
        return $this->hasMany(User::class);
    }

    public function admins() {
        return $this->hasMany(UserOrganization::class);
    }

//    protected static function booted()
//    {
//        static::updated(function ($region) {
//            if ($region){
//                $changes = $region->isDirty() ? $region->getDirty() : false;
//                if ($changes) {
//                    unset($changes['updated_at']);
//                    activity()
//                        ->performedOn($region)
//                        ->inLog('region')
//                        ->causedBy(auth()->user())
//                        ->withProperties($changes)
//                        ->log(auth()->user()->name . ' Updated- ' . $region->name);
//
//                }
//            }
//        });
//
//        static::created(function ($region) {
//            $changes = $region->getDirty();
//            if($changes) {
//                unset($changes['id'],$changes['created_at'],$changes['updated_at'],$changes['user_id']);
//                activity()
//                    ->causedBy(auth()->user())
//                    ->inLog('region')
//                    ->performedOn($region)
//                    ->withProperties($changes)
//                    ->log(auth()->user()->name . ' Created - ' . $region->name);
//            }
//        });
//
//    }

}
