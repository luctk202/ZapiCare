<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCustomer extends Model
{
    use HasFactory;

    protected $table = 'shop_customers';
    protected $fillable = ['user_id','shop_id'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    /*public function province(){
        return $this->belongsTo(Province::class, 'province_id');
    }*/

    public function shop(){
        return $this->belongsTo(Shop::class, 'shop_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
