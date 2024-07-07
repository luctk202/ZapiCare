<?php

namespace App\Repositories\ShopCategory;

use App\Models\ShopCategory;
use App\Repositories\Base\BaseRepository;

class ShopCategoryRepository extends BaseRepository implements ShopCategoryInterface
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
        return ShopCategory::class;
    }

}
