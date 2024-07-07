<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $table = 'shop';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'partner_id',
        'province_id',
        'province_name',
        'district_id',
        'district_name',
        'description',
        'ward_id',
        'ward_name',
        'code',
        'name',
        'phone',
        'address',
        'status',
        'logo'
    ];

    /*public function province(){
        return $this->belongsTo(Province::class, 'province_id');
    }*/

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function partner(){
        return $this->belongsTo(Partner::class, 'partner_id');
    }


    public function category(){
        return $this->belongsToMany(ProductCategory::class, 'shop_category', 'shop_id', 'category_id');
    }
}
