<?php

namespace App\Repositories\Disease;


use App\Models\Discount;

use App\Models\Disease;
use App\Repositories\Base\BaseRepository;


class DiseaseRepository extends BaseRepository implements DiseaseInterface
{

    public function model()
    {
        // TODO: Implement model() method.
        return Disease::class;
    }

}
