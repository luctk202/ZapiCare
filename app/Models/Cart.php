<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = 'cart';

    protected $fillable = [
        'user_id',
        'product_id',
        'attributes_name',
        'num',
    ];

    protected $casts = [
        'codes' => 'array'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function stock()
    {
        return $this->belongsTo(ProductStock::class, ['product_id', 'attributes_name'], ['product_id', 'attributes_name']);
    }
}
