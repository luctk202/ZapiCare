<?php

namespace App\Models;



use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'category_id',
       // 'type',
        'author',
        'video',
        'title',
        'image',
        'description',
        'status',
        'count_view',
        'count_share',
        'content',
        'url_share',
        'type',
        'product_id'
    ];

    protected $casts = [
        'image' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(NewsCategory::class, 'category_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

}
