<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level_image',
        'status'
    ];

    public function sampleData()
    {
        return $this->hasMany(SampleData::class);
    }

}
