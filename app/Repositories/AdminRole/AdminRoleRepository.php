<?php

namespace App\Repositories\AdminRole;
use App\Models\AdminRole;
use App\Repositories\Base\BaseRepository;
use Illuminate\Support\Facades\DB;

class AdminRoleRepository extends BaseRepository implements AdminRoleInterface
{

    public function model()
    {
        // TODO: Implement model() method.
        return AdminRole::class;
    }

    public function createWithPermission($data, $permission)
    {
        DB::transaction(function () use ($data, $permission) {
            $role = $this->create($data);
            $role->savePermissions($permission);
        });
        return true;
    }

    public function editWithPermission($role, $data, $permission)
    {
        DB::transaction(function () use ($role, $data, $permission) {
            $role = $this->edit($role, $data);
            $role->savePermissions($permission);
        });
        return true;
    }
}
