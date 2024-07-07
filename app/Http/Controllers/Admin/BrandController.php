<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Brand\CreateRequest;
use App\Http\Requests\Admin\Brand\EditRequest;
use App\Repositories\Brand\BrandRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    public $brandRepository;

    public function __construct(BrandRepository $brandRepository){
        $this->brandRepository = $brandRepository;
    }


    public function index(Request $request)
    {
        $where = [];
        if (!empty($request->name)) {
            $where['name'] = ['name', 'like', $request->name];
        }
        $data = $this->brandRepository->paginate($where, ['id' => 'DESC']);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách"]
        ];
        return view('admin.content.brand.list')->with(compact('data', 'breadcrumbs'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.brand.index'), 'name' => "Danh sách"], ['name' => 'Tạo mới']
        ];
        return view('admin.content.brand.create')->with(compact( 'breadcrumbs'));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->only(['name']);
        $data['status'] = $this->brandRepository::STATUS_SHOW;
        $logo = $request->file('logo');
        if ($logo) {
            $data['logo'] = Storage::putFileAs('brand', $logo, $logo->getClientOriginalName());;
        }
        /*$image = $request->file('image');
        if ($image) {
            $data['image'] = Storage::putFileAs('brand', $image, $image->getClientOriginalName());;
        }*/
        Cache::forget('brand');
        $this->brandRepository->create($data);
        return redirect()->route('admin.brand.index');
    }

    public function edit($id)
    {
        $data = $this->brandRepository->find($id);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.brand.index'), 'name' => "Danh sách"], ['name' => 'Sửa']
        ];
        return view('admin.content.brand.edit')->with(compact('data', 'breadcrumbs'));
    }

    public function update($id, EditRequest $request)
    {
        $attr = $this->brandRepository->find($id);
        $data = $request->only(['name']);
        $logo = $request->file('logo');
        if ($logo) {
            $data['logo'] = Storage::putFileAs('brand', $logo, $logo->getClientOriginalName());;
        }
        /*$image = $request->file('image');
        if ($image) {
            $data['image'] = Storage::putFileAs('brand', $image, $image->getClientOriginalName());;
        }*/
        Cache::forget('brand');
        $this->brandRepository->edit($attr, $data);
        return redirect()->route('admin.brand.index');
    }

    public function update_status($id, Request $request)
    {
        $user = $this->brandRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->brandRepository->edit($user, $data);
        Cache::forget('brand');
        return response(['result' => true]);
    }

    public function update_hot($id, Request $request)
    {
        $user = $this->brandRepository->find($id);
        $data = [
            'hot' => (int)$request->hot
        ];
        $this->brandRepository->edit($user, $data);
        return response(['result' => true]);
    }


    public function delete($id){
        $admin = $this->brandRepository->find($id);
        $this->brandRepository->delete($admin);
        Cache::forget('brand');
        return response(['result' => true]);
    }
}
