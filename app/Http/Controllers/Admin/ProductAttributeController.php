<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductAttribute\CreateRequest;
use App\Http\Requests\Admin\ProductAttribute\EditRequest;
use App\Repositories\ProductAttribute\ProductAttributeRepository;
use App\Repositories\Shop\ShopRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProductAttributeController extends Controller
{
    public $productAttributeRepository;
    public $shopRepository;

    public function __construct(ProductAttributeRepository $productAttributeRepository, ShopRepository $shopRepository){
        $this->productAttributeRepository = $productAttributeRepository;
        $this->shopRepository = $shopRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        if (!empty($request->name)) {
            $where['name'] = ['name', 'like', $request->name];
        }
        $data = $this->productAttributeRepository->paginate($where, ['id' => 'DESC']);
        $data->load('shop');
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách thuộc tính"]
        ];
        return view('admin.content.product_attribute.list')->with(compact('data', 'breadcrumbs'));
    }

    public function create(Request $request)
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.product-attribute.index'), 'name' => "Danh sách thuộc tính"], ['name' => 'Tạo mới']
        ];
        $shop = null;
        if (!empty($request->old('shop_id'))) {
            $shop = $this->shopRepository->find($request->old('shop_id'));
        }
        return view('admin.content.product_attribute.create')->with(compact( 'breadcrumbs', 'shop'));
    }

    public function store(CreateRequest $request)
    {
//        $data = $request->only(['name', 'shop_id']);
        $data = $request->only(['name']);
        $data['slug'] = Str::slug($data['name']);
        Cache::forget('product_attributes');
        $this->productAttributeRepository->create($data);
        return redirect()->route('admin.product-attribute.index');
    }

    public function edit($id)
    {
        $data = $this->productAttributeRepository->find($id);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.product-attribute.index'), 'name' => "Danh sách thuộc tính"], ['name' => 'Sửa']
        ];
        return view('admin.content.product_attribute.edit')->with(compact('data', 'breadcrumbs'));
    }

    public function update($id, EditRequest $request)
    {
        $attr = $this->productAttributeRepository->find($id);
        $data = $request->only(['name']);
        $data['slug'] = Str::slug($data['name']);
        Cache::forget('product_attributes');
        $this->productAttributeRepository->edit($attr, $data);
        return redirect()->route('admin.product-attribute.index');
    }

    /*public function update_status($id, Request $request)
    {
        $user = $this->userRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->userRepository->edit($user, $data);
        return response(['result' => true]);
    }*/


    public function delete($id){
        $admin = $this->productAttributeRepository->find($id);
        $this->productAttributeRepository->delete($admin);
        Cache::forget('product_attributes');
        return response(['result' => true]);
    }
}
