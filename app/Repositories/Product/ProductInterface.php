<?php

namespace App\Repositories\Product;

use App\Repositories\Base\BaseInterface;

interface ProductInterface extends BaseInterface
{

    /**
     * @param $data
     * @param $stock
     * @return mixed
     */
    public function createWithStock($data, $stock);
}
