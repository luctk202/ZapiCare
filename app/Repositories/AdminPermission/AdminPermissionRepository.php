<?php

namespace App\Repositories\AdminPermission;

use App\Models\AdminPermission;
use App\Repositories\Base\BaseRepository;

class AdminPermissionRepository extends BaseRepository implements AdminPermissionInterface
{


    public function model()
    {
        // TODO: Implement model() method.
        return AdminPermission::class;
    }
}
