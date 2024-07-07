<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestMeasurement extends Model
{
    use HasFactory;

    protected $table = 'test_measurements';
    protected $fillable = [
        'test_item_id',
        'file_upload_id',
        'measured_value',
        'user_id',
        'normal_range_min',
        'normal_range_max',
        'contact_id',
        'test_system_id',
        'measured_values',
        'follow'
    ];

    public function testItem()
    {
        return $this->belongsTo(TestItem::class);
    }

    public function fileUpload()
    {
        return $this->belongsTo(FileUpload::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
