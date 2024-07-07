<?php

namespace App\Repositories\Partner;

use App\Models\Partner;
use App\Models\Shop;
use App\Repositories\Base\BaseRepository;

class PartnerRepository extends BaseRepository implements PartnerInterface
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
        return Partner::class;
    }

}
