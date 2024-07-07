<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Repositories\Coupon\CouponRepository;
use App\Repositories\ProductCategory\ProductCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    protected $couponRepository;
    protected $productCategoryRepository;

    public function __construct(CouponRepository $couponRepository, ProductCategoryRepository $productCategoryRepository)
    {
        $this->couponRepository = $couponRepository;
        $this->productCategoryRepository = $productCategoryRepository;
    }

    public function index(Request $request){
        $where  = [
            'status' => $this->couponRepository::STATUS_ACTIVE,
            'start_time' => ['start_time', '<=', time()],
            'end_time' => ['end_time', '>=', time()],
        ];
        if(!empty($request->source_id)){
            $where['source_id'] = (int)$request->source_id;
        }
        if(!empty($request->shop_id)){
            $where['shop_id'] = ['shop_id', 'whereIn', [0, (int)$request->shop_id]];
        }
        if(!empty($request->product_id)){
            $where['product_id'] = ['product_id', 'whereIn', [0, (int)$request->product_id]];
        }
        if(!empty($request->category_id)){
            $ids = array_merge($this->productCategoryRepository->get_child_id($request->category_id), [(int)$request->category_id]);
            $ids[] = 0;
            $where['category_id'] = ['category_id', 'whereIn', $ids];
        }
        $coupons = $this->couponRepository->get($where);
        return response([
            'result' => true,
            'data' => $coupons
        ]);
    }

    public function show($code){
        $where  = [
            'code' =>  $code,
            //'status' => $this->couponRepository::STATUS_ACTIVE,
            //'start_time' => ['start_time', '<=', time()],
            //'end_time' => ['end_time', '>=', time()],
        ];
        $coupon = $this->couponRepository->first($where);
        return response([
            'result' => true,
            'data' => $coupon
        ]);
    }
    public function apply(Request $request){
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'shop_id' => 'required',
        ]);
        if ($validator->errors()->count()>0) {
            return response()->json(['errors' =>error_processor($validator)], 203);
        }
        $coupon = Coupon::where('shop_id',$request->shop_id)->where('code',$request->code)->first();
        if ($coupon){
            return response()->json([
                'result'=>true,
                'message'=>'Áp mã thành công',
                'data'=>$coupon
            ]);
        }else{
            return response()->json([
                'result'=>false,
                'message'=>'Không tìm thấy mã khuyễn mãi'
            ]);
        }
    }
}
