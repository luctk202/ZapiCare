<?php

namespace App\Repositories\ProductDiscount;

use App\Models\ProductDiscount;
use App\Repositories\ProductStock\ProductStockInterface;
use App\Repositories\Base\BaseRepository;

class ProductDiscountRepository extends BaseRepository implements ProductStockInterface
{
    const DISCOUNT_TYPE_PERCENT = 1;
    const DISCOUNT_TYPE_FIAT = 2;

    public $aryDiscountType = [
        self::DISCOUNT_TYPE_PERCENT => '%',
        self::DISCOUNT_TYPE_FIAT => 'Vnđ'
    ];

    public function model()
    {
        // TODO: Implement model() method.
        return ProductDiscount::class;
    }
}
