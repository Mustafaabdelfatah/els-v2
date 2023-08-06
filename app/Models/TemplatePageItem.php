<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class TemplatePageItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'type',
        'label',
        'notes',
        'comment',
        'width',
        'height',
        'length',
        'input_type',
        'enabled',
        'required',
        'website_view',
        'childList',
        'template_page_id',
    ];
//    protected static $logAttributes = ['type', 'label', 'notes', 'comment', 'width',
//        'height', 'enabled', 'required', 'website_view', 'childList'];
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

    public function page()
    {
        return $this->belongsTo(TemplatePage::class, 'template_page_id');
    }

}
