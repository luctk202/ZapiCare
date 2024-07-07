<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductStock extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'product_stock';

    protected $fillable = [
        'product_id',
        'attributes',
        'attributes_name',
        'price_sell',
        'price_cost',
        'price_website',
        'num',
        'image'
    ];

    protected $casts = [
        'attributes' => 'array'
    ];
}
