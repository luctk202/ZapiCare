<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $table = 'order_detail';

    protected $fillable = [
        'order_id',
        'shop_id',
        'user_id',
        'partner_id',
        'product_id',
        'category_id',
        'product_name',
        'product_image',
        'attributes_name',
        'product_sku',
        'product_stock_id',
        'num',
        'price',
        'price_discount',
        'price_cost',
        'total_product',
        'total_discount',
        'total_vat',
        'total_profit',
        'total_cost',
        'total',
        'created_time',
        'payment_time',
        'export_time',
        'status',
        'status_payment',
        'group_discount_id',
        'group_affiliate_id',
        'weight'
        //'codes'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function stock()
    {
        return $this->belongsTo(ProductStock::class, 'product_stock_id');
    }
}
