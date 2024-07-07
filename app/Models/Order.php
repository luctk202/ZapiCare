<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'orders';

    protected $fillable = [
        'user_id', // người mua
        'shop_id',
        'partner_id',
        'total_product',
        'check_review',
        'total_discount',
        'total_discount_product',
        'total_discount_coupon',
        'total_vat',
        'total_fee',
        'total_cost',
        'total_profit',
        'total',
        'total_payment',
        'created_time',
        'payment_time',
        'export_time',
        'confirm_time',
        'cancel_time',
        'confirm_id',
        'payment_id',
        'export_id',
        'cancel_id',
        'status',
        'payment_method',
        'status_payment',
        'name',
        'phone',
        'address',
        'province_id',
        'district_id',
        'ward_id',
        'note',
        'cancel_note',
    ];

    protected $casts = [
    ];

    public function details()
    {
        return $this->hasMany(OrderDetail::class, 'order_id');
    }

    public function coupons()
    {
        return $this->hasMany(OrderCoupon::class, 'order_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function cancel(){
        return $this->belongsTo(User::class, 'cancel_user_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id');
    }

    public function district()
    {
        return $this->belongsTo(District::class, 'district_id');
    }
    public function logs()
    {
        return $this->hasMany(OrderLog::class, 'order_id');
    }


    /*public function user_export()
    {
        return $this->belongsTo(User::class, 'user_export_id');
    }*/
}
