<?php

namespace App\Repositories\ProductAttribute;

use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Cache;

class ProductAttributeRepository extends \App\Repositories\Base\BaseRepository implements ProductAttributeInterface
{

    public function model()
    {
        // TODO: Implement model() method.
        return ProductAttribute::class;
    }

    public function cache_all()
    {
        $attributes = Cache::rememberForever('product_attributes', function () {
            return $this->all();
        });
        return $attributes;
    }
}
