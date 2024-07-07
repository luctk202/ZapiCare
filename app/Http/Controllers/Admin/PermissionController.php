<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Permission\CreateRequest;
use App\Http\Requests\Admin\Permission\EditRequest;
use App\Repositories\AdminPermission\AdminPermissionRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public $permissionRepository;

    public function __construct(AdminPermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function index(Request $request){
        $where = [];
        if(!empty($request->name)){
            $where['name'] = ['name', 'like', $request->name];
        }
        $permissions = $this->permissionRepository->paginate($where, ['id' => 'DESC']);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách"]
        ];
        return view('admin.content.permission.list')->with(compact('permissions', 'breadcrumbs'));
    }

    public function create(){
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.permission.index'), 'name' => "Danh sách quyền"], ['name' => 'Tạo mới']
        ];
        return view('admin.content.permission.create')->with(compact('breadcrumbs'));
    }

    public function store(CreateRequest $request){
        $data = $request->only(['name', 'display_name', 'description']);
        $data['name'] = Str::lower($data['name']);
        $data['name'] = Str::snake($data['name']);
        $permission = $this->permissionRepository->first(['name' => $data['name']]);
        if($permission){
            return back()->withErrors([
                'name' => 'Mã quyền đã tồn tại'
            ])->withInput();
        }
        $this->permissionRepository->create($data);
        return redirect()->route('admin.permission.index');
    }

    public function edit($id){
        $permission = $this->permissionRepository->find($id);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.permission.index'), 'name' => "Danh sách quyền"], ['name' => 'Sửa']
        ];
        return view('admin.content.permission.edit', compact('permission', 'breadcrumbs'));
    }

    public function update($id, EditRequest $request){
        $permission = $this->permissionRepository->find($id);
        $data = $request->only(['name', 'display_name', 'description']);
        $data['name'] = Str::lower($data['name']);
        $data['name'] = Str::snake($data['name'], '_');
        $old_permission = $this->permissionRepository->first(['name' => $data['name']]);
        if($old_permission && $old_permission->id != $permission->id){
            return back()->withErrors([
                'name' => 'Mã quyền đã tồn tại'
            ])->withInput();
        }
        $this->permissionRepository->edit($permission, $data);
        return redirect()->route('admin.permission.index');
    }

    public function delete($id){
        $permission = $this->permissionRepository->find($id);
        $this->permissionRepository->delete($permission);
        return response(['result' => true]);
    }


}
