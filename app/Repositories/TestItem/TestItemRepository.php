<?php

namespace App\Repositories\TestItem;

use App\Models\TestItem;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Cache;

class TestItemRepository extends BaseRepository implements TestItemInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 0;

    public function model()
    {
        return TestItem::class;
    }

    public function getActiveTestItems()
    {
        return Cache::rememberForever('test_items', function () {
            return $this->get(['status' => self::STATUS_ACTIVE]);
        });

    }

    public function active_all()
    {
        $test_item = Cache::rememberForever('test_item', function () {
            return $this->get(['status' => self::STATUS_ACTIVE]);
        });
        return $test_item;
    }

    public function getTestItemIdsByTestSystemId($testSystemId)
    {
        return $this->model()::where('test_system_id', $testSystemId)->get(['id', 'name'])->toArray();
    }

}
