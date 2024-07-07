<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Admin\CreateRequest;
use App\Http\Requests\Admin\Admin\EditRequest;
use App\Http\Requests\Admin\Admin\UpdatePasswordRequest;
use App\Repositories\Admin\AdminRepository;
use App\Repositories\AdminRole\AdminRoleRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class AdminController extends Controller
{
    public $adminRepository;
    public $roleRepository;

    public function __construct(AdminRepository $adminRepository, AdminRoleRepository $roleRepository)
    {
        $this->adminRepository = $adminRepository;
        $this->roleRepository = $roleRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        if (!empty($request->email)) {
            $where['email'] = ['email', 'like', $request->email];
        }
        if (!empty($request->name)) {
            $where['name'] = ['name', 'like', $request->name];
        }
        if ((int)$request->role_id > 0) {
            $where['role_id'] = (int)$request->role_id;
        }
        if ($request->get('status', -1) > -1) {
            $where['status'] = (int)$request->status;
        }
        $admins = $this->adminRepository->paginate($where, ['id' => 'DESC'], [], ['role'], 50);
        $roles = $this->roleRepository->pluck('name', 'id');
        $roles = Arr::prepend($roles->toArray(), 'Vui lòng chọn', 0);
        $status = [
            -1 => 'Vui lòng chọn',
            $this->adminRepository::STATUS_ACTIVE => 'Hoạt động',
            $this->adminRepository::STATUS_BLOCK => 'Khóa',
        ];
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách tài khoản"]
        ];
        return view('admin.content.admin.list')->with(compact('admins', 'roles', 'status', 'breadcrumbs'));
    }

    public function create()
    {
        $roles = $this->roleRepository->pluck('name', 'id');
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.admin.index'), 'name' => "Danh sách tài khoản"], ['name' => 'Tạo mới']
        ];
        return view('admin.content.admin.create')->with(compact('roles', 'breadcrumbs'));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->only(['name', 'email', 'password', 'role_id']);
        $data['status'] = AdminRepository::STATUS_ACTIVE;
        $this->adminRepository->create($data);
        return redirect()->route('admin.admin.index');
    }

    public function edit($id)
    {
        $admin = $this->adminRepository->find($id);
        $roles = $this->roleRepository->pluck('name', 'id');
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.admin.index'), 'name' => "Danh sách tài khoản"], ['name' => 'Sửa']
        ];
        return view('admin.content.admin.edit')->with(compact('roles', 'admin', 'breadcrumbs'));
    }

    public function update($id, EditRequest $request)
    {
        $admin = $this->adminRepository->find($id);
        $data = $request->only(['name', 'role_id']);
        if(!empty($request->password)){
            $data['password'] = $request->password;
        }
        $this->adminRepository->edit($admin, $data);
        return redirect()->route('admin.admin.index');
    }

    public function update_status($id, Request $request)
    {
        $admin = $this->adminRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->adminRepository->edit($admin, $data);
        return response(['result' => true]);
    }

    public function updatePassword($id, UpdatePasswordRequest $request)
    {
        $admin = $this->adminRepository->find($id);
        $data = [
            'password' => (int)$request->password
        ];
        $this->adminRepository->edit($admin, $data);
        return response(['result' => true]);
    }

    public function delete($id){
        $admin = $this->adminRepository->find($id);
        $this->adminRepository->delete($admin);
        return response(['result' => true]);
    }
}
