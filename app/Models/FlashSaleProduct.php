<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSaleProduct extends Model
{

    protected $table = 'flash_sale_product';

    protected $fillable = [
        'product_id',
        'flash_sale_id',
        //'group_discount_id',
        'discount_value',
        'discount_type',
        'start_time',
        'end_time',
        'start_hour',
        'end_hour',
        'status'
    ];

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }
    
}
