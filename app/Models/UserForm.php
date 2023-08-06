<?php

namespace App\Models;

use App\Models\Board\WorkflowApplications;
use App\Models\JobRequest\JobRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserForm extends Model
{
    use SoftDeletes;

    protected $table = 'users_assigned_forms';
    protected $fillable = [
        'user_id',
        'job_request_id',
        'type',
        'form_id',
        'assigned_by',
        'notifications',
        'status',
        'action',
        'updated_at'
    ];
//    protected static $logAttributes = ['notifications', 'status', 'action', 'updated_at'];
//    protected static $submitEmptyLogs = false;
//    protected static $logOnlyDirty = true;

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

    public function form()
    {
        return $this->belongsTo(Form::class,'form_id');
    }

    public function assigner()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id');
    }

    public function job()
    {
        return $this->belongsTo(JobRequest::class, 'job_request_id');
    }

    public function stages()
    {
        return $this->hasMany(WorkflowApplications::class, 'application_id');
    }
}
