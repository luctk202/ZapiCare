<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductDiscount extends Model
{
    use HasFactory;

    protected $table = 'product_discount';

    protected $fillable = [
        //'user_id',
        'product_id',
        'group_id',
        'discount_value',
        'discount_type'
    ];

    /*protected $casts = [
        'attributes' => 'array'
    ];*/
}
