<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;

    protected $table = 'product_attributes';

    protected $fillable = ['name', 'slug', 'shop_id'];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

}
