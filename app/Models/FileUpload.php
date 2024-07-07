<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileUpload extends Model
{
    use HasFactory;
    protected $table='file_uploads';
    protected $fillable = [
        'original_name',
        'storage_path',
        'file_size',
        'user_id',
        'user_contact_id',

    ];
    public function filExtract()
    {
        return $this->hasMany(FileExtract::class);
    }
    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
