<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    use HasFactory;
    protected $table = 'test_results';
    protected $fillable = [
        'test_item',
        'measured_value',
        'file_upload_id',
        'user_id'
    ];
    public function fileUpload()
    {
        return $this->belongsTo(FileUpload::class);
    }
    public function user(){
        return $this->belongsTo(User::class);
    }
}
