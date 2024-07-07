<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Review;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
{
    public function index(Request $request){
        $shop = resolve('shop');
        $reviews = Review::with(['product','childrenReviews'])->where('shop_id',$shop->id);
        if ($request->product_id){
            $reviews = $reviews->where('product_id',$request->product_id);
        }
        if ($request->name){
            $reviews = $reviews->where('name','like','%'.$request->name.'%');
        }
        $reviews =$reviews->paginate($request->limit ?? 50);
        return response()->json([
            'result'=>true,
            'data'=>$reviews,
        ]);
    }
    public function show(Request $request){
        $shop = resolve('shop');
        $review = Review::with(['product','childrenReviews'])->where('shop_id',$shop->id);
        $review =$review->first();
        return response()->json([
            'result'=>true,
            'data'=>$review,
        ]);
    }
    public function reply($id,Request $request){
        $shop = resolve('shop');
        $review =  Review::find($id);
        if ($review){
            $add = new Review;
            $add->parent_id = $review->id;
            $add->product_id = $review->product_id;
            $add->user_id = $shop->user->id;
            $add->name = $shop->name;
            $add->comment = $request->comment;
            $add->status = 1;
            $add->viewed = '0';
            $add->save();
        }else{
            return response()->json([
                'result'=>true,
                'message'=>'Không tìm thấy đánh giá',
            ]);
        }
    }

}
