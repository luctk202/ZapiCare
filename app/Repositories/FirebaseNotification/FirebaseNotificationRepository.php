<?php

namespace App\Repositories\FirebaseNotification;

use App\Models\FirebaseNotification;
use App\Repositories\Base\BaseRepository;

class FirebaseNotificationRepository extends BaseRepository implements FirebaseNotificationInterface
{

    public function model()
    {
        // TODO: Implement model() method.
        return FirebaseNotification::class;
    }
}
