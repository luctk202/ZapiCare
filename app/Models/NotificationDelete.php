<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificationDelete extends Model
{
    protected $table = 'notification_delete';

    protected $fillable = [
        'user_id',
        'notification_id',
    ];
}
