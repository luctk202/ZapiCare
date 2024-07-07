<?php

namespace App\Repositories\FlashSaleProduct;

use App\Models\FlashSaleProduct;
use App\Repositories\Base\BaseRepository;

class FlashSaleProductRepository extends BaseRepository implements FlashSaleProductInterface
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
        return FlashSaleProduct::class;
    }

}
