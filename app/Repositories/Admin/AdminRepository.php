<?php

namespace App\Repositories\Admin;

use App\Models\Admin;
use App\Repositories\Base\BaseRepository;

class AdminRepository extends BaseRepository implements AdminInterface
{

    const STATUS_ACTIVE = 1;
    const STATUS_BLOCK = 0;

    public function model()
    {
        // TODO: Implement model() method.
        return Admin::class;
    }
}
