<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BlogCategory\CreateRequest;
use App\Http\Requests\Admin\BlogCategory\EditRequest;
use App\Repositories\BlogCategory\BlogCategoryRepository;
use App\Repositories\Category\CategoryRepository;
use Illuminate\Http\Request;

class BlogCategoryController extends Controller
{
    public $categoryRepository;
    public $blogCategoryRepository;

    public function __construct(CategoryRepository $categoryRepository, BlogCategoryRepository $blogCategoryRepository)
    {

        $this->categoryRepository = $categoryRepository;
        $this->blogCategoryRepository = $blogCategoryRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        if (!empty($request->name)) {
            $where['name'] = ['name', 'like', $request->name];
        }
        if (\request('category_id', -1) > -1) {
            $where['category_id'] = (int)$request->category_id;
        }
        $data = $this->blogCategoryRepository->get($where);
        if($data){
            $data->load('category');
        }
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách"]
        ];
        $status = [
            -1 => 'Vui lòng chọn',
            $this->blogCategoryRepository::STATUS_ACTIVE => 'Hoạt động',
            $this->blogCategoryRepository::STATUS_BLOCK => 'Khóa',
        ];
        $categories = $this->categoryRepository->tree();
        return view('admin.content.blog_category.list')->with(compact('categories', 'data', 'status', 'breadcrumbs'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.blog-category.index'), 'name' => "Danh sách"], ['name' => 'Tạo mới']
        ];
        $opt = $this->categoryRepository->tree();
        return view('admin.content.blog_category.create')->with(compact('breadcrumbs', 'opt'));
    }

    public function store(CreateRequest $request)
    {
        $data = [
            'name' => $request->name,
            'category_id' => (int)$request->category_id,
            'position' => (int)$request->position,
        ];//$request->only(['name', 'category_id', 'news_type', 'category_type', 'position']);

        $data['status'] = $this->categoryRepository::STATUS_ACTIVE;
        $this->blogCategoryRepository->create($data);
        return redirect()->route('admin.blog-category.index');
    }

    public function edit($id)
    {
        $data = $this->blogCategoryRepository->find($id);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.blog-category.index'), 'name' => "Danh sách"], ['name' => 'Sửa']
        ];
        $opt = $this->categoryRepository->tree();
        return view('admin.content.blog_category.edit')->with(compact('data', 'breadcrumbs', 'opt'));
    }

    public function update($id, EditRequest $request)
    {
        $category = $this->blogCategoryRepository->find($id);
        $data = [
            'name' => $request->name,
            'category_id' => (int)$request->category_id,
            'position' => (int)$request->position,
        ];
        $this->blogCategoryRepository->edit($category, $data);
        return redirect()->route('admin.blog-category.index');
    }

    public function update_status($id, Request $request)
    {
        $user = $this->blogCategoryRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->blogCategoryRepository->edit($user, $data);
        return response(['result' => true]);
    }

    public function delete($id)
    {
        $category = $this->blogCategoryRepository->find($id);
        $this->blogCategoryRepository->delete($category);
        return response(['result' => true]);
    }
}
