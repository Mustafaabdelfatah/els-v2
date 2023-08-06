<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserTemplate extends Model
{
    protected $fillable = ['user_id','template_id','organization_id'];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function organization() {
        return $this->belongsTo(Organization::class);
    }

    public function template() {
        return $this->belongsTo(Template::class);
    }
}
