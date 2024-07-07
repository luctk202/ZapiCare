<?php

namespace App\Repositories\FilterAttribute;

use App\Models\FilterAttribute;
use App\Repositories\Base\BaseRepository;

class FilterAttributeRepository extends BaseRepository implements FilterAttributeInterface
{

    public function model()
    {
        // TODO: Implement model() method.
        return FilterAttribute::class;
    }
}
