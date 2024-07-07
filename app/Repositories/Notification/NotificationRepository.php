<?php

namespace App\Repositories\Notification;

use App\Models\Notification;
use App\Repositories\Base\BaseRepository;

class NotificationRepository extends BaseRepository implements NotificationInterface
{


    const STATUS_REQUEST = 0;
    const STATUS_SENT = 1;

    const TYPE_PUBLIC = 1;
    const TYPE_MEMBER = 2;

    public $aryStatus = [
        self::STATUS_REQUEST => 'Chờ gửi',
        self::STATUS_SENT => 'Đã gửi'
    ];

    public function model()
    {
        // TODO: Implement model() method.
        return Notification::class;
    }

}
