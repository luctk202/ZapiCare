<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'title',
        'text',
        'icon',
        'item_type',
        'item_id',
        'user_id',
        'type',
        'receiver_id',
        'response',
    ];

    public function read()
    {
        return $this->hasOne(NotificationRead::class, 'notification_id')->where('user_id', auth()->id());
    }

    public function detail(){
        return $this->belongsTo(NotificationRequest::class, 'item_id');
    }
}
