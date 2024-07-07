<?php

namespace App\Repositories\OrderCoupon;

use App\Models\OrderCoupon;
use App\Repositories\Base\BaseRepository;

class OrderCouponRepository extends BaseRepository implements OrderCouponInterface
{

    public function model()
    {
        // TODO: Implement model() method.
        return OrderCoupon::class;
    }


}
