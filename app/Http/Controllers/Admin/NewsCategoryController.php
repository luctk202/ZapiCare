<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\NewsCategory\NewsCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class NewsCategoryController extends Controller
{
    public $newsCategoryRepository;

    public function __construct(NewsCategoryRepository $newsCategoryRepository)
    {

        $this->newsCategoryRepository = $newsCategoryRepository;
    }

    public function index(Request $request)
    {
        $categories = $this->newsCategoryRepository->all();
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách danh mục"]
        ];
        $status = [
            -1 => 'Vui lòng chọn',
            $this->newsCategoryRepository::STATUS_ACTIVE => 'Hoạt động',
            $this->newsCategoryRepository::STATUS_BLOCK => 'Khóa',
        ];
        $data = [];
        $this->newsCategoryRepository->sort_parent($categories,0, '', $data);
        return view('admin.content.news_category.list')->with(compact('data', 'status', 'breadcrumbs'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.news-category.index'), 'name' => "Danh sách danh mục"], ['name' => 'Tạo mới']
        ];
        $opt = $this->newsCategoryRepository->tree();
        return view('admin.content.news_category.create')->with(compact( 'breadcrumbs','opt'));
    }

    public function store(Request $request)
    {
        $data = $request->only(['name','parent_id']);
        $data['status'] = $this->newsCategoryRepository::STATUS_ACTIVE;
        $image = $request->file('image');
        if ($image) {
            $data['image'] = Storage::putFileAs('news_category', $image, $image->getClientOriginalName());;
        }
        Cache::forget('news_categories');
        Cache::forget('news_categories_active');
        $this->newsCategoryRepository->create($data);
        return redirect()->route('admin.news-category.index');
    }

    public function edit($id)
    {
        $data = $this->newsCategoryRepository->find($id);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.news-category.index'), 'name' => "Danh sách danh mục"], ['name' => 'Sửa']
        ];
        $opt = $this->newsCategoryRepository->tree();
        return view('admin.content.news_category.edit')->with(compact('data', 'breadcrumbs', 'opt'));
    }

    public function update($id, Request $request)
    {
        $news = $this->newsCategoryRepository->find($id);
        $data = $request->only(['name','parent_id']);
        $image = $request->file('image');
        if ($image) {
            $data['image'] = Storage::putFileAs('news_category', $image, $image->getClientOriginalName());;
        }
        $this->newsCategoryRepository->edit($news, $data);
        Cache::forget('news_categories');
        Cache::forget('news_categories_active');
        return redirect()->route('admin.news-category.index');
    }

    public function update_status($id, Request $request)
    {
        $news = $this->newsCategoryRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->newsCategoryRepository->edit($news, $data);
        Cache::forget('news_categories_active');
        return response(['result' => true]);
    }

    public function delete($id){
        $news = $this->newsCategoryRepository->find($id);
        $this->newsCategoryRepository->delete($news);
        Cache::forget('news_categories');
        Cache::forget('news_categories_active');
        return response(['result' => true]);
    }
}
