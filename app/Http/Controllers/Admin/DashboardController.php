<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\District\DistrictRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\OrderDetail\OrderDetailRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\Province\ProvinceRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public $orderRepository;
    public $orderDetailRepository;
    public $productRepository;
    public $userRepository;
    public $provinceRepository;
    public $districtRepository;

    public function __construct(OrderRepository $orderRepository,
                                OrderDetailRepository $orderDetailRepository,
                                ProductRepository $productRepository,
                                ProvinceRepository $provinceRepository,
                                DistrictRepository $districtRepository,
                                UserRepository $userRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
        $this->provinceRepository = $provinceRepository;
        $this->districtRepository = $districtRepository;
    }

    public function index()
    {
        /*$count_order_retail_new = $this->orderRepository->count(['source' => $this->orderRepository::SOURCE_RETAIL, 'status' => $this->orderRepository::STATUS_NEW]);
        $count_order_wholesale_new = $this->orderRepository->count(['source' => $this->orderRepository::SOURCE_WHOLESALE, 'status' => $this->orderRepository::STATUS_NEW]);
        $sum_order_retail = $this->orderRepository->sum(['source' => $this->orderRepository::SOURCE_RETAIL, 'status' => $this->orderRepository::STATUS_DONE, 'status_payment' => $this->orderRepository::STATUS_PAYMENT_PAID], 'total');
        $sum_order_wholesale = $this->orderRepository->sum(['source' => $this->orderRepository::SOURCE_WHOLESALE, 'status' => $this->orderRepository::STATUS_DONE, 'status_payment' => $this->orderRepository::STATUS_PAYMENT_PAID], 'total');*/

        return view('admin/content/dashboard/dashboard-ecommerce');
    }

    public function sum(Request $request){
        $count_order_retail_new = $this->orderRepository->count(['status' => $this->orderRepository::STATUS_NEW, 'start_date' => ['created_time', '>=', strtotime(str_replace("/", "-", $request->start_date))], 'end_date' => ['created_time', '<=', (strtotime(str_replace("/", "-", $request->end_date)) + 86399)]]);
        $sum_order_retail = $this->orderRepository->sum(['status' => $this->orderRepository::STATUS_DONE, 'status_payment' => $this->orderRepository::STATUS_PAYMENT_PAID, 'start_date' => ['export_time', '>=', strtotime(str_replace("/", "-", $request->start_date))], 'end_date' => ['export_time', '<=', (strtotime(str_replace("/", "-", $request->end_date)) + 86399)]], 'total');
        return response([
            'result' => true,
            'count_order_retail_new' => $count_order_retail_new,
            'sum_order_retail' => $sum_order_retail,
        ]);
    }

    public function revenueByDay(Request $request)
    {
        $where = [
            //'user_id' => auth()->id(),
            'status' => $this->orderRepository::STATUS_DONE
        ];
        if (!empty($request->start_date)) {
            $where['start_date'] = ['export_time', '>=', strtotime(str_replace("/", "-", $request->start_date))];
        }
        if (!empty($request->end_date)) {
            $where['end_date'] = ['export_time', '<=', (strtotime(str_replace("/", "-", $request->end_date)) + 86399)];
        }
        $retail = $this->orderRepository->get($where, [], ['date'], [DB::raw('DATE_FORMAT(FROM_UNIXTIME(export_time), "%d/%m") as date'), DB::raw('SUM(total) as total')])->pluck('total', 'date');
        $categories = [];
        if (!empty($request->start_date) && !empty($request->end_date)) {
            $start = Carbon::createFromFormat('d-m-Y', $request->start_date);
            $end = Carbon::createFromFormat('d-m-Y', $request->end_date);
            $dateRange = new Collection();
            while ($start->lte($end)) {
                $dateRange->push($start->format('d/m'));
                $start->addDay();
            }
            $categories = $dateRange->toArray();
        }
        $data_retail = [];
        if ($categories) {
            foreach ($categories as $category) {
                $data_retail[] = $retail[$category] ?? 0;
            }
        }

        return response([
            'result' => true,
            'categories' => $categories,
            'retail' => $data_retail
        ]);
    }

    public function top()
    {
        $where = [
            'status' => $this->orderRepository::STATUS_DONE
        ];
        if (!empty($request->start_date)) {
            $where['start_date'] = ['export_time', '>=', strtotime(str_replace("/", "-", $request->start_date))];
        }
        if (!empty($request->end_date)) {
            $where['end_date'] = ['export_time', '<=', (strtotime(str_replace("/", "-", $request->end_date)) + 86399)];
        }
        $order_details = $this->orderDetailRepository->get($where, ['total' => 'DESC'], ['product_id'], ['product_id', DB::raw('SUM(num) as total')], 10)->pluck('total', 'product_id')->toArray();
        $product = [];
        if ($order_details) {
            $product = $this->productRepository->get([['id', 'whereIn', array_keys($order_details)]]);
            foreach ($product as $key => $value) {
                $value->export_total = $order_details[$value->id] ?? 0;
            }
            if($product){
                $product = $product->sortByDesc('export_total')->values()->all();
            }

        }
        $order_totals = $this->orderRepository->get($where, ['total' => 'DESC'], ['user_id'], ['user_id', DB::raw('SUM(total) as total')], $request->limit ?? 10)->pluck('total', 'user_id')->toArray();
        $user_total = [];
        if ($order_totals) {
            $user_total = $this->userRepository->get([['id', 'whereIn', array_keys($order_totals)]]);
            foreach ($user_total as $key => $value) {
                $value->export_total = $order_totals[$value->id] ?? 0;
                //$user_total[$key] = $value;
            }
            if($user_total){
                $user_total = $user_total->sortByDesc('export_total')->values()->all();
            }
        }
        return response([
            'result' => true,
            'top_product' => $product,
            'top_user' => $user_total,
        ]);
    }

    public function top_province(Request $request){
        $where = [
            'status' => $this->orderRepository::STATUS_DONE
        ];
        if(empty($request->time)){
            $start_date = strtotime(date('d-m-Y', time() - 30*86400));
            $end_date = strtotime(date('d-m-Y', time())) + 86399;
        }else{
            $time = $request->time;
            $aryTime = explode(' to ', $time);
            $start_date = strtotime($aryTime[0]);
            $end_date = !empty($aryTime[1]) ? strtotime($aryTime[1]) + 86399 : $start_date + 86399;
        }
        $where['start_date'] = ['export_time', '>=', $start_date];
        $where['end_date'] = ['export_time', '<=', $end_date];
        $orders = $this->orderRepository->get($where, ['total' => 'DESC'], ['province_id'], ['province_id', DB::raw('SUM(total) as total')], $request->limit ?? 100)->pluck('total', 'province_id')->toArray();
        $data = [];
        if ($orders) {
            $data = $this->provinceRepository->get([['id', 'whereIn', array_keys($orders)]]);
            foreach ($data as $key => $value) {
                $value->export_total = $orders[$value->id] ?? 0;
            }
        }
        $data = $data->sortByDesc('export_total')->values()->all();
        return view('admin/content/dashboard/top-province', compact('data'));
    }

    public function top_district(Request $request){
        $where = [
            'status' => $this->orderRepository::STATUS_DONE
        ];
        if(!empty($request->province_id)){
            $where['province_id'] = $request->province_id;
        }
        if(empty($request->time)){
            $start_date = strtotime(date('d-m-Y', time() - 30*86400));
            $end_date = strtotime(date('d-m-Y', time())) + 86399;
        }else{
            $time = $request->time;
            $aryTime = explode(' to ', $time);
            $start_date = strtotime($aryTime[0]);
            $end_date = !empty($aryTime[1]) ? strtotime($aryTime[1]) + 86399 : $start_date + 86399;
        }
        $where['start_date'] = ['export_time', '>=', $start_date];
        $where['end_date'] = ['export_time', '<=', $end_date];
        $orders = $this->orderRepository->get($where, ['total' => 'DESC'], ['district_id'], ['district_id', DB::raw('SUM(total) as total')], $request->limit ?? 100)->pluck('total', 'district_id')->toArray();
        $data = [];
        if ($orders) {
            $data = $this->districtRepository->get([['id', 'whereIn', array_keys($orders)]]);
            foreach ($data as $key => $value) {
                $value->export_total = $orders[$value->id] ?? 0;
            }
        }
        $data = $data->sortByDesc('export_total')->values()->all();
        $provinces = $this->provinceRepository->pluck('name', 'id');
        return view('admin/content/dashboard/top-district', compact('data', 'provinces'));
    }

    public function top_product(Request $request)
    {
        $where = [
            'status' => $this->orderRepository::STATUS_DONE
        ];
        if (empty($request->time)) {
            $start_date = strtotime(date('d-m-Y', time() - 30 * 86400));
            $end_date = strtotime(date('d-m-Y', time())) + 86399;
        } else {
            $time = $request->time;
            $aryTime = explode(' to ', $time);
            $start_date = strtotime($aryTime[0]);
            $end_date = !empty($aryTime[1]) ? strtotime($aryTime[1]) + 86399 : $start_date + 86399;
        }
        $where['start_date'] = ['export_time', '>=', $start_date];
        $where['end_date'] = ['export_time', '<=', $end_date];
        $orders = $this->orderDetailRepository->get($where, ['total' => 'DESC'], ['product_id'], ['product_id', DB::raw('SUM(total_product) as total')], 100)->pluck('total', 'product_id')->toArray();
        $data = [];
        if ($orders) {
            $data = $this->productRepository->get([['id', 'whereIn', array_keys($orders)]]);
            foreach ($data as $key => $value) {
                $value->export_total = $orders[$value->id] ?? 0;
            }
        }
        $data = $data->sortByDesc('export_total')->values()->all();
        return view('admin/content/dashboard/top-product', compact('data'));
    }

    public function top_product_number(Request $request)
    {
        $where = [
            'status' => $this->orderRepository::STATUS_DONE
        ];
        if (empty($request->time)) {
            $start_date = strtotime(date('d-m-Y', time() - 30 * 86400));
            $end_date = strtotime(date('d-m-Y', time())) + 86399;
        } else {
            $time = $request->time;
            $aryTime = explode(' to ', $time);
            $start_date = strtotime($aryTime[0]);
            $end_date = !empty($aryTime[1]) ? strtotime($aryTime[1]) + 86399 : $start_date + 86399;
        }
        $where['start_date'] = ['export_time', '>=', $start_date];
        $where['end_date'] = ['export_time', '<=', $end_date];
        $orders = $this->orderDetailRepository->get($where, ['total' => 'DESC'], ['product_id'], ['product_id', DB::raw('SUM(num) as total')], 100)->pluck('total', 'product_id')->toArray();
        $data = [];
        if ($orders) {
            $data = $this->productRepository->get([['id', 'whereIn', array_keys($orders)]]);
            foreach ($data as $key => $value) {
                $value->export_total = $orders[$value->id] ?? 0;
            }
        }
        $data = $data->sortByDesc('export_total')->values()->all();
        return view('admin/content/dashboard/top-product-number', compact('data'));
    }

    public function top_user(Request $request){
        $where = [
            'status' => $this->orderRepository::STATUS_DONE
        ];
        if(empty($request->time)){
            $start_date = strtotime(date('d-m-Y', time() - 30*86400));
            $end_date = strtotime(date('d-m-Y', time())) + 86399;
        }else{
            $time = $request->time;
            $aryTime = explode(' to ', $time);
            $start_date = strtotime($aryTime[0]);
            $end_date = !empty($aryTime[1]) ? strtotime($aryTime[1]) + 86399 : $start_date + 86399;
        }
        $where['start_date'] = ['export_time', '>=', $start_date];
        $where['end_date'] = ['export_time', '<=', $end_date];
        $orders = $this->orderRepository->get($where, ['total' => 'DESC'], ['user_id'], ['user_id', DB::raw('SUM(total) as total')], $request->limit ?? 100)->pluck('total', 'user_id')->toArray();
        $data = [];
        if ($orders) {
            $data = $this->userRepository->get([['id', 'whereIn', array_keys($orders)]]);
            foreach ($data as $key => $value) {
                $value->export_total = $orders[$value->id] ?? 0;
            }
        }
        $data = $data->sortByDesc('export_total')->values()->all();
        return view('admin/content/dashboard/top-user', compact('data'));
    }
}
