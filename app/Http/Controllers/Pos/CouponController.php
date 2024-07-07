<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;

use App\Http\Requests\Pos\Coupon\CreateRequest;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $shop = resolve('shop');
        $coupons = Coupon::orderBy('created_at','desc')->where('shop_id',$shop->id);
        if ($request->code){
            $coupons = $coupons->where('code',$request->code);
        }
        $coupons = $coupons->paginate($request->limit ?? 50);
        return response([
            'result' => true,
            'data' => $coupons
        ]);
    }
    public function store(CreateRequest $request){
        $shop = resolve('shop');
        $coupon = new Coupon();
        $coupon->shop_id = $shop->id;
        $coupon->code = $request->code; // mã giảm giá
        $coupon->discount_value = $request->discount_value; // giá trị giảm
        $coupon->discount_type = $request->discount_type; // loại giảm
        $coupon->num = $request->num; // số lượng giảm
        $coupon->max_per_person = $request->max_per_person;
        $coupon->discount_max_value = $request->discount_max_value;
        $coupon->min_total_order = $request->min_total_order;
        $coupon->concurrency = $request->concurrency;
        $coupon->product_id = $request->product_id;
        $coupon->description = $request->description;
        $coupon->start_time = $request->start_time;
        $coupon->end_time = $request->end_time;
        $coupon->status = 1;
        $coupon->save();
        return response([
            'result' => true,
            'message' => 'Tạo mã giảm giá thành công',
            'data' => $coupon
        ]);
    }
    public function update($id,Request $request){
        $shop = resolve('shop');
        $coupon = Coupon::find($id);
        $coupon->shop_id = $shop->id;
        $coupon->code = $request->code; // mã giảm giá
        $coupon->discount_value = $request->discount_value; // giá trị giảm
        $coupon->discount_type = $request->discount_type; // loại giảm
        $coupon->num = $request->num; // số lượng giảm
        $coupon->max_per_person = $request->max_per_person;
        $coupon->discount_max_value = $request->discount_max_value;
        $coupon->min_total_order = $request->min_total_order;
        $coupon->concurrency = $request->concurrency;
        $coupon->product_id = $request->product_id;
        $coupon->description = $request->description;
        $coupon->start_time = $request->start_time;
        $coupon->end_time = $request->end_time;
        $coupon->save();
        return response([
            'result' => true,
            'message' => 'Sửa mã giảm giá thành công',
            'data' =>  $coupon,
        ]);
    }
    public function update_status($id,Request $request){
        $shop = resolve('shop');
        $coupon = Coupon::find($id);
        $coupon->status = $request->status;
        $coupon->save();
        return response([
            'result' => true,
            'message' => 'Update trạng thái thành công',
            'data' => $coupon
        ]);
    }
    public function delete($id,Request $request){
        $shop = resolve('shop');
        $coupon = Coupon::find($id);
        $coupon->delete();
        return response([
            'result' => true,
            'message' => 'Update trạng thái thành công',
            'data' => $coupon
        ]);
    }
}
