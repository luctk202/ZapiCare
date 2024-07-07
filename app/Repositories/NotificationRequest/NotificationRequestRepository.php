<?php

namespace App\Repositories\NotificationRequest;

use App\Models\NotificationRequest;
use App\Repositories\Base\BaseRepository;

class NotificationRequestRepository extends BaseRepository implements NotificationRequestInterface
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
        return NotificationRequest::class;
    }

}
