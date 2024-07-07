<?php

namespace App\Repositories\NotificationDelete;

use App\Models\NotificationDelete;
use App\Models\NotificationRead;
use App\Repositories\Base\BaseRepository;

class NotificationDeleteRepository extends BaseRepository implements NotificationDeleteInterface
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
        return NotificationDelete::class;
    }

}
