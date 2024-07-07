<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Analysis extends Model
{

    protected $table = 'analysis';

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
