<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{

    protected $table = 'banners';

    protected $fillable = [
        'name',
        'image',
        'link',
        //'target',
        'status',
        'position',
        'category_id',
        'group_id',
        'start_time',
        'end_time'
    ];

    public function category(){
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
}
