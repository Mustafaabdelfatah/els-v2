<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestNote extends Model
{
    protected $fillable = [
        'assigner_id',
        'form_id',
        'status',
        'notes'
    ];

    protected static function booted()
    {
        static::updated(function ($region) {
            if ($region){
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
            if($changes) {
                unset($changes['id'],$changes['created_at'],$changes['updated_at'],$changes['user_id']);
                activity()
                    ->causedBy(auth()->user())
                    ->inLog('region')
                    ->performedOn($region)
                    ->withProperties($changes)
                    ->log(auth()->user() ? auth()->user()->name . ' Created - ' . $region->name : "Root");
            }
        });

    }
}
