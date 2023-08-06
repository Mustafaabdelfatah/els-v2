<?php

namespace App\Models;

use App\Models\ReturnRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UploadedFile  extends Model
{
    use HasFactory;
    protected $fillable = ['uploadable_type','uploadable_id','file','name'];

    public function uploadable()
    {
        return $this->morphTo();
    }

    public function returnRequest()
    {
        return $this->belongsTo(ReturnRequest::class, 'requestReturn_id');
    }



}
