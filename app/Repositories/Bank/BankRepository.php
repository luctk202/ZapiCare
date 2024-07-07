<?php

namespace App\Repositories\Bank;

use App\Models\Bank;
use App\Repositories\Base\BaseRepository;

class BankRepository extends BaseRepository implements BankInterface
{

    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 0;

    public function model()
    {
        // TODO: Implement model() method.
        return Bank::class;
    }

}
