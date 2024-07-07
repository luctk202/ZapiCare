<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function index($id)
    {
        $reviews = Review::where('product_id', $id)->where('parent_id',0)->where('status', 1)->orderBy('updated_at', 'desc')->paginate(10);
        return response()->json([
            'result'=>true,
            'data'=>$reviews,
        ]);
    }

    public function submit(Request $request)
    {
        DB::beginTransaction();
        try {
            foreach ($request->reviews as $review) {
                $product = Product::find($review['product_id']);
                $user = User::find(auth()->user()->id);
                /*
                 @foreach ($detailedProduct->orderDetails as $key => $orderDetail)
                                                    @if($orderDetail->order != null && $orderDetail->order->user_id == Auth::user()->id && $orderDetail->delivery_status == 'delivered' && \App\Models\Review::where('user_id', Auth::user()->id)->where('product_id', $detailedProduct->id)->first() == null)
                                                        @php
                                                            $commentable = true;
                                                        @endphp
                                                    @endif
                                                @endforeach
                */
                $reviewable = false;
                foreach ($product->orderDetails as $key => $orderDetail) {
                    if ($orderDetail->order != null && $orderDetail->order->user_id == auth()->user()->id && $orderDetail->status == 5 && \App\Models\Review::where('user_id', auth()->user()->id)->where('product_id', $product->id)->first() == null) {
                        $reviewable = true;
                    }
                }
                if (!$reviewable) {
                    return response()->json([
                        'result' => false,
                        'message' => 'Bạn không thể đánh giá sản phẩm này'
                    ]);
                }
                $re = new \App\Models\Review;
                $re->product_id = $review['product_id'];
                $re->user_id = auth()->user()->id;
                $re->name = auth()->user()->name;
                $re->rating = $review['rating'];
                $re->shop_id = $product->shop_id;
                if (array_key_exists('images',$review)) {
                    $data_image = [];
                    foreach ($review['images'] as $image) {
                        $data_image[] = Storage::putFileAs('review', $image, $image->getClientOriginalName());
                    }
                    $re->images = $data_image;
                }
                $re->parent_id = 0;
                $re->comment = $review['comment'];
                $re->status = 0;
                $re->viewed = 0;
                $re->save();
                $count = Review::where('product_id', $product->id)->where('status', 1)->count();
                if ($count > 0) {
                    $product->rating = Review::where('product_id', $product->id)->where('status', 1)->sum('rating') / $count;
                } else {
                    $product->rating = 0;
                }
                $product->save();

            }
            $order = Order::where('id',$request->order_id)->first();
            $order->check_review = 1;
            $order->save();
            DB::commit();
            return response()->json([
                'result' => true,
                'message' => translate('Review  Submitted')
            ]);
        }
        catch (\Exception $e){
            DB::rollBack();
        }
    }
}
