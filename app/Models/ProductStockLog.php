<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductStockLog extends Model
{
    use HasFactory;

    protected $table = 'product_stock_logs';

    protected $fillable = [
        'product_id',
        'product_stock_id',
        'price_cost',
        'number',
        'created_time',
        'admin_id'
    ];
}
