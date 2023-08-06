<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CloseFile extends Model
{
    protected $table = 'close_files'; // Add this line to specify the table name explicitly.

    protected $fillable = ['request_file_id','file','fileName'];

    public function request_file() {
        return $this->belongsTo(RequestFile::class);
    }
}
