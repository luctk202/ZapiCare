<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;

use App\Http\Requests\Pos\BannerShop\CreateRequest;
use App\Http\Requests\Pos\BannerShop\UpdateRequest;
use App\Models\Banner;
use App\Models\BannerShop;
use App\Models\ShopCustomer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $shop = resolve('shop');
        $customers = ShopCustomer::with(['user','shop'])->orderBy('created_at','desc')->where('shop_id',$shop->id);
        if ($request->search){
//            $customers = $customers->where('position',$request->position);
        }
        $customers = $customers->paginate($request->limit ?? 50);
        return response([
            'result' => true,
            'data' => $customers
        ]);
    }
    public function store(Request $request){
        $shop = resolve('shop');
        $shop_customer = new ShopCustomer();
        $shop_customer->shop_id = $shop->id;
        $shop_customer->name = $request->name;
        $shop_customer->email = $request->email;
        $shop_customer->phone = $request->phone;
        $shop_customer->address = $request->address;
        $shop_customer->province_id = $request->province_id;
        $shop_customer->province_name = $request->province_name;
        $shop_customer->district_name = $request->district_name;
        $shop_customer->district_id = $request->district_id;
        $shop_customer->ward_id = $request->ward_id;
        $shop_customer->ward_name = $request->ward_name;
        $shop_customer->birthday = $request->birthday;
        $image = $request->file('image');
        if ($image) {
            $shop_customer->image = Storage::putFileAs('shop-customer', $image, $image->getClientOriginalName());;
        }
        $shop_customer->save();
        return response([
            'result' => true,
            'message' => 'Tạo khách hàng thành công',
        ]);
    }
    public function update($id,Request $request){
        $shop = resolve('shop');
        $shop_customer = ShopCustomer::find($id);
        $shop_customer->shop_id = $shop->id;
        $shop_customer->name = $request->name;
        $shop_customer->email = $request->email;
        $shop_customer->phone = $request->phone;
        $shop_customer->address = $request->address;
        $shop_customer->province_id = $request->province_id;
        $shop_customer->province_name = $request->province_name;
        $shop_customer->district_name = $request->district_name;
        $shop_customer->district_id = $request->district_id;
        $shop_customer->ward_id = $request->ward_id;
        $shop_customer->ward_name = $request->ward_name;
        $shop_customer->birthday = $request->birthday;
        $image = $request->file('image');
        if ($image) {
            $shop_customer->image = Storage::putFileAs('shop-customer', $image, $image->getClientOriginalName());;
        }
        $shop_customer->save();
        return response([
            'result' => true,
            'message' => 'Sửa thành công',
        ]);
    }
    public function delete($id,Request $request){
        $shop = resolve('shop');
        $shop_customer = ShopCustomer::find($id);
        $shop_customer->delate();
        return response([
            'result' => true,
            'message' => 'Xóa thành công',
        ]);
    }
}
