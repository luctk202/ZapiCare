<?php

namespace App\Repositories\Discount;

use App\Models\Brand;
use App\Models\Discount;
use App\Models\ProductAttribute;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Cache;

class DiscountRepository extends BaseRepository implements DiscountInterface
{

    const STATUS_SHOW = 1;
    const STATUS_HIDE = 0;
    public $status = [
        self::STATUS_SHOW => 'Hiện',
        self::STATUS_HIDE => 'Ẩn',
    ];

    const DISCOUNT_TYPE_PERCENT = 1;
    const DISCOUNT_TYPE_FIAT = 2;

    public $aryDiscountType = [
        self::DISCOUNT_TYPE_PERCENT => '%',
        self::DISCOUNT_TYPE_FIAT => 'Vnđ'
    ];

    public function model()
    {
        // TODO: Implement model() method.
        return Discount::class;
    }

    public function active_all()
    {
        return $this->get(['status' => self::STATUS_SHOW]);
        /*$brand = Cache::rememberForever('brand', function () {
            return $this->get(['status' => self::STATUS_SHOW]);
        });
        return $brand;*/
    }
}
