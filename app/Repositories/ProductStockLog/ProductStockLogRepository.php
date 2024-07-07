<?php

namespace App\Repositories\ProductStockLog;

use App\Models\ProductStockLog;
use App\Repositories\Base\BaseRepository;

class ProductStockLogRepository extends BaseRepository implements ProductStockLogInterface
{

    public function model()
    {
        // TODO: Implement model() method.
        return ProductStockLog::class;
    }

}
