<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Disease extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    public function testItems()
    {
        return $this->belongsToMany(TestItem::class, 'disease_test_item');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'disease_product');
    }

    public function sampleData()
    {
        return $this->belongsToMany(SampleData::class, 'disease_sample_data');
    }
}
