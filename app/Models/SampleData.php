<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SampleData extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_item_id',
//        'level_id',
//        'range_min',
//        'range_max',
//        'is_normal_range',
        'explanation',
        'symptom',
//        'disease',
        'advice',
    ];

    public function testItem()
    {
        return $this->belongsTo(TestItem::class, 'test_item_id');
    }

    public function level()
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_sample_data');
    }

    public function diseases()
    {
        return $this->belongsToMany(Disease::class, 'disease_sample_data');
    }
}
