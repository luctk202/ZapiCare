<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderCoupon extends Model
{
    protected $table = 'order_coupon';

    protected $fillable = [
        'order_id',
        'shop_id',
        'user_id',
        'code',
        'coupon_source',
        'coupon_value'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function stock()
    {
        return $this->belongsTo(ProductStock::class, 'product_stock_id');
    }
}
