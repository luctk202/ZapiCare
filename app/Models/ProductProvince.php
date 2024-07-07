<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductProvince extends Model
{
    use HasFactory;

    protected $table = 'product_province';

    protected $fillable = [
        'product_id',
        'province_id',
    ];
}
