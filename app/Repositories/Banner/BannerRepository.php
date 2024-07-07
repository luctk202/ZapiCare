<?php

namespace App\Repositories\Banner;

use App\Models\Banner;
use App\Repositories\Base\BaseRepository;

class BannerRepository extends BaseRepository implements BannerInterface
{

    const POSITION_TOP_HOME = 1;
    const POSITION_BETWEEN_HOME = 2;
    const POSITION_BOTTOM_HOME = 3;
//    const POSITION_CATEGORY = 2;
//    const POSITION_GROUP = 3;

    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 0;

    const TG_NEW_TAB = 1;
    const TG_CURRENT_TAB = 0;

    public $aryPosition = [
        self::POSITION_TOP_HOME => 'Banner Top',
        self::POSITION_BETWEEN_HOME => 'Banner Between',
        self::POSITION_BOTTOM_HOME => 'Banner Bottom',
    ];

    public $aryStatus = [
        self::STATUS_ACTIVE => 'Hiển thị',
        self::STATUS_BLOCK => 'Ẩn'
    ];

    public $aryTagrget = [
        self::TG_CURRENT_TAB => 'Tab hiện tại',
        self::TG_NEW_TAB => 'Tab mới'
    ];

    public function model()
    {
        // TODO: Implement model() method.
        return Banner::class;
    }

}
