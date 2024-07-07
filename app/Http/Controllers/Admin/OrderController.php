<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\CreateRequest;
use App\Repositories\Brand\BrandRepository;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\OrderDetail\OrderDetailRepository;
use App\Repositories\Discount\DiscountRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\ProductCategory\ProductCategoryRepository;
use App\Repositories\ProductDiscount\ProductDiscountRepository;
use App\Repositories\ProductStock\ProductStockRepository;
use App\Repositories\User\UserRepository;
use App\Support\Firebase\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public $orderRepository;
    public $productRepository;
    public $productStockRepository;
    public $orderDetailRepository;
    public $userRepository;
    public $categoryRepository;
    public $brandRepository;
    public $feeRepository;
    public $firebaseService;
    public $notificationRepository;

    public function __construct(OrderRepository             $orderRepository,
                                ProductRepository           $productRepository,
                                ProductDiscountRepository   $productDiscountRepository,
                                ProductStockRepository      $productStockRepository,
                                OrderDetailRepository       $orderDetailRepository,
                                UserRepository              $userRepository,
                                ProductCategoryRepository   $categoryRepository,
                                DiscountRepository          $discountRepository,
                                //FeeRepository               $feeRepository,
                                BrandRepository             $brandRepository,
                                FirebaseService             $firebaseService,
                                NotificationRepository      $notificationRepository
    )
    {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->productDiscountRepository = $productDiscountRepository;
        $this->productStockRepository = $productStockRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->userRepository = $userRepository;
        $this->categoryRepository = $categoryRepository;
        $this->brandRepository = $brandRepository;
        $this->discountRepository = $discountRepository;
        //$this->feeRepository = $feeRepository;
        $this->firebaseService = $firebaseService;
        $this->notificationRepository = $notificationRepository;
    }

    public function index(Request $request)
    {
        $where = [
            //'source' => $this->orderRepository::SOURCE_RETAIL
        ];
        if (!empty($request->id)) {
            $where['id'] = (int)$request->id;
        }
        if (!empty($request->user_id)) {
            $where['user_id'] = (int)$request->user_id;
        }
        if (!empty($request->shop_id)) {
            $where['shop_id'] = (int)$request->shop_id;
        }
        if (!empty($request->status)) {
            $where['status'] = (int)$request->status;
        }
        if (!empty($request->status_payment)) {
            $where['status_payment'] = (int)$request->status_payment;
        }
        if (!empty($request->payment_method)) {
            $where['payment_method'] = (int)$request->payment_method;
        }
        /*if (!empty($request->proxy)) {
            $where['proxy'] = (int)$request->proxy;
        }*/
        if (!empty($request->created_time)) {
            $aryTime = explode('to', $request->created_time);
            $start_time = !empty($aryTime[0]) ? strtotime($aryTime[0]) : 0;
            $end_time = !empty($aryTime[1]) ? (strtotime($aryTime[1]) + 86399) : (strtotime($aryTime[0]) + 86399);
            if (!empty($start_time)) {
                $where['start_time'] = ['created_time', '>=', $start_time];
                if (!empty($end_time)) {
                    $where['end_time'] = ['created_time', '<', $end_time];
                }
            }
        }
        /*if (!empty($request->source)) {
            $where['source'] = (int)$request->source;
        }*/
        $sum_total = $this->orderRepository->sum($where, 'total');
        $sum_product = $this->orderRepository->sum($where, 'total_product');
        $sum_cost = $this->orderRepository->sum($where, 'total_cost');
        $sum_discount = $this->orderRepository->sum($where, 'total_discount');
        $sum_vat = $this->orderRepository->sum($where, 'total_vat');
        $sum_fee = $this->orderRepository->sum($where, 'total_fee');
        $data = $this->orderRepository->paginate($where, ['id' => 'DESC']);
        $data->load('user', 'shop');
        $status = [0 => 'Vui lòng chọn'] + $this->orderRepository->aryStatus;
        $status_payment = [0 => 'Vui lòng chọn'] + $this->orderRepository->aryStatusPayment;
        $payment_method = [0 => 'Vui lòng chọn'] + $this->orderRepository->aryMethod;
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách"]
        ];
        return view('admin.content.order.list')->with(compact('data', 'breadcrumbs', 'status', 'status_payment', 'payment_method', 'sum_total', 'sum_product', 'sum_discount', 'sum_cost', 'sum_vat', 'sum_fee'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.orders.index'), 'name' => "Danh sach"], ['name' => "Chi tiết"]
        ];
        $categories = $this->categoryRepository->tree();
        $brands = $this->brandRepository->active_all();
        $discounts = $this->discountRepository->active_all();
        $fee = $this->feeRepository->active_all();
        return view('admin.content.order.create')->with(compact('breadcrumbs', 'categories', 'brands', 'discounts', 'fee'));
    }

    public function store(CreateRequest $request)
    {
        $user = $this->userRepository->first([
            'id' => $request->user_id,
            'type' => $this->userRepository::TYPE_SALE
        ]);
        if (!$user) {
            return response([
                'result' => false,
                'message' => 'Không tìm thấy thông tin khách hàng'
            ]);
        }

        $group_id = $user->group_id;
        $created_time = time();
        $data_cart = [
            'user_id' => $user->id,
            'total_product' => 0,
            'total_discount' => 0,
            'total_discount_group' => 0,
            'total_discount_product' => 0,
            'total_discount_order' => 0,
            //'total_profit' => 0,
            'total_vat' => 0,
            'total_fee' => 0,
            'total' => 0,
            'total_point' => 0,
            'created_time' => $created_time,
            'status' => $this->orderRepository::STATUS_NEW,
            'payment_method' => $request->payment_method ?? $this->orderRepository::METHOD_TRANSFER,
            'source' => $this->orderRepository::SOURCE_WHOLESALE,
            'name' => $request->name ?? $user->name,
            'phone' => $request->phone ?? $user->phone,
            'address' => $request->address ?? '',
            'note' => $request->note ?? '',
        ];
        $data_product = [];
        $products = $request->products;
        $products = collect($products)->pluck(null, 'id')->toArray();
        $product_ids = array_keys($products);
        $data = $this->productRepository->get(['id' => ['id', 'whereIn', $product_ids]]);
        $data->load('stocks', 'discounts', 'wholesales');

        $weight = 0;
        foreach ($data as $value) {
            $stock = $value->stocks[0];
            $price_sell = $stock->price_sell;
            $num = $products[$value->id]['num'];
            $weight += ($num * $value->weight);
            $total_product = $price_sell * $num;
            $total_point = ($num * $value->point_value);

            $total_discount_group = $total_vat = 0;

            $wholesales = $value->wholesales->toArray();
            usort($wholesales, function ($a, $b) {
                return $b['min_number'] - $a['min_number'];
            });
            $wholesale_price = $price_sell;
            foreach ($wholesales as $price) {
                if ($num >= $price['min_number']) {
                    $wholesale_price = $price['price_sell'];
                    break;
                }
            }
            $total_discount_product = ($price_sell - $wholesale_price) * $num;

            $discounts_group = $value->discounts;
            if ($group_id > 0) {
                $discount_group = $discounts_group->first(function ($value) use ($group_id) {
                    return $value->group_id == $group_id;
                });
                if ($discount_group) {
                    switch ($discount_group->discount_type) {
                        case $this->productDiscountRepository::DISCOUNT_TYPE_PERCENT:
                            $total_discount_group = (int)(($discount_group->discount_value * $price_sell) / 100) * $num; //ceil(($num * ) *  / 100);
                            break;
                        case $this->productDiscountRepository::DISCOUNT_TYPE_FIAT:
                            $total_discount_group = $num * $discount_group->discount_value;
                            break;
                    }
                }
            }

            if ($value->tax_value > 0) {
                switch ($value->tax_type) {
                    case $this->productRepository::VAT_TYPE_PERCENT:
                        $total_vat += ceil(($num * $price_sell) * $value->tax_value / 100);
                        break;
                    case $this->productRepository::VAT_TYPE_FIAT:
                        $total_vat += $num * $value->tax_value;
                        break;
                }
            }
            if ($value->vat_value > 0) {
                switch ($value->vat_type) {
                    case $this->productRepository::VAT_TYPE_PERCENT:
                        $total_vat += ceil(($num * $price_sell) * $value->vat_value / 100);
                        break;
                    case $this->productRepository::VAT_TYPE_FIAT:
                        $total_vat += $num * $value->vat_value;
                        break;
                }
            }
            $total_discount = $total_discount_group + $total_discount_product;
            $data_product[] = [
                'user_id' => auth()->id(),
                'product_id' => $value->id,
                'category_id' => $value->category_id,
                'brand_id' => $value->brand_id,
                'product_name' => $value->name,
                'attribute_name' => $stock->attribute_name,
                'product_stock_id' => $stock->id,
                'num' => $num,
                'price' => $price_sell,
                'total_product' => $total_product,
                'total_discount' => $total_discount,
                'total_discount_group' => $total_discount_group,
                'total_discount_product' => $total_discount_product,
                'total_vat' => $total_vat,
                'total' => $total_product - $total_discount + $total_vat,
                'total_point' => $total_point,
                'created_time' => $created_time,
                'status' => $this->orderRepository::STATUS_NEW,
                //'payment_method' => $request->payment_method ?? $this->cartRepository::METHOD_TRANSFER,
            ];
            $data_cart['total_product'] += $total_product;
            $data_cart['total_discount'] += $total_discount;
            $data_cart['total_discount_group'] += $total_discount_group;
            $data_cart['total_discount_product'] += $total_discount_product;
            $data_cart['total_vat'] += $total_vat;
            //$data_cart['total'] += ($total_product + $total_vat - $total_discount);
            $data_cart['total_point'] += $total_point;
        }

        $discounts = $this->discountRepository->active_all()->toArray();
        usort($discounts, function ($a, $b) {
            return $b['discount_total'] - $a['discount_total'];
        });
        $total_discount_order = 0;
        foreach ($discounts as $value) {
            if ($data_cart['total_product'] >= $value['discount_total']) {
                switch ($value['discount_type']) {
                    case DiscountRepository::DISCOUNT_TYPE_PERCENT:
                        $total_discount_order = (int)(($value['discount_value'] * $data_cart['total_product']) / 100);
                        break;
                    case DiscountRepository::DISCOUNT_TYPE_FIAT:
                        $total_discount_order = $value['discount_value'];
                        break;
                }
                break;
            }
        }
        $data_cart['total_discount_order'] += $total_discount_order;
        $data_cart['total_discount'] += $total_discount_order;

        $fee = $this->feeRepository->active_all()->toArray();
        usort($fee, function ($a, $b) {
            return $b['weight'] - $a['weight'];
        });
        $total_fee = 0;
        foreach ($fee as $value) {
            if ($weight >= $value['weight']) {
                $total_fee += $value['price'];
                if ($value['weight_than'] > 0 && $value['price_than'] > 0) {
                    $weight_than = $weight - $value['weight'];
                    $total_fee += ceil($weight_than / $value['weight_than']) * $value['price_than'];
                }
                break;
            }
        }
        $data_cart['total_fee'] = $total_fee;
        $data_cart['total'] = ($data_cart['total_product'] + $data_cart['total_vat'] + $data_cart['total_fee'] - $data_cart['total_discount']);

        DB::transaction(function () use ($data_cart, $data_product, $products, $user) {
            $order = $this->orderRepository->create($data_cart);
            $order->details()->createMany($data_product);
        });
        return response([
            'result' => true
        ]);
    }

    public function show($id)
    {
        $data = $this->orderRepository->find($id);
        $data->load('user', 'shop');
        $status = $this->orderRepository->aryStatus;
        $status_payment = $this->orderRepository->aryStatusPayment;
        $payment_method = $this->orderRepository->aryMethod;
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.orders.index'), 'name' => "Danh sach"], ['name' => "Chi tiết"]
        ];
        return view('admin.content.order.show')->with(compact('data', 'breadcrumbs', 'status', 'status_payment', 'payment_method'));
    }

    public function print($id)
    {
        $data = $this->orderRepository->find($id);
        $data->load('user', 'details');
        $status = $this->orderRepository->aryStatus;
        $status_payment = $this->orderRepository->aryStatusPayment;
        $payment_method = $this->orderRepository->aryMethod;
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Chi tiết"]
        ];
        return view('admin.content.order.print')->with(compact('data', 'breadcrumbs', 'status', 'status_payment', 'payment_method'));
    }

    public function update_payment($id)
    {
        $order = $this->orderRepository->find($id);
        if (!$order) {
            return response([
                'result' => false,
                'message' => 'Không tìm thấy thông tin đơn hàng'
            ]);
        }
        if ($order->status_payment == $this->orderRepository::STATUS_PAYMENT_UNPAID) {
            $data = [
                'status_payment' => $this->orderRepository::STATUS_PAYMENT_PAID,
                'payment_time' => time(),
                'payment_id' => auth('admin')->id(),
                'total_payment' => $order->total
            ];
            $order = DB::transaction(function () use ($order, $data) {
                $order = $this->orderRepository->edit($order, $data);
                $this->orderDetailRepository->editWhere(['order_id' => $order->id], ['status_payment' => $order->status_payment, 'payment_time' => $order->payment_time]);
                $order->logs()->create([
                    'time' => time(),
                    'user_id' => 0,
                    'user_name' => '',
                    'user_phone' => '',
                    'status' => $order->status,
                    'title' => 'Xác nhận thanh toán',
                    'description' =>  'BellHome xác nhận thanh toán đơn hàng'
                ]);
                return $order;
            });
//            if ($order->payment_method == $this->orderRepository::METHOD_TRANSFER) {
//                NewOrder::dispatch($order->id)->onQueue('order');
//            }
//            if ($order->status == $this->orderRepository::STATUS_DONE && $order->status_payment == $this->orderRepository::STATUS_PAYMENT_PAID) {
//                PaymentOrder::dispatch($order->id)->onQueue('order');
//            }
            //PaymentOrder::dispatch($order->id)->onQueue('order');

            try {
                $customer = $this->userRepository->find($order->user_id);
//                $this->firebaseService->send([
//                    'device_token' => $customer->device_token,
//                    'title' => 'Xác nhận thanh toán',
//                    'text' => 'Đơn hàng ' . $order->id . ' đã được xác nhận thanh toán thành công',
//                    'icon' => '',
//                    'item_type' => FirebaseService::TYPE_SELF_ORDER,
//                    'item_id' => $order->id,
//                    'user_id' => $customer->id,
//                    'type' => $this->notificationRepository::TYPE_MEMBER,
//                ]);
            } catch (\Exception $exception) {

            }

            return response([
                'result' => true
            ]);
        }
        return response([
            'result' => false,
            'message' => 'Không thể cập nhật thanh toán đơn hàng'
        ]);

    }

    public function update_status($id, Request $request)
    {
        $order = $this->orderRepository->find($id);
        if (!$order) {
            return response([
                'result' => false,
                'message' => 'Không tìm thấy thông tin đơn hàng'
            ]);
        }
        if ($request->status == $this->orderRepository::STATUS_CANCEL) {
            if ($order->status_payment == $this->orderRepository::STATUS_PAYMENT_UNPAID) {
                if ($order->status == $this->orderRepository::STATUS_NEW) {
                    $data = [
                        'status' => $this->orderRepository::STATUS_CANCEL,
                        'cancel_time' => time(),
                        'cancel_id' => auth('admin')->id()
                    ];
                    DB::transaction(function () use ($order, $data) {
                        $order = $this->orderRepository->edit($order, $data);
                        $this->orderDetailRepository->editWhere(['order_id' => $order->id], ['status' => $order->status]);
//                        $this->orderHandleRepository->deleteWhere([
//                            'order_id' => $order->id,
//                        ]);
                        $order->logs()->create([
                            'time' => time(),
                            'user_id' => 0,
                            'user_name' => '',
                            'user_phone' => '',
                            'status' => $order->status,
                            'title' => 'Hủy đơn hàng',
                            'description' =>  'Zapicare thao tác hủy đơn hàng'
                        ]);
                    });
                    try {
                        $customer = $this->userRepository->find($order->user_id);
//                        $this->firebaseService->send([
//                            'device_token' => $customer->device_token,
//                            'title' => 'Hủy đơn hàng',
//                            'text' => 'Đơn hàng ' . $order->id . ' đã được hủy',
//                            'icon' => '',
//                            'item_type' => FirebaseService::TYPE_SELF_ORDER,
//                            'item_id' => $order->id,
//                            'user_id' => $customer->id,
//                            'type' => $this->notificationRepository::TYPE_MEMBER,
//                        ]);
                    } catch (\Exception $exception) {

                    }
                    return response([
                        'result' => true
                    ]);
                }
            }
        }
        if ($request->status == $this->orderRepository::STATUS_CONFIRM) {
            if ($order->status == $this->orderRepository::STATUS_NEW) {
                $data = [
                    'status' => $this->orderRepository::STATUS_CONFIRM,
                    'confirm_time' => time(),
                    'confirm_id' => auth('admin')->id()
                ];
                DB::transaction(function () use ($order, $data) {
                    $order = $this->orderRepository->edit($order, $data);
                    $this->orderDetailRepository->editWhere(['order_id' => $order->id], ['status' => $order->status]);
//                    $this->orderHandleRepository->deleteWhere([
//                        'order_id' => $order->id,
//                    ]);
                    $order->logs()->create([
                        'time' => time(),
                        'user_id' => 0,
                        'user_name' => '',
                        'user_phone' => '',
                        'status' => $order->status,
                        'title' => 'Xác nhận đơn hàng',
                        'description' =>  'Zapicare thao tác xác nhận đơn hàng'
                    ]);
                });
                try {
                    $customer = $this->userRepository->find($order->user_id);
//                    $this->firebaseService->send([
//                        'device_token' => $customer->device_token,
//                        'title' => 'Xác nhận đơn hàng',
//                        'text' => 'Đơn hàng ' . $order->id . ' đang được xử lý',
//                        'icon' => '',
//                        'item_type' => FirebaseService::TYPE_SELF_ORDER,
//                        'item_id' => $order->id,
//                        'user_id' => $customer->id,
//                        'type' => $this->notificationRepository::TYPE_MEMBER,
//                    ]);
                } catch (\Exception $exception) {

                }
                return response([
                    'result' => true
                ]);
            }
        }

        if ($request->status == $this->orderRepository::STATUS_DONE) {
            if ($order->status == $this->orderRepository::STATUS_CONFIRM) {
                DB::transaction(function () use ($order) {
                    $data = [
                        'status' => $this->orderRepository::STATUS_DONE,
                        'export_id' => auth('admin')->id(),
                        'export_time' => time(),
                    ];
                    $order = $this->orderRepository->edit($order, $data);
                    $this->orderDetailRepository->editWhere(['order_id' => $order->id], ['status' => $order->status, 'export_time' => $order->export_time]);
                    $order->logs()->create([
                        'time' => time(),
                        'user_id' => 0,
                        'user_name' => '',
                        'user_phone' => '',
                        'status' => $order->status,
                        'title' => 'Xác nhận giao hàng',
                        'description' =>  'Zapicare xác nhận giao hàng thành công'
                    ]);
//lực tính số lượng sp đã bán
//                    $orderDetails = $this->orderDetailRepository->getByOrderId($order->id);
//                    foreach ($orderDetails as $detail) {
//                        // Tăng số lượng đã bán của sản phẩm
//                        DB::table('products')->where('id', $detail->product_id)->increment('total_sell', $detail->num);
//                    }
                    $orderDetails = $this->orderDetailRepository->getByOrderId($order->id);
                    dd($orderDetails);
                    foreach ($orderDetails as $detail) {
                        // Tính tổng số lượng đã bán của sản phẩm từ các chi tiết đơn hàng
                        $totalSold = DB::table('order_detail')
                            ->where('product_id', $detail->product_id)
                            ->where('status', $this->orderRepository::STATUS_DONE)
                            ->sum('num');
                        // Cập nhật số lượng đã bán trong bảng products
                        DB::table('products')->where('id', $detail->product_id)->update(['total_sell' => $totalSold]);
                    }
//                    $this->userRepository->increment([
//                        'id' => $order->user_id
//                    ], 'point', $order->total_point);
//                    if ($order->status == $this->orderRepository::STATUS_DONE && $order->status_payment == $this->orderRepository::STATUS_PAYMENT_PAID) {
//                        PaymentOrder::dispatch($order->id)->onQueue('order');
//                    }
                });
                try {
                    $customer = $this->userRepository->find($order->user_id);
//                    $this->firebaseService->send([
//                        'device_token' => $customer->device_token,
//                        'title' => 'Xác nhận giao hàng',
//                        'text' => 'Đơn hàng ' . $order->id . ' đã giao thành công',
//                        'icon' => '',
//                        'item_type' => FirebaseService::TYPE_SELF_ORDER,
//                        'item_id' => $order->id,
//                        'user_id' => $customer->id,
//                        'type' => $this->notificationRepository::TYPE_MEMBER,
//                    ]);
                } catch (\Exception $exception) {

                }
                return response([
                    'result' => true
                ]);
                //AffiliateJob::dispatch($id)->onQueue('affiliate');
            }
        }

        return response([
            'result' => false,
            'message' => 'Không thể cập nhật trạng thái đơn hàng'
        ]);
    }

    public function addHandle(Request $request)
    {
        $data = $request->only(['order_id', 'user_id']);
        $order = $this->orderRepository->find($data['order_id']);
        $data['admin_id'] = auth('admin')->id();
        $data['type'] = $this->orderHandleRepository::TYPE_ADMIN;
        $user = $this->userRepository->find($data['user_id']);
        DB::transaction(function () use ($data, $order, $user) {
            $this->orderHandleRepository->create($data);
            $order->logs()->create([
                'time' => time(),
                'user_id' => 0,
                'user_name' => '',
                'user_phone' => '',
                'status' => $order->status,
                'title' => 'Thêm cửa hàng xử lý đơn hàng',
                'description' => $user->name . ' DT: ' . $user->phone . ' được giao xử lý đơn hàng'
            ]);
        });
        try {
            $this->firebaseService->send([
                'device_token' => $user->device_token,
                'title' => 'Xử lý đơn hàng',
                'text' => 'Bạn được đề xuất để xử lý đơn hàng ' . $data['order_id'],
                'icon' => '',
                'item_type' => FirebaseService::TYPE_HANDLE_ORDER,
                'item_id' => $data['order_id'],
                'user_id' => $user->id,
                'type' => $this->notificationRepository::TYPE_MEMBER,
            ]);
        } catch (\Exception $exception) {
        }
        return response([
            'result' => true
        ]);
    }


}
