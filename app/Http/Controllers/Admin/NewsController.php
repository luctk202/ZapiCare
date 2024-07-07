<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\News\CreateRequest;
use App\Http\Requests\Admin\News\EditRequest;
use App\Models\Logs;
use App\Repositories\News\NewsRepository;
use App\Repositories\NewsCategory\NewsCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{
    public $newsRepository;
    public $newsCategoryRepository;

    public function __construct(NewsRepository $newsRepository, NewsCategoryRepository $newsCategoryRepository)
    {
        $this->newsRepository = $newsRepository;
        $this->newsCategoryRepository = $newsCategoryRepository;
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
        $data = $this->newsRepository->paginate($where, ['created_at' => 'DESC']);
        if($data){
            $data->load('category');
        }
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách"]
        ];
        $status = $this->newsRepository->aryStatus;
        return view('admin.content.news.list')->with(compact( 'data', 'status', 'breadcrumbs'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.news.index'), 'name' => "Danh sách"], ['name' => 'Tạo mới']
        ];
        $categories = $this->newsCategoryRepository->tree();
        $type = $this->newsRepository->aryType;
        return view('admin.content.news.create')->with(compact('breadcrumbs', 'categories', 'type'));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->only(['category_id', 'title', 'author', 'description', 'content', 'count_view', 'count_share', 'url_share', 'type', 'product_id']);
        $images = $request->file('image');
        if ($images) {
            $data_image = [];
            foreach ($images as $image) {
                $data_image[] = Storage::putFileAs('news', $image, Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION));
            }
            $data['image'] = $data_image;
        }
        $video = $request->file('video');
        if ($video) {
            $data['video'] = Storage::disk('r2')->putFileAs('news', $video, Str::slug(pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . pathinfo($video->getClientOriginalName(), PATHINFO_EXTENSION));
        }
        $data['status'] = $this->newsRepository::STATUS_ACTIVE;
        DB::transaction(function () use ($data){
            $news = $this->newsRepository->create($data);
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'create_news',
                'item_id' => $news->id,
                'data' => $data
            ]);
        });
        return redirect()->route('admin.news.index');
    }

    public function edit($id)
    {
        $data = $this->newsRepository->find($id);
        $data->load('product');
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.news.index'), 'name' => "Danh sách"], ['name' => 'Sửa']
        ];
        $categories = $this->newsCategoryRepository->tree();
        $type = $this->newsRepository->aryType;
        return view('admin.content.news.edit')->with(compact('data', 'breadcrumbs', 'categories', 'type'));
    }

    public function update($id, EditRequest $request)
    {
        $news = $this->newsRepository->find($id);
        $data = $request->only(['category_id', 'title', 'author', 'description', 'content', 'count_view', 'count_share', 'url_share', 'type', 'product_id']);
        $images = $request->file('image');
        //$data['slug'] = Str::slug($data['title'], '-');
        if ($images) {
            $data_image = [];
            foreach ($images as $image) {
                $data_image[] = Storage::putFileAs('news', $image, Str::slug(pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . pathinfo($image->getClientOriginalName(), PATHINFO_EXTENSION));
            }
            $data['image'] = $data_image;
        }
        $video = $request->file('video');
        if ($video) {
            $data['video'] = Storage::disk('r2')->putFileAs('news', $video, Str::slug(pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . pathinfo($video->getClientOriginalName(), PATHINFO_EXTENSION));
        }
        DB::transaction(function () use ($news, $data){
            $news = $this->newsRepository->edit($news, $data);
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'edit_news',
                'item_id' => $news->id,
                'data' => $data
            ]);
        });
        return redirect()->route('admin.news.index');
    }

    public function update_status($id, Request $request)
    {
        $news = $this->newsRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->newsRepository->edit($news, $data);
        return response(['result' => true]);
    }

    public function delete($id)
    {
        $blog = $this->newsRepository->find($id);
        $this->newsRepository->delete($blog);
        return response(['result' => true]);
    }

   /* public function upload(Request $request){
        $image = $request->file('file');
        $imgurl = '';
        if ($image) {
            $imgpath = Storage::putFileAs('editors', $image, $image->getClientOriginalName());
            $imgurl = Storage::disk('public')->url($imgpath);
        }
        return response()->json(['location'=> $imgurl]);
    }*/
}
