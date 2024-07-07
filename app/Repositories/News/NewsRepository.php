<?php

namespace App\Repositories\News;

use App\Models\News;
use App\Repositories\Base\BaseRepository;

class NewsRepository extends BaseRepository implements NewsInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 0;

    const TYPE_PRODUCT = 1;
    const TYPE_USER = 2;

    public $aryStatus = [
        self::STATUS_ACTIVE => 'Hiển thị',
        self::STATUS_BLOCK => 'Ẩn'
    ];

    public $aryType = [
        self::TYPE_PRODUCT => 'Chia sẻ sản phẩm',
        self::TYPE_USER => 'Tuyển dụng đại lý'
    ];

    public function model()
    {
        // TODO: Implement model() method.
        return News::class;
    }
}
