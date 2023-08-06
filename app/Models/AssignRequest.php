<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AssignRequest extends Model
{

    protected $fillable = [
        'user_id',
        'form_id',
        'assigner_id',
        'date',
        'status'
    ];
    protected $casts = [
        'date' => 'date:Y-m-d',
        'created_at' => "datetime:Y-m-d H:i",
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

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class,'assigner_id');
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}
