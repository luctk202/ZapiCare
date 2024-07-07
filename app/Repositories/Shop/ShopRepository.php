<?php

namespace App\Repositories\Shop;

use App\Models\Shop;
use App\Repositories\Base\BaseRepository;

class ShopRepository extends BaseRepository implements ShopInterface
{

    const STATUS_SHOW = 1;
    const STATUS_HIDE = 0;

    public $aryStatus = [
        self::STATUS_SHOW => 'Hiện',
        self::STATUS_HIDE => 'Ẩn',
    ];

    public function model()
    {
        // TODO: Implement model() method.
        return Shop::class;
    }

}
