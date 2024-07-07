<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestSystem extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'test_item',
        'status'
    ];

    public function testItem()
    {
        return $this->hasMany(TestItem::class);
    }
}
