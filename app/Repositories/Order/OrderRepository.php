<?php

namespace App\Repositories\Order;

use App\Models\Order;
use App\Repositories\Base\BaseRepository;

class OrderRepository extends BaseRepository implements OrderInterface
{

    const STATUS_NEW = 1;
    const STATUS_CONFIRM = 2;
    const STATUS_CANCEL = 3;
    const STATUS_DONE = 5;

    const STATUS_PAYMENT_UNPAID = 1;
    const STATUS_PAYMENT_PAID = 2;

    const METHOD_COD = 1;
    //const METHOD_WALLET = 2;
    const METHOD_ONLINE = 3;
    const METHOD_TRANSFER = 5;

    public $aryMethod = [
        self::METHOD_COD => 'COD',
        //self::METHOD_WALLET => 'Ví',
        self::METHOD_ONLINE => 'Online',
        self::METHOD_TRANSFER => 'Chuyển khoản',
    ];
    public $aryStatus = [
        self::STATUS_NEW => 'Chờ xử lý',
        self::STATUS_CONFIRM => 'Đã xác nhận',
        self::STATUS_CANCEL => 'Đã hủy',
        self::STATUS_DONE => 'Đã giao hàng',
    ];

    public $aryStatusPayment = [
        self::STATUS_PAYMENT_PAID => 'Đã thanh toán',
        self::STATUS_PAYMENT_UNPAID => 'Chưa thanh toán',
    ];

    public function model()
    {
        // TODO: Implement model() method.
        return Order::class;
    }


}
