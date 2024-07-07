<?php

namespace App\Repositories\Coupon;

use App\Models\Coupon;
use App\Repositories\Base\BaseRepository;

class CouponRepository extends BaseRepository implements CouponInterface
{

    const DISCOUNT_TYPE_PERCENT = 1;
    const DISCOUNT_TYPE_FIAT = 2;

    const CONCURRENCY = 1;
    const NO_CONCURRENCY = 0;

    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 0;

    public $aryStatus = [
        self::STATUS_ACTIVE => 'Hiển thị',
        self::STATUS_BLOCK => 'Ẩn'
    ];

    public $aryDiscountType = [
        self::DISCOUNT_TYPE_PERCENT => '%',
        self::DISCOUNT_TYPE_FIAT => 'Vnđ'
    ];

    public function model()
    {
        // TODO: Implement model() method.
        return Coupon::class;
    }

}
