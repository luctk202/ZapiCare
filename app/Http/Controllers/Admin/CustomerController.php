<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Customer\CreateRequest;
use App\Http\Requests\Admin\Customer\EditRequest;
use App\Repositories\CustomerGroup\CustomerGroupRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public $userRepository;
    public $customerGroupRepository;

    public function __construct(UserRepository $userRepository){

        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        //$where['type'] = $this->userRepository::TYPE_CUSTOMER;

        if (!empty($request->phone)) {
            $where['phone'] = ['phone', 'like', $request->phone];
        }
//        if (!empty($request->code)) {
//            $where['code'] = ['code', 'like', $request->code];
//        }
        if (!empty($request->name)) {
            $where['name'] = ['name', 'like', $request->name];
        }
        /*if ($request->get('status', -1) > -1) {
            $where['status'] = (int)$request->status;
        }*/
        /*if ($request->get('verified', -1) > -1) {
            $where['verified'] = (int)$request->verified;
        }
        if(!empty($request->group_id)){
            $where['group_id'] = (int)$request->group_id;
        }
        if(!empty($request->parent_id)){
            $where['parent_id'] = (int)$request->parent_id;
        }*/
        $users = $this->userRepository->paginate($where, ['id' => 'DESC']);
        /*$users->load(['parent', 'group']);*/
        //$users->loadCount('children');
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách"]
        ];
        $status = [
            -1 => 'Vui lòng chọn',
            $this->userRepository::STATUS_ACTIVE => 'Hoạt động',
            $this->userRepository::STATUS_BLOCK => 'Khóa',
        ];
        $verified_status = [
            -1 => 'Vui lòng chọn',
            $this->userRepository::VERIFIED => 'Đã xác thực',
            $this->userRepository::NOT_VERIFY => 'Chưa xác thực',
        ];

        return view('admin.content.customer.list')->with(compact('users', 'status', 'breadcrumbs', 'verified_status'));
    }

    public function create(){
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.customer.index'), 'name' => "Danh sách"], ['name' => 'Tạo mới']
        ];
        return view('admin.content.customer.create')->with(compact( 'breadcrumbs'));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->only(['name', 'phone', 'email']);
        //$data['references_code'] = $data['phone'];
        //$data['type'] = $this->userRepository::TYPE_CUSTOMER;
        $data['verified'] = $this->userRepository::VERIFIED;
        $data['verified_time'] = time();
        $data['verified_id'] = auth()->id();
        $data['status'] = $this->userRepository::STATUS_ACTIVE;
        $data['password'] = bcrypt($request->password);
        $this->userRepository->create($data);
        return redirect()->route('admin.customer.index');
    }

    public function edit($id)
    {
        $user = $this->userRepository->find($id);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.customer.index'), 'name' => "Danh sách"], ['name' => 'Sửa']
        ];
        return view('admin.content.customer.edit')->with(compact('user', 'breadcrumbs'));
    }

    public function update($id, EditRequest $request)
    {
        $user = $this->userRepository->find($id);
        $data = $request->only(['name', 'email']);
        if(!empty($request->password)){
            $data['password'] = bcrypt($request->password);
        }
        $this->userRepository->edit($user, $data);
        return redirect()->route('admin.customer.index');
    }

    public function update_status($id, Request $request)
    {
        $user = $this->userRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->userRepository->edit($user, $data);
        return response(['result' => true]);
    }

    public function verified($id){
        $user = $this->userRepository->find($id);
        $data = [
            'verified' => $this->userRepository::VERIFIED,
            'verified_id' => auth('admin')->id(),
            'verified_time' => time(),
        ];
        $this->userRepository->edit($user, $data);
        return response(['result' => true]);
    }

    public function search(Request $request){
        $where = [];
        //$where['type'] = $this->userRepository::TYPE_CUSTOMER;
        if (!empty($request->search)) {
            $where['orWhere'] = [
                ['phone', 'like', $request->search],
                ['name', 'like', $request->search],
                ['email', 'like', $request->search]
            ];
        }
        $users = $this->userRepository->paginate($where, ['id' => 'DESC']);
        return response([
            'result' => true,
            'data' => $users
        ]);
    }
}
