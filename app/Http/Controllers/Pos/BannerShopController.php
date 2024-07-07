<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;

use App\Http\Requests\Pos\BannerShop\CreateRequest;
use App\Http\Requests\Pos\BannerShop\UpdateRequest;
use App\Models\Banner;
use App\Models\BannerShop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BannerShopController extends Controller
{
    public function index(Request $request)
    {
        $shop = resolve('shop');
        $banners = BannerShop::orderBy('created_at','desc')->where('shop_id',$shop->id);
        if ($request->position){
            $banners = $banners->where('position',$request->position);
        }
        $banners = $banners->paginate(10);
        return response([
            'result' => true,
            'data' => $banners
        ]);
    }
    public function store(CreateRequest $request){
        $shop = resolve('shop');
        $banner_shop = new BannerShop();
        $banner_shop->shop_id = $shop->id;
        $banner_shop->name = $request->name;
        $banner_shop->position = $request->position;
        $banner_shop->start_time = ($request->start_time);
        $banner_shop->end_time = ($request->end_time);
        $image = $request->file('image');
        if ($image) {
            $banner_shop->image = Storage::putFileAs('banner-shop', $image, $image->getClientOriginalName());;
        }
        $banner_shop->status = 0;
        $banner_shop->save();
        return response([
            'result' => true,
            'message' => 'Tạo thành công',
            'data' => $banner_shop
        ]);
    }
    public function update($id,UpdateRequest $request){
        $shop = resolve('shop');
        $banner_shop = BannerShop::find($id);
        $banner_shop->shop_id = $shop->id;
        $banner_shop->name = $request->name;
        $banner_shop->position = $request->position;
        $banner_shop->start_time = ($request->start_time);
        $banner_shop->end_time = ($request->end_time);
        $image = $request->file('image');
        if ($image) {
            $banner_shop->image = Storage::putFileAs('banner-shop', $image, $image->getClientOriginalName());;
        }
        $banner_shop->save();
        return response([
            'result' => true,
            'message' => 'Sửa thành công',
            'data' => $banner_shop
        ]);
    }
    public function update_status($id,Request $request){
        $shop = resolve('shop');
        $banner_shop = BannerShop::find($id);
        $banner_shop->status = $request->status;
        $banner_shop->save();
        return response([
            'result' => true,
            'message' => 'Update trạng thái thành công',
            'data' => $banner_shop
        ]);
    }
    public function delete($id,Request $request){
        $shop = resolve('shop');
        $banner_shop = BannerShop::find($id);
        $banner_shop->delete();
        return response([
            'result' => true,
            'message' => 'Xóa thành công',
        ]);
    }
}
