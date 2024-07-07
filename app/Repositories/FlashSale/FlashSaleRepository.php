<?php

namespace App\Repositories\FlashSale;

use App\Models\FlashSale;
use App\Repositories\Base\BaseRepository;

class FlashSaleRepository extends BaseRepository implements FlashSaleInterface
{

    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 0;

    const HOME = 1;

    const DISCOUNT_TYPE_PERCENT = 1;
    const DISCOUNT_TYPE_FIAT = 2;

    public $aryDiscountType = [
        self::DISCOUNT_TYPE_PERCENT => '%',
        self::DISCOUNT_TYPE_FIAT => 'Vnđ'
    ];

    public $aryStatus = [
        self::STATUS_ACTIVE => 'Hiển thị',
        self::STATUS_BLOCK => 'Ẩn'
    ];

    public function model()
    {
        // TODO: Implement model() method.
        return FlashSale::class;
    }

}
