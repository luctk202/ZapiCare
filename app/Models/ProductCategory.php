<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'product_categories';

    protected $fillable = ['name','status', 'parent_id', 'image','filter_id','short_description','long_description'];

    protected $casts = [
        'filter_id' => 'array'
    ];
}
