<?php

namespace App\Repositories\Address;

use App\Models\Address;
use App\Repositories\Base\BaseRepository;

class AddressRepository extends BaseRepository implements AddressInterface
{

    public function model()
    {
        // TODO: Implement model() method.
        return Address::class;
    }

}
