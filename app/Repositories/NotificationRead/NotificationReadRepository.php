<?php

namespace App\Repositories\NotificationRead;

use App\Models\NotificationRead;
use App\Repositories\Base\BaseRepository;

class NotificationReadRepository extends BaseRepository implements NotificationReadInterface
{


    const STATUS_REQUEST = 0;
    const STATUS_SENT = 1;

    public $aryStatus = [
        self::STATUS_REQUEST => 'Chờ gửi',
        self::STATUS_SENT => 'Đã gửi'
    ];

    public function model()
    {
        // TODO: Implement model() method.
        return NotificationRead::class;
    }

}
