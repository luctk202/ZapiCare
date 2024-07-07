<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{

    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'num',
        'num_used',
        'max_per_person',
        'shop_id',
        'partner_id',
        'category_id',
        'product_id',
        'min_total_order',
        'discount_value',
        'discount_type',
        'discount_max_value',
        'source_id',
        'concurrency',
        'start_time',
        'end_time',
        'description',
        'status',
    ];

    public function source(){
        return $this->belongsTo(Shop::class, 'source_id');
    }

    public function product(){
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function category(){
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

//    public function shop(){
//        return $this->belongsTo(Shop::class, 'shop_id');
//    }

}
