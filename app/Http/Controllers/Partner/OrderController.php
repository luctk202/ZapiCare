<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request){
        $partner = resolve('partner');
        $orders = Order::with(['details'])->where('partner_id',$partner->id)->paginate($request->limit ?? 50);
        return response()->json([
            'result'=>true,
            'data'=>$orders,
        ]);
    }
    public function show(Request $request,$id){
        $shop = resolve('shop');
        $orders = Order::with(['details'])->find($id);
        return response()->json([
            'result'=>true,
            'data'=>$orders,
        ]);
    }
    public function update_status($id, Request $request)
    {
        $status = $request->status;
        $shop = resolve('shop');
        $order = Order::where('shop_id',$shop->id)->where('id',$id)->first();
        if (!$order) {
            return response([
                'result' => false,
                'message' => 'Không tìm thấy thông tin đơn hàng',
            ]);
        }
//        if ($order->status_payment == 2) {
//            return response([
//                'result' => false,
//                'message' => 'Đơn hàng đã thanh toán',
//            ]);
//        }
//        if ($order->status == 3) {
//            return response([
//                'result' => false,
//                'message' => 'Đơn hàng đã bị hủy',
//            ]);
//        }
        $order = DB::transaction(function () use ($order,$status) {
            $order->status = $status;
            $order->save();
            foreach ($order->details as $key => $orderDetail) {
                $orderDetail->status = $status;
                $orderDetail->save();
            }

            return $order;
        });
        return response([
            'result' => true,
            'data' => $order
        ]);
    }
    public function update_status_payment($id,Request $request){
        $shop = resolve('shop');
        $order = Order::where('shop_id',$shop->id)->where('id',$id)->first();
        foreach ($order->details as $key => $orderDetail) {
            $orderDetail->status_payment = $request->status;
            $orderDetail->save();
        }

        $status = 2;
        foreach ($order->details as $key => $orderDetail) {
            if ($orderDetail->status_payment != 2) {
                $status = 1;
            }
        }
        $order->status_payment = $status;
        $order->save();

    }

}
