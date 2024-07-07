<?php

namespace App\Models;

use App\Repositories\FlashSale\FlashSaleRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;


class Product extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'products';

    protected $fillable = [
        'shop_id',
        'name',
        'unit',
        'description',
        'avatar',
        'images',
        'num',
        'attributes',
        'files',
        'draft',
        'price_sell',
        'price_website',
        'price_cost',
        'category_id',
        'brand_id',
        'weight',
        'length',
        'width',
        'height',
        //'producer_id',
        'barcode',
        'rating',
        'sku',
        'vat_value',
        'vat_type',
        'tax_type',
        'tax_value',
        'warranty_type',
        'warranty_time',
        'approval',
        'status',
        'total_sell',
        'total_revenue',
        'full_avatar',
        'typical',
        'new',
        'short_description',
        'long_description',
        'tag_label_ids'
    ];

    protected $casts = [
        'images' => 'array',
        'attributes' => 'array',
        'files' => 'array',
        'full_avatar' => 'string',
        'tag_label_ids' => 'array',
    ];

    /*public function stocks()
    {
        return $this->hasMany(ProductStock::class, 'product_id');
    }*/

    /**
     * Get the user's first name.
     */

    protected function fullAvatar(): Attribute
    {
        return Attribute::make(
            get: fn() => Storage::url($this->avatar)
        );
    }

    public function stocks()
    {
        return $this->hasMany(ProductStock::class, 'product_id');
    }

    public function province()
    {
        return $this->hasMany(ProductProvince::class, 'product_id');
    }

    public function discounts()
    {
        return $this->hasMany(ProductDiscount::class, 'product_id');
    }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    /*public function producer()
    {
        return $this->belongsTo(User::class, 'producer_id');
    }*/

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }


    public function flash_sale()
    {
        return $this->hasOne(FlashSaleProduct::class, 'product_id')->where('status',
            FlashSaleRepository::STATUS_ACTIVE)->where('start_time', '<=', time())->where('end_time', '>=',
            time())->where('start_hour', '<=', date('h', time()))->where('end_hour', '>', date('h', time()));
    }

    public function cart()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }

    public function filter_attributes()
    {
        return $this->hasMany(FilterAttributeProduct::class, 'product_id');
    }

    public function sampleData()
    {
        return $this->belongsToMany(SampleData::class, 'product_sample_data');
    }

    public function diseases()
    {
        return $this->belongsToMany(Disease::class, 'disease_product');
    }
}
