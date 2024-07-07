<?php

namespace App\Repositories\Blog;

use App\Models\Blog;
use App\Repositories\Base\BaseRepository;

class BlogRepository extends BaseRepository implements BlogInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 0;

    public $aryStatus = [
        self::STATUS_ACTIVE => 'Hiển thị',
        self::STATUS_BLOCK => 'Ẩn'
    ];

    public function model()
    {
        // TODO: Implement model() method.
        return Blog::class;
    }

}
