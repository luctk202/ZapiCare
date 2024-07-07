<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderLog extends Model
{
    protected $table = 'order_logs';

    protected $fillable = [
        'order_id',
        'user_id',
        'user_name',
        'user_phone',
        'status',
        'title',
        'description',
        'time'
    ];
}
