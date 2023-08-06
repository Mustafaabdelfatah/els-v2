<?php

namespace App\Models;

use App\Models\File;
use App\Models\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;

class ReturnRequest extends Model
{
    protected $table = 'request_return';
    protected $fillable = [
        'form_id',
        'file',
        'Priority',
        'end_date',
        'is_secret',
        'approve_secret',
        'is_need_info',
        'informationText',
        'value',
        'status',
        'user_id',
        "type",
        "comment"
    ];
    protected $appends = ['created'];
//    public function data($value) {
//        return json_decode($value);
//    }

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
    public function form(){
        return $this->belongsTo(Form::Class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function uploadedFiles()
    {
        return $this->morphMany(UploadedFile::class, 'uploadable');
    }
    // public function files()
    // {
    //     return $this->hasMany(File::class, 'requestReturn_id');
    // }
    public function getCreatedAttribute()
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i');
    }

    public function rejectedFiles()
    {
        return $this->morphMany(UploadedFile::class, 'uploadable_id')->where('uploadable_type','Rejected');
    }
}
