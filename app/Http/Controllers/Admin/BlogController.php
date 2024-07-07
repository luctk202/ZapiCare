<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Blogs\CreateRequest;
use App\Http\Requests\Admin\Blogs\EditRequest;
use App\Repositories\Blog\BlogRepository;
use App\Repositories\BlogCategory\BlogCategoryRepository;
use App\Repositories\Category\CategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public $blogRepository;

    public function __construct(BlogRepository  $blogRepository)
    {
        $this->blogRepository = $blogRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        if (!empty($request->title)) {
            $where['title'] = ['title', 'like', $request->title];
        }
        if ($request->status == '0'){
            $where['status'] = 0;
        }
        $data = $this->blogRepository->paginate($where);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách"]
        ];
        $status = [-1 => 'Vui lòng chọn'] + $this->blogRepository->aryStatus;
        return view('admin.content.blogs.list')->with(compact( 'data', 'status', 'breadcrumbs'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.blogs.index'), 'name' => "Danh sách"], ['name' => 'Tạo mới']
        ];
        return view('admin.content.blogs.create')->with(compact('breadcrumbs'));
    }

    public function store(CreateRequest $request)
    {
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->get('content'),
            //'blog_category_id' => $request->blog_category_id,
        ];
        $image = $request->file('image');
        $data['slug'] = Str::slug($data['title'], '-');
        if ($image) {
            $data['image'] = Storage::putFileAs('blogs', $image, $image->getClientOriginalName());;
        }
        $data['status'] = $this->blogRepository::STATUS_ACTIVE;
        $this->blogRepository->create($data);
        return redirect()->route('admin.blogs.index');
    }

    public function edit($id)
    {
        $data = $this->blogRepository->find($id);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.blogs.index'), 'name' => "Danh sách"], ['name' => 'Sửa']
        ];
        return view('admin.content.blogs.edit')->with(compact('data', 'breadcrumbs'));
    }

    public function update($id, EditRequest $request)
    {
        $blog = $this->blogRepository->find($id);
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->get('content'),
            //'blog_category_id' => $request->blog_category_id,
        ];
        $image = $request->file('image');
        $data['slug'] = Str::slug($data['title'], '-');
        if ($image) {
            $data['image'] = Storage::putFileAs('blogs', $image, $image->getClientOriginalName());;
        }
        $this->blogRepository->edit($blog, $data);
        return redirect()->route('admin.blogs.index');
    }

    public function update_status($id, Request $request)
    {
        $user = $this->blogRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->blogRepository->edit($user, $data);
        return response(['result' => true]);
    }

    public function delete($id)
    {
        $blog = $this->blogRepository->find($id);
        $this->blogRepository->delete($blog);
        return response(['result' => true]);
    }

    public function upload(Request $request){
        $image = $request->file('file');
        $imgurl = '';
        if ($image) {
            $imgpath = Storage::putFileAs('editors', $image, $image->getClientOriginalName());
            $imgurl = Storage::disk('public')->url($imgpath);
        }
        return response()->json(['location'=> $imgurl]);
    }
}
