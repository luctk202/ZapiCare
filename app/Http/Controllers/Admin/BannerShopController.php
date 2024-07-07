<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BannerShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerShopController extends Controller
{
    public function index(Request $request)
    {
        $data = BannerShop::query()->orderBy('created_at','desc');
        if($request->status != null){
            $data = $data->where('status',$request->status);
        }
        if ($request->position){
            $data = $data->where('position',$request->position);
        }
        $data = $data->paginate(50);
        $positions = [
            "1" => 'Banner Top',
            "2" => 'Banner Between',
            "3" => 'Banner Bottom',
        ];
        $status = [
            "1" => 'Hiển thị',
            "0" => 'Ẩn',
        ];
        return view('admin.content.banner-shop.list')->with(compact('data','positions','status'));
    }

    public function create()
    {
        $positions = [
            "1" => 'Banner Top',
            "2" => 'Banner Between',
            "3" => 'Banner Bottom',
        ];
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.banner-shop.index'), 'name' => "Danh sách"], ['name' => 'Tạo mới']
        ];
        return view('admin.content.banner-shop.create')->with(compact('breadcrumbs', 'positions'));
    }

    public function store(Request $request)
    {
        $shop = new BannerShop();
        $aryTime = explode('to', $request->time);
        $data = [
            'shop_id' => (int)$request->shop_id,
            'position' => (int)$request->position,
            'name' => $request->name,
            'start_time' => !empty($aryTime[0]) ? strtotime($aryTime[0]) : 0,
            'end_time' => !empty($aryTime[1]) ? (strtotime($aryTime[1]) + 86399) : (strtotime($aryTime[0]) + 86399),
            'status' => 1
        ];
        $image = $request->file('image');
        if ($image) {
            $data['image'] = Storage::putFileAs('banner-shop', $image, $image->getClientOriginalName());;
        }
        $shop->create($data);
        return redirect()->route('admin.banner-shop.index');
    }

    public function edit($id)
    {
        $positions = [
            "1" => 'Banner Top',
            "2" => 'Banner Between',
            "3" => 'Banner Bottom',
        ];
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.banner-shop.index'), 'name' => "Danh sách"], ['name' => 'Sửa']
        ];
        $banner = BannerShop::find($id);
        return view('admin.content.banner-shop.edit')->with(compact('breadcrumbs', 'positions', 'banner'));
    }

    public function update($id, Request $request)
    {
        $banner = BannerShop::find($id);
        $aryTime = explode('to', $request->time);
        $data = [
            'category_id' => (int)$request->category_id,
            'position' => (int)$request->position,
            'name' => $request->name,
            'link' => $request->link,
            'start_time' => !empty($aryTime[0]) ? strtotime($aryTime[0]) : 0,
            'end_time' => !empty($aryTime[1]) ? (strtotime($aryTime[1]) + 86399) : (strtotime($aryTime[0]) + 86399),
            //'status' => $this->bannerRepository::STATUS_ACTIVE
        ];
        $image = $request->file('image');
        if ($image) {
            $data['image'] = Storage::putFileAs('banner-shop', $image, $image->getClientOriginalName());;
        }
        $banner->edit($banner, $data);
        return redirect()->route('admin.banner-shop.index');
    }

    public function show()
    {

    }

    public function update_status($id, Request $request)
    {
        $banner = BannerShop::find($id);
        $banner->status = $request->status;
        $banner->save();
        return response()->json(
            ['result' => true]
        );
    }

    public function delete($id)
    {
        $banner = BannerShop::find($id);
        $banner->delete($banner);
        return response(['result' => true]);
    }
}
