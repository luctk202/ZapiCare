<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Producer\CreateRequest;
use App\Http\Requests\Admin\Producer\EditRequest;
use App\Repositories\Package\PackageRepository;
use App\Repositories\PackageProducer\PackageProducerRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class ProducerController extends Controller
{
    public $userRepository;
    public $packageRepository;
    public $packageProducerRepository;

    public function __construct(UserRepository $userRepository, PackageRepository $packageRepository, PackageProducerRepository $packageProducerRepository){

        $this->userRepository = $userRepository;
        $this->packageRepository = $packageRepository;
        $this->packageProducerRepository = $packageProducerRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        $where['type'] = $this->userRepository::TYPE_PRODUCER;

        if (!empty($request->phone)) {
            $where['phone'] = ['phone', 'like', $request->email];
        }
        if (!empty($request->name)) {
            $where['name'] = ['name', 'like', $request->name];
        }
        if (!empty($request->references_id)) {
            $where['references_id'] = (int) $request->references_id;
        }
        if ($request->get('status', -1) > -1) {
            $where['status'] = (int)$request->status;
        }
        if ($request->get('verified', -1) > -1) {
            $where['verified'] = (int)$request->verified;
        }
        $users = $this->userRepository->paginate($where, ['id' => 'DESC']);
        $users->loadCount('sale');
        $users->load('package', 'references');
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách nhà cung cấp"]
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
        $packages = $this->packageRepository->pluck('name', 'id');
        return view('admin.content.producer.list')->with(compact('users', 'status', 'breadcrumbs', 'verified_status', 'packages'));
    }

    public function create(){
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.producer.index'), 'name' => "Danh sách nhà cung cấp"], ['name' => 'Tạo mới']
        ];
        return view('admin.content.producer.create')->with(compact( 'breadcrumbs'));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->only(['name', 'phone', 'email', 'references_id']);
        $user = $this->userRepository->first(['phone' => $data['phone']]);
        if ($user) {
            return back()->withErrors([
                'phone' => 'Tài khoản đã tồn tại'
            ])->withInput();
        }
        $data['type'] = $this->userRepository::TYPE_PRODUCER;
        $data['verified'] = $this->userRepository::VERIFIED;
        $data['status'] = $this->userRepository::STATUS_ACTIVE;
        $data['password'] = bcrypt($request->password);
        $this->userRepository->create($data);
        return redirect()->route('admin.producer.index');
    }

    public function edit($id)
    {
        $user = $this->userRepository->find($id);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.producer.index'), 'name' => "Danh sách nhà cung cấp"], ['name' => 'Sửa']
        ];
        $reference = $this->userRepository->find($user->references_id);
        return view('admin.content.producer.edit')->with(compact('user', 'breadcrumbs', 'reference'));
    }

    public function update($id, EditRequest $request)
    {
        $user = $this->userRepository->find($id);
        $data = $request->only(['name', 'email', 'references_id']);
        if(!empty($request->password)){
            $data['password'] = bcrypt($request->password);
        }
        $this->userRepository->edit($user, $data);
        return redirect()->route('admin.producer.index');
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
        $where['type'] = $this->userRepository::TYPE_PRODUCER;
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

    public function add_package(Request $request){
        $package = $this->packageRepository->find($request->package_id);
        $end_time = 0;
        switch ($package->time_type){
            case $this->packageRepository::TYPE_DAY:
                $end_time = strtotime(' + ' . $package->time . ' days');
                break;
            case $this->packageRepository::TYPE_MONTH:
                $end_time = strtotime(' + ' . $package->time . ' months');
                break;
            case $this->packageRepository::TYPE_YEAR:
                $end_time = strtotime(' + ' . $package->time . ' years');
                break;
        }
        $data = [
            'name' => $package->name,
            'product_number' => $package->product_number,
            'user_number' => $package->user_number,
            'producer_id' => $request->producer_id,
            'package_id' => $package->id,
            'price' => $package->price,
            'start_time' => time(),
            'end_time' => $end_time,
        ];
        $this->packageProducerRepository->create($data);
        return response([
            'result' => true,
        ]);
    }



    /*public function updatePassword($id, UpdatePasswordRequest $request)
    {
        $admin = $this->adminRepository->find($id);
        $data = [
            'password' => (int)$request->password
        ];
        $this->adminRepository->edit($admin, $data);
        return response(['result' => true]);
    }*/

    /*public function delete($id){
        $admin = $this->adminRepository->find($id);
        $this->adminRepository->delete($admin);
        return response(['result' => true]);
    }*/
}
