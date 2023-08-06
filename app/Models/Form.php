<?php

namespace App\Models;

use DB;
use Laravel\Scout\Searchable;
use App\Models\ApproveRequest;
use Illuminate\Support\Carbon;
use App\Http\Resources\FormResource;
use PhpParser\Node\Expr\Cast\Object_;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Form extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];

//    use Searchable;
    protected $fillable = [
        'name',
        'status',
        'department',
        'user_id',
        'template_id',
        'comment',
        "parent",
        'organization_id',
        "created_at",
        "update_at"
    ];
//    protected static $logAttributes = ['name'];
//    protected static $submitEmptyLogs = false;

//    protected static $logOnlyDirty = true;

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'department' => $this->department,
            'job_level' => $this->job_level,
        ];
    }

    protected $casts = [
        'expires_at' => 'date',
        'created_at' => "datetime:Y-m-d H:i",
        'updated_at' => "datetime:Y-m-d H:i",
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
                    ->log(auth()->user() ? auth()->user()->name . ' Created - ' . $region->name : "Root");
            }
        });

        static::deleting(function ($form) {
            $pages = FormPage::where('form_id', $form->id)->get();
            $form->closed()->delete();
            $form->amendmentRequest()->delete();
            $form->assignedRequests()->delete();
            $children = $form->children()->get();
            if($children)
            {
                $children->each(function ($child){
                    $childPages = FormPage::where('form_id', $child->id)->get();
                    $child->closed()->delete();
                    $child->amendmentRequest()->delete();
                    $child->assignedRequests()->delete();
                    $childPages->each(function ($page) {
                        $items = $page->items()->get();
                        $items->each(function ($item) {
                            $item->filling()->delete();
                        });
                        $page->items()->delete();
                        $page->delete();
                    });
                    $child->delete();
                });
            }
            $pages->each(function ($page) {
                $items = $page->items()->get();
                $items->each(function ($item) {
                    $item->filling()->delete();
                });
                $page->items()->delete();
                $page->delete();
            });
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function template()
    {
        return $this->belongsTo(Template::class);
    }

    public function pages()
    {
        return $this->hasMany(FormPage::class);
    }


    public function userforms()
    {
        return $this->hasMany(UserForm::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_assigned_forms');
    }

    public function parent_form()
    {
        return $this->belongsTo(self::class, 'parent')->with('parent_form',
            'pages',
            'pages.items',
            'pages.items.filling',
            'assignedRequests',
            'assignedRequests.user',
            'assignedRequests.assigner',
            'amendmentRequest',
            'amendmentRequest.user',
            'closed',
            'requestNotes',
        );
    }

    public function histories()
    {
        $histories = [
            $this
        ];
        $parent = $this->parent_form;
        while ($parent) {
            $histories[] = $parent;
            $parent = $parent->parent_form;
        }

         return $histories;
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent')->orderBy("id", "DESC");
    }

    public function lastchild()
    {
        return $this->hasMany(self::class, 'parent')->select('id')->orderBy("id", "DESC");
    }

    public function assignedRequests()
    {
        return $this->hasMany(AssignRequest::class);
    }

    public function amendmentRequest()
    {
        return $this->hasMany(ReturnRequest::class);
    }

    public function approve()
    {
        return $this->hasMany(ApproveRequest::class);
    }

    public function closed()
    {
        return $this->hasOne(RequestFile::class, 'form_id');
    }

    public function requestNotes()
    {
        return $this->hasOne(RequestNote::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

 
}
