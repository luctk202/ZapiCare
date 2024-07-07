<?php

namespace App\Repositories\SampleData;

use App\Models\SampleData;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\Cache;

class SampleDataRepository extends BaseRepository implements SampleDataInterface
{
    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 0;

    public function model()
    {
        return SampleData::class;
    }

    public function getActiveSampleData()
    {
        return Cache::rememberForever('sample_data', function () {
            return $this->get(['status' => self::STATUS_ACTIVE]);
        });
    }


}
