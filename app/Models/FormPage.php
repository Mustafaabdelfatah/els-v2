<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormPage extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'title',
        'form_id'
    ];
//    protected static $logAttributes = ['title'];
//    protected static $submitEmptyLogs = false;
//    protected static $logOnlyDirty = true;

//    protected static function booted()
//    {
//        static::deleting(function ($formPage) {
//            \Log::debug('form_page', [$formPage]);
//
//            FormPageItem::withTrashed()->where('form_page_id', $formPage->id)->delete();
//        });

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
//
//            $changes = $region->getDirty();
//            if($changes) {
//                unset($changes['id'],$changes['created_at'],$changes['updated_at'],$changes['user_id']);
//                activity()
//                    ->causedBy(auth()->user())
//                    ->inLog('region')
//                    ->performedOn($region)
//                    ->withProperties($changes)
//                    ->log(auth()->user() ? auth()->user()->name . ' Created - ' . $region->name : "Root");
//            }
//        });

//    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function items()
    {
        return $this->hasMany(FormPageItem::class, 'form_page_id');
    }
}
