<?php

namespace App\Repositories\Province;

use App\Models\Province;
use App\Repositories\Base\BaseRepository;

class ProvinceRepository extends BaseRepository implements ProvinceInterface
{

    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 0;

    public function model()
    {
        // TODO: Implement model() method.
        return Province::class;
    }

}
