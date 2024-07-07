<?php

namespace App\Repositories\FilterAttributeProduct;

use App\Models\FilterAttributeProduct;
use App\Repositories\Base\BaseRepository;

class FilterAttributeProductRepository extends BaseRepository implements FilterAttributeProductInterface
{

    public function model()
    {
        // TODO: Implement model() method.
        return FilterAttributeProduct::class;
    }
}
