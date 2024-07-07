<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TestMeasurement;
class TestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'test_system_id',
        'status',
        'measured_value',
        'normal_range_min',
        'normal_range_max',
        'mild_low_min',
        'mild_low_max',
        'moderately_low_min',
        'moderately_low_max',
        'severity_low_min',
        'severity_low_max',
        'mild_high_min',
        'mild_high_max',
        'moderately_high_min',
        'moderately_high_max',
        'severity_high_min',
        'severity_high_max',
        'measured_values',
    ];

    public function testSystem()
    {
        return $this->belongsTo(TestSystem::class);
    }


    public function sampleData()
    {
        return $this->hasMany(SampleData::class);
    }
    public function diseases()
    {
        return $this->belongsToMany(Disease::class, 'disease_test_item');
    }

    //
    public function measurements()
    {
        return $this->hasMany(TestMeasurement::class);
    }
//    public static function findOrCreate($attributes, $values = [])
//    {
//        $instance = static::where($attributes)->first();
//
//        if (!$instance) {
//            $instance = static::create(array_merge($attributes, $values));
//        }
//
//        return $instance;
//    }

}
