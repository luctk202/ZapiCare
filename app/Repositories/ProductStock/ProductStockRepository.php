<?php

namespace App\Repositories\ProductStock;

use App\Models\ProductAttribute;
use App\Models\ProductStock;
use Illuminate\Support\Facades\Cache;
use App\Repositories\Base\BaseRepository;

class ProductStockRepository extends BaseRepository implements ProductStockInterface
{

    public function model()
    {
        // TODO: Implement model() method.
        return ProductStock::class;
    }

}
