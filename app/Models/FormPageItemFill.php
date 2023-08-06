<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FormPageItemFill extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'value',
        'file_name',
//        'importance',
//        'frequency',
        'review',
        'comment',
        'form_page_item_id',
        'user_id'];
//    protected static $logAttributes = ['value', 'review', 'comment'];
//    protected static $submitEmptyLogs = false;
//    protected static $logOnlyDirty = true;

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
//
//    }
    public function item()
    {
        return $this->belongsTo(FormPageItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
