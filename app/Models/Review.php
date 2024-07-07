<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';
    protected $fillable = [
        'shop_id',
        'user_id',
        'name',
        'product_id',
        'images',
        'rating',
        'comment',
        'status',
        'viewed',
        'parent_id',
    ];

    protected $casts = [
        'images' => 'array',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class, 'parent_id');
    }

    public function childrenReviews()
    {
        return $this->hasMany(Review::class, 'parent_id')->with('reviews');
    }
}
