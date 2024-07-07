<?php

namespace App\Repositories\Ward;

use App\Models\Ward;
use App\Repositories\Base\BaseRepository;

class WardRepository extends BaseRepository implements WardInterface
{

    public function model()
    {
        // TODO: Implement model() method.
        return Ward::class;
    }

}
