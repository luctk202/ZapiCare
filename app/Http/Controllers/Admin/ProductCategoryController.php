<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductCategory\CreateRequest;
use App\Http\Requests\Admin\ProductCategory\EditRequest;
use App\Repositories\Filter\FilterRepository;
use App\Repositories\ProductCategory\ProductCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ProductCategoryController extends Controller
{
    public $categoryRepository;
    public $filterRepository;

    public function __construct(ProductCategoryRepository $categoryRepository, FilterRepository $filterRepository){

        $this->categoryRepository = $categoryRepository;
        $this->filterRepository = $filterRepository;
    }

    public function index(Request $request)
    {
        $categories = $this->categoryRepository->all();
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách danh mục"]
        ];
        $status = [
            -1 => 'Vui lòng chọn',
            $this->categoryRepository::STATUS_ACTIVE => 'Hoạt động',
            $this->categoryRepository::STATUS_BLOCK => 'Khóa',
        ];
        $data = [];
        $this->categoryRepository->sort_parent($categories,0, '', $data);
        return view('admin.content.product_category.list')->with(compact('data', 'status', 'breadcrumbs'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.product-category.index'), 'name' => "Danh sách danh mục"], ['name' => 'Tạo mới']
        ];
        $opt = $this->categoryRepository->tree();
        $filters = $this->filterRepository->all();
        return view('admin.content.product_category.create')->with(compact( 'breadcrumbs','opt', 'filters'));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->only(['name', 'parent_id', 'filter_id', 'short_description', 'long_description']);
        $data['status'] = $this->categoryRepository::STATUS_ACTIVE;
        $image = $request->file('image');
        if ($image) {
            $data['image'] = Storage::putFileAs('category', $image, $image->getClientOriginalName());;
        }
        Cache::forget('product_categories');
        $this->categoryRepository->create($data);

        return redirect()->route('admin.product-category.index');
    }

    public function edit($id)
    {
        $data = $this->categoryRepository->find($id);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.product-category.index'), 'name' => "Danh sách danh mục"], ['name' => 'Sửa']
        ];
        $opt = $this->categoryRepository->tree();
        $filters = $this->filterRepository->all();
        return view('admin.content.product_category.edit')->with(compact('data', 'breadcrumbs', 'opt', 'filters'));
    }

    public function update($id, EditRequest $request)
    {
        $admin = $this->categoryRepository->find($id);
        $data = $request->only(['name', 'parent_id', 'filter_id', 'short_description', 'long_description']);
        $image = $request->file('image');
        if ($image) {
            $data['image'] = Storage::putFileAs('category', $image, $image->getClientOriginalName());;
        }
        $this->categoryRepository->edit($admin, $data);
        Cache::forget('product_categories');
        return redirect()->route('admin.product-category.index');
    }

    public function update_status($id, Request $request)
    {
        $user = $this->categoryRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->categoryRepository->edit($user, $data);
        Cache::forget('product_categories');
        return response(['result' => true]);
    }

    public function update_hot($id, Request $request)
    {
        $user = $this->categoryRepository->find($id);
        $data = [
            'hot' => (int)$request->hot
        ];
        $this->categoryRepository->edit($user, $data);
        Cache::forget('product_categories');
        return response(['result' => true]);
    }

    public function update_home($id, Request $request)
    {
        $user = $this->categoryRepository->find($id);
        $data = [
            'home' => (int)$request->home
        ];
        $this->categoryRepository->edit($user, $data);
        Cache::forget('product_categories');
        return response(['result' => true]);
    }

    public function delete($id){
        $admin = $this->categoryRepository->find($id);
        $this->categoryRepository->delete($admin);
        Cache::forget('product_categories');
        return response(['result' => true]);
    }
}
