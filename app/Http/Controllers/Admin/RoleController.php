<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Role\CreateRequest;
use App\Http\Requests\Admin\Role\EditRequest;
use App\Repositories\AdminPermission\AdminPermissionRepository;
use App\Repositories\AdminRole\AdminRoleRepository;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public $roleRepository;
    public $permissionRepository;

    public function __construct(AdminRoleRepository $roleRepository, AdminPermissionRepository $permissionRepository)
    {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        if (!empty($request->name)) {
            $where['name'] = ['name', 'like', $request->name];
        }
        $roles = $this->roleRepository->paginate($where, ['id' => 'DESC']);
        /*$roles->load('permissions');*/
        $roles->loadCount('admins');
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách nhóm"]
        ];
        return view('admin.content.role.list')->with(compact('roles', 'breadcrumbs'));
    }

    public function create()
    {
        $permissions = $this->permissionRepository->pluck('display_name', 'id');
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.role.index'), 'name' => "Danh sách nhóm"], ['name' => 'Tạo mới']
        ];
        return view('admin.content.role.create')->with(compact('permissions', 'breadcrumbs'));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->only(['name', 'description']);
        $permissions = $request->get('permissions', []);
        $this->roleRepository->createWithPermission($data, $permissions);
        return redirect()->route('admin.role.index');
    }

    public function edit($id)
    {
        $permissions = $this->permissionRepository->pluck('display_name', 'id');
        $role = $this->roleRepository->find($id);
        $role_permissions = $role->permissions->pluck('id')->toArray();
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.role.index'), 'name' => "Danh sách nhóm"], ['name' => 'Sửa']
        ];
        return view('admin.content.role.edit', compact('permissions', 'role', 'role_permissions', 'breadcrumbs'));
    }

    public function update($id, EditRequest $request)
    {
        $data = $request->only(['name', 'description']);
        $permissions = $request->get('permissions', []);
        $role = $this->roleRepository->find($id);
        $this->roleRepository->editWithPermission($role, $data, $permissions);
        return redirect()->route('admin.role.index');
    }

    public function delete($id)
    {
        $role = $this->roleRepository->find($id);
        $this->permissionRepository->delete($role);
        return response(['result' => true]);
    }
}
