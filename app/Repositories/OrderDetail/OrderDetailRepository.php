<?php

namespace App\Repositories\OrderDetail;

use App\Models\OrderDetail;
use App\Repositories\Base\BaseRepository;

class OrderDetailRepository extends BaseRepository implements OrderDetailInterface
{

    public function model()
    {
        // TODO: Implement model() method.
        return OrderDetail::class;
    }

    public function getByOrderId($orderId)
    {
        return OrderDetail::where('order_id', $orderId)->get();
    }
}
