<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApproveRequest extends Model
{
    protected $fillable = [
        'user_id',
        'form_id',
        'workers',
        'commercial',
        'general',
        'is_secret',
        'administrative',
        'informationText',
        'executive',
        'expected_amount',
        'procedureType',
        "Priority",
        "end_date"
    ];

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
}
