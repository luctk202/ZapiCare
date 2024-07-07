<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Banner\CreateRequest;
use App\Http\Requests\Admin\Banner\EditRequest;
use App\Repositories\Banner\BannerRepository;
use App\Repositories\ProductCategory\ProductCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    private $bannerRepository;
    private $categoryRepository;

    public function __construct(BannerRepository $bannerRepository, ProductCategoryRepository $categoryRepository)
    {
        $this->bannerRepository = $bannerRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        /*if (!empty($request->category_id)) {
            $where['category_id'] = (int)$request->category_id;
        }*/
        if (!empty($request->position)) {
            $where['position'] = (int)$request->position;
        }
        if ($request->get('status', -1) > -1) {
            $where['status'] = (int)$request->status;
        }
        $data = $this->bannerRepository->paginate($where, ['id' => 'DESC']);
        if ($data) {
            $data->load('category');
        }
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách"]
        ];
        //$categories = $this->categoryRepository->tree();
        $positions = $this->bannerRepository->aryPosition;
        $status = $this->bannerRepository->aryStatus;
        return view('admin.content.banner.list')->with(compact('data', 'status', 'breadcrumbs', 'positions'));
    }

    public function create()
    {
        //$categories = $this->categoryRepository->tree();
        $positions = $this->bannerRepository->aryPosition;
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.banner.index'), 'name' => "Danh sách"], ['name' => 'Tạo mới']
        ];
//        $categories = $this->categoryRepository->tree();
        return view('admin.content.banner.create')->with(compact('breadcrumbs', 'positions'));
    }

    public function store(CreateRequest $request)
    {
        $aryTime = explode('to', $request->time);
        $data = [
//            'category_id' => (int)$request->category_id,
            'position' => (int)$request->position,
            'name' => $request->name,
            'link' => $request->link,
            'start_time' => !empty($aryTime[0]) ? strtotime($aryTime[0]) : 0,
            'end_time' => !empty($aryTime[1]) ? (strtotime($aryTime[1]) + 86399) : (strtotime($aryTime[0]) + 86399),
            'status' => $this->bannerRepository::STATUS_ACTIVE
        ];
        $image = $request->file('image');
        if ($image) {
            $data['image'] = Storage::putFileAs('banner', $image, $image->getClientOriginalName());;
        }
        $this->bannerRepository->create($data);
        return redirect()->route('admin.banner.index');
    }

    public function edit($id)
    {
        //$categories = $this->categoryRepository->tree();
        $positions = $this->bannerRepository->aryPosition;
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.banner.index'), 'name' => "Danh sách"], ['name' => 'Sửa']
        ];
        $banner = $this->bannerRepository->find($id);
//        $categories = $this->categoryRepository->tree();
        return view('admin.content.banner.edit')->with(compact('breadcrumbs', 'positions', 'banner'));
    }

    public function update($id, EditRequest $request)
    {
        $aryTime = explode('to', $request->time);
        $data = [
//            'category_id' => (int)$request->category_id,
            'position' => (int)$request->position,
            'name' => $request->name,
            'link' => $request->link,
            'start_time' => !empty($aryTime[0]) ? strtotime($aryTime[0]) : 0,
            'end_time' => !empty($aryTime[1]) ? (strtotime($aryTime[1]) + 86399) : (strtotime($aryTime[0]) + 86399),
            //'status' => $this->bannerRepository::STATUS_ACTIVE
        ];
        $image = $request->file('image');
        if ($image) {
            $data['image'] = Storage::putFileAs('banner', $image, $image->getClientOriginalName());;
        }
        $banner = $this->bannerRepository->find($id);
        $this->bannerRepository->edit($banner, $data);
        return redirect()->route('admin.banner.index');
    }

    public function show()
    {

    }

    public function update_status($id, Request $request)
    {
        $user = $this->bannerRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->bannerRepository->edit($user, $data);
        return response(['result' => true]);
    }

    public function delete($id)
    {
        $package = $this->bannerRepository->find($id);
        $this->bannerRepository->delete($package);
        return response(['result' => true]);
    }
}
