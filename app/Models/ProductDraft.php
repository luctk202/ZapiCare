<?php

namespace App\Models;

use App\Repositories\FlashSale\FlashSaleRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;


class ProductDraft extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'product_drafts';

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
        'product_id',
        'vat_type',
        'tax_type',
        'tax_value',
        'warranty_type',
        'warranty_time',
        'approval',
        'status',
        'total_sell',
        'total_revenue',
        'full_avatar'
    ];

    protected $casts = [
        'images' => 'array',
        'attributes' => 'array',
        'files' => 'array',
        'full_avatar' => 'string'
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
            get: fn () => Storage::url($this->avatar)
        );
    }

    public function stock_drafts()
    {
        return $this->hasMany(ProductStockDraft::class, 'product_draft_id');
    }

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
    public function filter_attribute_drafts(){
        return $this->hasMany(FilterAttributeProductDraft::class, 'product_draft_id');
    }
}
