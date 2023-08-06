<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use App\Notifications\VerifyEmail;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use LdapRecord\Laravel\Auth\AuthenticatesWithLdap;
use LdapRecord\Laravel\Auth\LdapAuthenticatable;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements LdapAuthenticatable
{
    use SoftDeletes,
        HasRoles,
        Notifiable,
        HasApiTokens,
        AuthenticatesWithLdap;

//    protected static $logAttributes = ['name', 'username', 'email', 'password', 'type', 'avatar', 'phone', 'active', 'mail_notify'];
//    protected static $submitEmptyLogs = false;
//    protected static $logOnlyDirty = true;
    protected $guard_name = 'api';
    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'organization_id',
        'department_id',
        'avatar',
        'phone',
        'active',
        'mail_notify',
        'domain',
        'guid',
        'canApply',
        'user_reset_password'
    ];

    protected $dates = [
        'updated_at',
        'created_at',
        'deleted_at',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
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
                        ->log(auth()->user() ? auth()->user()->name . ' Created - ' . $region->name : "USER");

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

    public function users()
    {
        // return $this->hasMany(User::class, 'primary_id', 'id');
        return $this->hasMany(User::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new VerifyEmail);
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function templates()
    {
        return $this->hasMany(Template::class);
    }

    public function assignedForms()
    {
        return $this->hasMany(UserForm::class);
    }

    public function forms()
    {
        return $this->belongsToMany(Form::class, 'users_assigned_forms');
    }

    public function has_forms() {
        return $this->hasMany(Form::class);
    }

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function organization_admin()
    {
        return $this->hasMany(UserOrganization::class);
    }

    public function adminTemplates()
    {
        return $this->belongsToMany(Template::class,'user_templates','user_id','template_id')
            ->withPivot('organization_id')->withTimestamps();
    }
}
