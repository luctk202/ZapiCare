<?php

namespace App\Models;

use App\Repositories\FlashSale\FlashSaleRepository;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;


class Page extends Model
{
    use HasFactory;

    protected $table = 'pages';

    protected $fillable = [
        'type',
        'title',
        'slug',
        'content',
    ];

}
