<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
    use HasFactory;

    protected $table = 'shop_category';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'shop_id',
        'category_id'
    ];

    /*public function province(){
        return $this->belongsTo(Province::class, 'province_id');
    }*/

    public function shop(){
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}
