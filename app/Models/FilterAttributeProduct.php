<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FilterAttributeProduct extends Model
{
    use HasFactory;

    protected $table = 'filter_attribute_product';

    protected $fillable = ['filter_attribute_id' , 'product_id', 'category_id'];

}
