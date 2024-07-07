<?php

namespace App\Repositories\Level;

use App\Models\Level;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Cache;

class LevelRepository extends BaseRepository implements LevelInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 0;

    public function model()
    {
        return Level::class;
    }
    public function getActiveLevels()
    {
        return Cache::rememberForever('levels', function () {
            return $this->get(['status' => self::STATUS_ACTIVE]);
        });
    }
    public function active_all()
    {
        $level = Cache::rememberForever('levels', function () {
            return $this->get(['status' => self::STATUS_ACTIVE]);
        });
        return $level;
    }

}
