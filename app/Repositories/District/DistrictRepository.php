<?php

namespace App\Repositories\District;

use App\Models\District;
use App\Repositories\Base\BaseRepository;

class DistrictRepository extends BaseRepository implements DistrictInterface
{

    public function model()
    {
        // TODO: Implement model() method.
        return District::class;
    }

}
