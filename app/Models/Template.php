<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Template extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'ar_name',
        'user_id',
        'type',
        'icon',
        'updated_at'
    ];
//    protected static $logAttributes = ['name','type', 'updated_at'];
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
        return $this->belongsTo(User::class);
    }

    public function forms()
    {
        return $this->hasMany(Form::class);
    }
    public function pages()
    {
        return $this->hasMany(TemplatePage::class);
    }
//    public function organization()
//    {
//        return $this->belongsTo(Organization::class);
//    }

    public function admins()
    {
        return $this->belongsToMany(User::class,'user_templates','template_id','user_id')
            ->withPivot('organization_id')->withTimestamps();
    }
    public function organizations()
    {
        return $this->belongsToMany(Organization::class,'template_organizations')->withTimestamps();
    }
}
