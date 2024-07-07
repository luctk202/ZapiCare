<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlashSale extends Model
{

    protected $table = 'flash_sale';

    protected $fillable = [
        'title',
        'image',
        'status',
        'start_hour',
        'end_hour',
        'start_time',
        'end_time',
        'home'
    ];

    public function products(){
        return $this->hasMany(FlashSaleProduct::class, 'flash_sale_id');
    }

}
