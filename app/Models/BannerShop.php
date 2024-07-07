<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerShop extends Model
{

    protected $table = 'banner_shops';

    protected $fillable = [
        'name',
        'image',
        'link',
        //'target',
        'status',
        'position',
        'shop_id',
        'start_time',
        'end_time'
    ];

    public function shop(){
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}
