<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name_en',
        'name_ar',
        'description',
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
    ];

    protected static function booted()
    {
        static::updated(function ($region) {
            if ($region) {
                $changes = $region->isDirty() ? $region->getDirty() : false;
                if ($changes) {
                    unset($changes['updated_at']);
                    activity()
                        ->performedOn($region)
                        ->inLog('region')
                        ->causedBy(auth()->user())
                        ->withProperties($changes)
                        ->log(auth()->user()->name . ' Updated- ' . $region->name);

                }
            }
        });

        static::created(function ($region) {
            $changes = $region->getDirty();
            if ($changes) {
                unset($changes['id'], $changes['created_at'], $changes['updated_at'], $changes['user_id']);
                activity()
                    ->causedBy(auth()->user())
                    ->inLog('region')
                    ->performedOn($region)
                    ->withProperties($changes)
                    ->log(auth()->user()->name . ' Created - ' . $region->name);
            }
        });

    }
}
