<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotificationRequest extends Model
{
    use SoftDeletes;

    protected $table = 'notification_requests';

    protected $fillable = [
        'title',
        'text',
        'icon',
        'description',
        'status',
        'type',
        'time_send',
    ];
}
