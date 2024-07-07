<?php

namespace App\Repositories\Deposit;


use App\Models\Deposit;
use App\Models\WalletTransaction;
use App\Repositories\Base\BaseRepository;

class DepositRepository extends BaseRepository implements DepositInterface
{
    const STATUS_DEPOSIT = 1;
    const STATUS_REFUND = 2;
    const STATUS_CANCEL = 3;


    public $aryStatus = [
        self::STATUS_DEPOSIT => 'Đã đặt cọc',
        self::STATUS_REFUND => 'Đã hoàn cọc',
        self::STATUS_CANCEL => 'Hủy hoàn cọc',
    ];



    public function model()
    {
        // TODO: Implement model() method.
        return Deposit::class;
    }

}
