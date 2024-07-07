<?php

namespace App\Repositories\ProductProvince;

use App\Models\ProductAttribute;
use App\Models\ProductProvince;
use App\Models\ProductStock;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Base\BaseRepository;

class ProductProvinceRepository extends BaseRepository implements ProductProvinceInterface
{

    public function model()
    {
        // TODO: Implement model() method.
        return ProductProvince::class;
    }

}
