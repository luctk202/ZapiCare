<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;
use App\Repositories\Order;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public $posOrderRepository;
    public $posTransactionRepository;
    public $posShopInventoryRepository;
    public $posShopRevenueRepository;

    public function __construct(Order\OrderRepository $posOrderRepository)
    {
        $this->posOrderRepository = $posOrderRepository;
    }

    public function profit(Request $request)
    {
        $shop = resolve('shop');
        $where = [
            'shop_id' => $shop->id,
            'status_payment' => $this->posOrderRepository::STATUS_PAYMENT_PAID
        ];
        if (!empty($request->customer_id)) {
            $where['customer_id'] = (int)$request->customer_id;
        }
        if (!empty($request->start_time)) {
            $where['start_time'] = ['payment_time', '>=', (int)$request->start_time];
        }
        if (!empty($request->end_time)) {
            $where['end_time'] = ['payment_time', '<', (int)$request->end_time];
        }
        $sum_revenue = $this->posOrderRepository->sum($where, 'total');
        $sum_cost = $this->posOrderRepository->sum($where, 'total_cost');
        $sum_fee = $this->posOrderRepository->sum($where, 'total_fee');
        $sum_discount = $this->posOrderRepository->sum($where, 'total_discount');
        $sum_product = $this->posOrderRepository->sum($where, 'total_product');
        $sum_discount_product = $this->posOrderRepository->sum($where, 'total_discount_product');
        return response([
            'result' => true,
            'sum_revenue' => $sum_revenue,
            'sum_cost' => $sum_cost,
            'sum_fee' => $sum_fee,
            'sum_discount' => $sum_discount,
            'sum_product' => $sum_product,
            'sum_product_discount' => $sum_discount_product,
        ]);
    }

    public function stock()
    {

    }

    public function revenue_transaction(Request $request)
    {
        $shop = resolve('shop');
        $where_revenue_paid = [
            'shop_id' => $shop->id,
            'amount' => ['amount', '>', 0]
        ];
        $where_revenue_unpaid = [
            'shop_id' => $shop->id,
            'amount' => ['amount', '>', 0]
        ];
        $where_spent_paid = [
            'shop_id' => $shop->id,
            'amount' => ['amount', '<', 0]
        ];
        $where_spent_unpaid = [
            'shop_id' => $shop->id,
            'amount' => ['amount', '<', 0]
        ];
        if (!empty($request->id)) {
            $where_revenue_paid['id'] = (int)$request->id;
            $where_revenue_unpaid['id'] = (int)$request->id;
            $where_spent_paid['id'] = (int)$request->id;
            $where_spent_unpaid['id'] = (int)$request->id;
        }
        if (!empty($request->start_time)) {
            $where_revenue_paid['start_time'] = ['created_time', '>=', (int)$request->start_time];
            $where_revenue_unpaid['start_time'] = ['created_time', '>=', (int)$request->start_time];
            $where_spent_paid['start_time'] = ['created_time', '>=', (int)$request->start_time];
            $where_spent_unpaid['start_time'] = ['created_time', '>=', (int)$request->start_time];
        }
        if (!empty($request->end_time)) {
            $where_revenue_paid['end_time'] = ['created_time', '<', (int)$request->end_time];
            $where_revenue_unpaid['end_time'] = ['created_time', '<', (int)$request->end_time];
            $where_spent_paid['end_time'] = ['created_time', '<', (int)$request->end_time];
            $where_spent_unpaid['end_time'] = ['created_time', '<', (int)$request->end_time];
        }
        $total_revenue_paid = $this->posTransactionRepository->sum($where_revenue_paid, 'amount_payment');
        $total_revenue_unpaid = $this->posTransactionRepository->sum($where_revenue_unpaid, 'amount_debt');
        $total_spent_paid = $this->posTransactionRepository->sum($where_spent_paid, 'amount_payment');
        $total_spent_unpaid = $this->posTransactionRepository->sum($where_spent_unpaid, 'amount_debt');
        return response([
            'result' => true,
            'total_revenue_paid' => $total_revenue_paid,
            'total_revenue_unpaid' => $total_revenue_unpaid,
            'total_spent_paid' => -$total_spent_paid,
            'total_spent_unpaid' => -$total_spent_unpaid,
        ]);
    }

    public function status_order()
    {
        $shop = resolve('shop');
        $count_order_wait_delivery = \App\Models\Order::where('shop_id',$shop->id)->where('status',1)->count();
        $count_order_unpaid = \App\Models\Order::where('shop_id',$shop->id)->where('status',1)->where('status_payment',1)->count();
//            $this->posOrderRepository->count([
//            'shop_id' => $shop->id,
//            'status' => $this->posOrderRepository::STATUS_NEW,
//            'status_payment' => $this->posOrderRepository::STATUS_PAYMENT_UNPAID
//        ]);

        return response([
            'result' => true,
            'count_order_wait_delivery' => $count_order_wait_delivery,
            'count_order_unpaid' => $count_order_unpaid,
        ]);
    }

    public function inventory(Request $request)
    {
        $shop = resolve('shop');
        $data = $this->posShopInventoryRepository->first([
            'shop_id' => $shop->id
        ], ['created_time' => 'DESC']);
        return response([
            'result' => true,
            'data' => $data
        ]);
    }

    public function inventory_day(Request $request)
    {
        $shop = resolve('shop');
        $start_day = date('d-m-Y', (int)$request->start_time);
        $end_day = date('d-m-Y', (int)$request->end_time);
        $start = $this->posShopInventoryRepository->first([
            'shop_id' => $shop->id,
            'created_day' => $start_day
        ]);
        $end = $this->posShopInventoryRepository->first([
            'shop_id' => $shop->id,
            'created_day' => $end_day
        ]);
        $sum_export_day = $this->posShopInventoryRepository->sum([
            'shop_id' => $shop->id,
            'start_time' => ['created_time', '>=', (int)$request->start_time],
            'end_time' => ['created_time', '<=', ((int)$request->end_time)]
        ], 'sum_export_day');
        $sum_import_day = $this->posShopInventoryRepository->sum([
            'shop_id' => $shop->id,
            'start_time' => ['created_time', '>=', (int)$request->start_time],
            'end_time' => ['created_time', '<=', ((int)$request->end_time)]
        ], 'sum_import_day');
        return response([
            'result' => true,
            'sum_start_day' => $start->sum_start_day ?? 0,
            'sum_end_day' => $end->sum_end_day ?? 0,
            'sum_export_day' => $sum_export_day,
            'sum_import_day' => $sum_import_day,
        ]);
    }

    public function revenue_day(Request $request){
        $shop = resolve('shop');
        $data = $this->posShopRevenueRepository->get([
            'shop_id' => $shop->id,
            'start_time' => ['created_time', '>=', (int)$request->start_time],
            'end_time' => ['created_time', '<=', ((int)$request->end_time)]
        ], ['created_time' => 'ASC']);
        return response([
            'result' => true,
            'data' => $data
        ]);
    }
}
