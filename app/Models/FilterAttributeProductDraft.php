<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FilterAttributeProductDraft extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'filter_attribute_product_draft';

    protected $fillable = ['filter_attribute_id' , 'product_draft_id', 'category_id'];

}
