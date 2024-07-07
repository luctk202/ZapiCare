<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use App\Repositories\Base\BaseRepository;

class CartRepository extends BaseRepository implements CartInterface
{

    public function model()
    {
        // TODO: Implement model() method.
        return Cart::class;
    }


}
