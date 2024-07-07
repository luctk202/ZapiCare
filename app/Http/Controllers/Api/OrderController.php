<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Order\CreateRequest;
use App\Jobs\CreateUserProfit;
use App\Jobs\HandleOrder;
use App\Jobs\MemberDoneOrder;
use App\Jobs\PaymentOrder;
use App\Jobs\NewOrder;
use App\Models\ShopCustomer;
use App\Repositories\AffiliateGroup\AffiliateGroupRepository;
use App\Repositories\AffiliateSetting\AffiliateSettingRepository;
use App\Repositories\Coupon\CouponRepository;
use App\Repositories\DeliveryGroup\DeliveryGroupRepository;
use App\Repositories\DiscountGroup\DiscountGroupRepository;
use App\Repositories\Fee\FeeRepository;
use App\Repositories\FFDGroup\FFDGroupRepository;
use App\Repositories\FlashSaleProduct\FlashSaleProductRepository;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\OrderCoupon\OrderCouponRepository;
use App\Repositories\OrderDetail\OrderDetailRepository;
use App\Repositories\OrderHandle\OrderHandleRepository;
use App\Repositories\OrderLog\OrderLogRepository;
use App\Repositories\OrderProfit\OrderProfitRepository;
use App\Repositories\OrderProfitZone\OrderProfitZoneRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\ProductStock\ProductStockRepository;
use App\Repositories\Shop\ShopRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\WalletLog\WalletLogRepository;
use App\Repositories\Ward\WardRepository;
use App\Repositories\Shop\ZoneRepository;
use App\Support\Firebase\FirebaseService;
use App\Support\Telegram\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public $productRepository;
    public $productStockRepository;
    public $shopRepository;
    public $orderRepository;
    public $orderDetailRepository;
    public $userRepository;
    public $flashSaleProductRepository;
    public $firebaseService;
    public $notificationRepository;
    public $wardRepository;
    public $orderCouponRepository;
    public $couponRepository;

    public function __construct(ProductRepository          $productRepository,
                                ProductStockRepository     $productStockRepository,
                                OrderRepository            $orderRepository,
                                ShopRepository             $shopRepository,
                                OrderDetailRepository      $orderDetailRepository,
                                OrderCouponRepository      $orderCouponRepository,
                                UserRepository             $userRepository,
                                FlashSaleProductRepository $flashSaleProductRepository,
                                FirebaseService            $firebaseService,
                                WardRepository             $wardRepository,
                                CouponRepository           $couponRepository,
                                NotificationRepository     $notificationRepository
    )
    {
        $this->shopRepository = $shopRepository;
        $this->userRepository = $userRepository;
        $this->productRepository = $productRepository;
        $this->productStockRepository = $productStockRepository;
        $this->orderRepository = $orderRepository;
        $this->orderDetailRepository = $orderDetailRepository;
        $this->flashSaleProductRepository = $flashSaleProductRepository;
        $this->firebaseService = $firebaseService;
        $this->notificationRepository = $notificationRepository;
        $this->wardRepository = $wardRepository;
        $this->orderCouponRepository = $orderCouponRepository;
        $this->couponRepository = $couponRepository;
    }

    public function index(Request $request)
    {
        $where = [
            'user_id' => auth()->id()
        ];
        if (!empty($request->id)) {
            $where['id'] = (int)$request->id;
        }
        if (!empty($request->status)) {
            $where['status'] = (int)$request->status;
        }
        if (!empty($request->status_payment)) {
            $where['status_payment'] = (int)$request->status_payment;
        }
        if (!empty($request->shop_id)) {
            $where['shop_id'] = (int)$request->shop_id;
        }
        if (!empty($request->start_time)) {
            $where['start_time'] = ['created_time', '>=', (int)$request->start_time];
        }
        if (!empty($request->end_time)) {
            $where['end_time'] = ['created_time', '<', (int)$request->end_time];
        }
        $data = $this->orderRepository->paginate($where, ['id' => 'DESC'], [], [], $request->limit ?? 50);
        if ($data) {
            $data->load('details');
        }
        return response([
            'result' => true,
            'data' => $data
        ]);
    }

    public function count(Request $request)
    {
        $where = [
            'user_id' => auth()->id()
        ];
        if (!empty($request->id)) {
            $where['id'] = (int)$request->id;
        }
        /*if (!empty($request->status)) {
            $where['status'] = (int)$request->status;
        }*/
        if (!empty($request->status_payment)) {
            $where['status_payment'] = (int)$request->status_payment;
        }
        if (!empty($request->shop_id)) {
            $where['shop_id'] = (int)$request->shop_id;
        }
        if (!empty($request->start_time)) {
            $where['start_time'] = ['created_time', '>=', (int)$request->start_time];
        }
        if (!empty($request->end_time)) {
            $where['end_time'] = ['created_time', '<', (int)$request->end_time];
        }
        $data = $this->orderRepository->get($where, [], ['status'], ['status', DB::raw('COUNT(*) as count')]);
        return response([
            'result' => true,
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $data = $this->orderRepository->first([
            'id' => $id,
            'user_id' => auth()->id()
        ]);
        if (!$data) {
            return response([
                'result' => false,
                'message' => 'Không tìm thấy thông tin đơn hàng'
            ]);
        }
        $data->load('details');
        return response([
            'result' => true,
            'data' => $data
        ]);
    }

    public function cancel($id, Request $request)
    {
        $user = $this->userRepository->find(auth()->id());
        $where = [
            'id' => $id,
            'user_id' => auth()->id()
        ];
        $order = $this->orderRepository->first($where);
        if (!$order) {
            return response([
                'result' => false,
                'message' => 'Không tìm thấy thông tin đơn hàng'
            ]);
        }
        if (!in_array($order->status, [$this->orderRepository::STATUS_NEW])) {
            return response([
                'result' => false,
                'message' => 'Đơn hàng đang xử lý không thể hủy'
            ]);
        }
        $cancel_note = $request->note ?? '';
        DB::transaction(function () use ($order, $cancel_note, $user) {
            $order = $this->orderRepository->edit($order, [
                'status' => $this->orderRepository::STATUS_CANCEL,
                'cancel_time' => time(),
                'cancel_user_id' => auth()->id(),
                'cancel_note' => $cancel_note
            ]);
            $this->orderDetailRepository->editWhere(['order_id' => $order->id], [
                'status' => $this->orderRepository::STATUS_CANCEL
            ]);
//            $order->logs()->create([
//                'time' => time(),
//                'user_id' => $order->user_id,
//                'user_name' => $user->name,
//                'user_phone' => $user->phone,
//                'status' => $order->status,
//                'title' => 'Hủy đơn hàng',
//                'description' => $user->name . ' ĐT : ' . $user->phone . ' hủy đơn hàng đơn hàng'
//            ]);
        });

        return response([
            'result' => true,
            'message' => 'Hủy đơn hàng thành công'
        ]);
    }

//    public function store(CreateRequest $request)
//    {
//        $user = $this->userRepository->first([
//            'id' => auth()->id(),
//        ]);
//        $created_time = time();
//        $data_order = [
//            'user_id' => $user->id ?? 0,
//            'total_product' => 0,
//            'total_discount' => 0,
//            'total_discount_coupon' => 0,
//            'total_discount_product' => 0,
//            'total_vat' => 0,
//            'total_fee' => 0,
//            'total_cost' => 0,
//            'total_profit' => 0,
//            'total' => 0,
//            'total_payment' => 0,
//            'created_time' => $created_time,
//            'status' => $this->orderRepository::STATUS_NEW,
//            'status_payment' => $this->orderRepository::STATUS_PAYMENT_UNPAID,
//            'payment_method' => $request->payment_method,
//            'payment_time' => 0,
//            'name' => $request->name,
//            'phone' => $request->phone,
//            'address' => $request->address,
//            'province_id' => $request->province_id,
//            'district_id' => $request->district_id,
//            'ward_id' => $request->ward_id,
//            'note' => $request->note ?? '',
//        ];
//        $products = $request->products;
//        $products = collect($products)->pluck(null, 'id')->toArray();
//        $product_ids = array_keys($products);
//        $where = [
//            'id' => ['id', 'whereIn', $product_ids],
//            'status' => $this->productRepository::STATUS_SHOW,
//            'approval' => $this->productRepository::APPROVAL_DONE,
//        ];
//        $data = $this->productRepository->get($where);
//        $data_ids = $data->pluck('id')->toArray();
//        $hide_ids = array_diff($product_ids, $data_ids);
//        if ($hide_ids) {
//            return response([
//                'result' => false,
//                'message' => 'Sản phẩm hiện đã ngừng bán',
//                'ids' => $hide_ids
//            ]);
//        }
//        //$data->load('stocks');
//
//        $data->load('flash_sale');
//        $total_order_product = 0;
//        $shop_ids = [];
//        $data_product = [];
//        $data_order_shop = [];
//        $data_total_product = [];
//        foreach ($data as $value) {
//            $attributes_name = $products[$value->id]['attributes_name'] ?? '';
//            $stock = $this->productStockRepository->first(['product_id' => $value->id, 'attributes_name' => $attributes_name]);
//            if (!$stock) {
//                return response([
//                    'result' => false,
//                    'message' => 'Sản phẩm ' . $value->name . ' ' . $attributes_name . 'không còn hàng',
//                ]);
//            }
//            $price_sell = $stock->price_sell;
//            $price_cost = $stock->price_cost;
//            $num = $products[$value->id]['num'] ?? 1;
//            if ($num < 1) {
//                return response([
//                    'result' => false,
//                    'message' => 'Vui lòng nhập số lượng cho sản phẩm ' . $value->name . ' ' . $attributes_name,
//                ]);
//            }
//            $weight = ($num * $value->weight);
//            $total_product = $price_sell * $num;
//            $total_cost = $price_cost * $num;
//            /*tính tiền thuế*/
//            $total_vat = 0;
//            if ($value->tax_value > 0) {
//                switch ($value->tax_type) {
//                    case $this->productRepository::VAT_TYPE_PERCENT:
//                        $total_vat += ceil($total_product * $value->tax_value / 100);
//                        break;
//                    case $this->productRepository::VAT_TYPE_FIAT:
//                        $total_vat += $num * $value->tax_value;
//                        break;
//                }
//            }
//            if ($value->vat_value > 0) {
//                switch ($value->vat_type) {
//                    case $this->productRepository::VAT_TYPE_PERCENT:
//                        $total_vat += ceil($total_product * $value->vat_value / 100);
//                        break;
//                    case $this->productRepository::VAT_TYPE_FIAT:
//                        $total_vat += $num * $value->vat_value;
//                        break;
//                }
//            }
//
//            $total_discount = 0;
//            if ($value->flash_sale) {
//                $flash_sale = $value->flash_sale;
//                switch ($flash_sale->discount_type) {
//                    case $this->flashSaleProductRepository::DISCOUNT_TYPE_PERCENT:
//                        $total_discount += floor($total_product * $flash_sale->discount_value / 100);
//                        break;
//                    case $this->flashSaleProductRepository::DISCOUNT_TYPE_FIAT:
//                        $total_discount += $num * $flash_sale->discount_value;
//                        break;
//                }
//            }
//            $total_profit = $total_product - $total_discount + $total_vat - $total_cost;
//            if (!in_array($value->shop_id, $shop_ids)) {
//                $shop_ids[] = $value->shop_id;
//                $data_order_shop[$value->shop_id] = $data_order;
//            }
//            $data_total_product[] = [
//                'shop_id' => $value->shop_id,
//                'product_id' => $value->id,
//                'category_id' => $value->category_id,
//                'total_product' => $total_product,
//            ];
//            $data_product[$value->shop_id][] = [
//                'user_id' => auth()->id(),
//                'shop_id' => $value->shop_id,
//                'product_id' => $value->id,
//                'category_id' => $value->category_id,
//                'product_name' => $value->name,
//                'product_sku' => $value->sku,
//                'product_image' => $value->avatar,
//                'attributes_name' => $stock->attributes_name,
//                'product_stock_id' => $stock->id,
//                'num' => $num,
//                'price' => $price_sell,
//                'price_cost' => $price_cost,
//                'price_discount' => $price_sell - ($total_discount / $num),
//                'total_product' => $total_product,
//                'total_discount' => $total_discount,
//                'total_vat' => $total_vat,
//                'total_profit' => $total_profit,
//                'total_cost' => $total_cost,
//                'total' => $total_product - $total_discount + $total_vat,
//                'created_time' => $created_time,
//                'status' => $data_order['status'],
//                'status_payment' => $data_order['status_payment'],
//                'payment_time' => $data_order['payment_time'],
//                'weight' => $weight
//            ];
//            $total_order_product += $total_product;
//            $data_order_shop[$value->shop_id]['total_product'] += $total_product;
//            $data_order_shop[$value->shop_id]['total_discount'] += $total_discount;
//            $data_order_shop[$value->shop_id]['total_discount_product'] += $total_discount;
//            $data_order_shop[$value->shop_id]['total_vat'] += $total_vat;
//            $data_order_shop[$value->shop_id]['total_profit'] += $total_profit;
//            $data_order_shop[$value->shop_id]['total_cost'] += $total_cost;
//            $data_order_shop[$value->shop_id]['total'] += $total_product - $total_discount + $total_vat;
//        }
//
//        // xu ly ma giam gia
//        $coupons = $request->coupons ?? [];
//        if ($coupons) {
//            $where = [
//                'code' => ['code', 'whereIn', $coupons],
//                'status' => $this->couponRepository::STATUS_ACTIVE,
//            ];
//            $data_coupon = $this->couponRepository->get($where);
//            $data_coupon_ids = $data_coupon->pluck('code')->toArray();
//            $hide_coupon_ids = array_diff($coupons, $data_coupon_ids);
//            if ($hide_coupon_ids) {
//                return response([
//                    'result' => false,
//                    'message' => 'Mã giảm giá không đúng',
//                    'ids' => $hide_coupon_ids
//                ]);
//            }
//            $data_discount = [];
//            foreach ($data_coupon as $dt) {
//                if($dt->num > 0){
//                    if($dt->num_used >= $dt->num){
//                        return response([
//                            'result' => false,
//                            'message' => 'Mã giảm giá ' . $dt->code . ' đã được sử dụng hết',
//                        ]);
//                    }
//                }
//                if ($dt->max_per_person > 0) {
//                    $count = $this->orderCouponRepository->count([
//                        'code' => $dt->code,
//                        'user_id' => auth()->id()
//                    ]);
//                    if ($count >= $dt->max_per_person) {
//                        return response([
//                            'result' => false,
//                            'message' => 'Mã giảm giá ' . $dt->code . ' đã được sử dụng',
//                        ]);
//                    }
//                }
//                $collection = collect($data_total_product);
//                if ($dt->source_id > 0) {
//                    $collection = $collection->where('shop_id', $dt->source_id);
//                }
//                if ($dt->shop_id > 0) {
//                    $collection = $collection->where('shop_id', $dt->shop_id);
//                }
//                if ($dt->category_id > 0) {
//                    $collection = $collection->where('category_id', $dt->category_id);
//                }
//                if ($dt->product_id > 0) {
//                    $collection = $collection->where('product_id', $dt->product_id);
//                }
//                $total_order_product_coupon = $collection->sum('total_product');
//                if ($dt->min_total_order > 0) {
//                    if ($total_order_product_coupon < $dt->min_total_order) {
//                        return response([
//                            'result' => false,
//                            'message' => 'Giá trị đơn hàng chưa đủ để sử dụng mã giảm giá ' . $dt->code,
//                        ]);
//                    }
//                }
//                if ($dt->source_id == 0) {
//                    if ($dt->concurrency == $this->couponRepository::NO_CONCURRENCY) {
//                        if (count($data_coupon) > 1) {
//                            return response([
//                                'result' => false,
//                                'message' => 'Mã giảm giá ' . $dt->code . ' không được áp dụng đồng thời các mã khác',
//                            ]);
//                        }
//                    }
//                    if ($dt->shop_id > 0) {
//                        if (!in_array($dt->shop_id, $shop_ids)) {
//                            return response([
//                                'result' => false,
//                                'message' => 'Mã giảm giá ' . $dt->code . ' không được áp dụng cho đơn của shop',
//                            ]);
//                        }
//                    }
//                }
//                if ($dt->source_id > 0) {
//                    if ($dt->concurrency == $this->couponRepository::NO_CONCURRENCY) {
//                        $count_data_coupon = $data_coupon->whereIn('source_id', [0, $dt->source_id])->count();
//                        if ($count_data_coupon > 1) {
//                            return response([
//                                'result' => false,
//                                'message' => 'Mã giảm giá ' . $dt->code . ' không được áp dụng đồng thời các mã khác',
//                            ]);
//                        }
//                    }
//                    if (!in_array($dt->source_id, $shop_ids)) {
//                        return response([
//                            'result' => false,
//                            'message' => 'Mã giảm giá ' . $dt->code . ' không được áp dụng cho đơn của shop',
//                        ]);
//                    }
//                }
//                $total_discount_coupon = 0;
//                switch ($dt->discount_type) {
//                    case $this->couponRepository::DISCOUNT_TYPE_PERCENT:
//                        $total_discount_coupon = floor($total_order_product_coupon * $dt->discount_value / 100);
//                        break;
//                    case $this->couponRepository::DISCOUNT_TYPE_FIAT:
//                        $total_discount_coupon += $dt->discount_value;
//                        break;
//                }
//                $total_discount_coupon = ($total_discount_coupon <= $dt->discount_max_value) ? $total_discount_coupon : $dt->discount_max_value;
//                $data_discount[] = [
//                    'total' => $total_order_product_coupon,
//                    'total_discount' => $total_discount_coupon,
//                    'shop_id' => $dt->source_id ?? $dt->shop_id,
//                    'code' => $dt->code
//                ];
//            }
//            foreach ($data_discount as $dt) {
//                if ($dt['shop_id'] > 0) {
//                    if(isset($data_order_shop[$dt['shop_id']])){
//                        $data_order_shop[$dt['shop_id']]['total_discount_coupon'] += $dt['total_discount'];
//                        $data_order_shop[$dt['shop_id']]['total_discount'] += $dt['total_discount'];
//                        $data_order_shop[$dt['shop_id']]['total'] = ($data_order_shop[$dt['shop_id']]['total_product'] + $data_order_shop[$dt['shop_id']]['total_vat'] + $data_order_shop[$dt['shop_id']]['total_fee'] - $data_order_shop[$dt['shop_id']]['total_discount']);
//                        $data_order_shop[$dt['shop_id']]['coupons'][] = [
//                            'shop_id' => $dt['shop_id'],
//                            'user_id' => auth()->id(),
//                            'code' => $dt['code'],
//                            'coupon_source' => $dt['shop_id'],
//                            'coupon_value' => $dt['total_discount']
//                        ];
//                    }
//                } else {
//                    foreach ($data_order_shop as $shop_id => $v){
//                        $total_discount_coupon = floor($v['total_product'] * $dt['total_discount'] / $dt['total']);
//                        $v['total_discount_coupon'] += $total_discount_coupon;
//                        $v['total_discount'] += $total_discount_coupon;
//                        $v['total'] = ($v['total_product'] + $v['total_vat'] + $v['total_fee'] - $v['total_discount']);
//                        $v['coupons'][] = [
//                            'shop_id' => $dt['shop_id'],
//                            'user_id' => auth()->id(),
//                            'code' => $dt['code'],
//                            'coupon_source' => $dt['shop_id'],
//                            'coupon_value' => $total_discount_coupon
//                        ];
//                        $data_order_shop[$shop_id] = $v;
//                    }
//                }
//            }
//        }
//        DB::transaction(function () use ($data_order_shop, $data_product, $user) {
//            foreach ($data_order_shop as $shop_id => $data_order){
//                $shop = $this->shopRepository->find($shop_id);
//                $data_order['partner_id'] = $shop->partner_id;
//                $data_order['shop_id'] = $shop_id;
//                $coupons = $data_order['coupons'] ?? [] ;
//                $order = $this->orderRepository->create($data_order);
//                $order_details = $order->details()->createMany($data_product[$shop_id]);
//                if($coupons){
//                    $order->coupons()->createMany($coupons);
//                }
//            }
//            if ($order_details){
//                foreach ($order_details as $order_detail){
//                        ShopCustomer::firstOrCreate(
//                            ['user_id'=>$order->user_id],
//                            ['shop_id'=>$order_detail->shop_id]
//                        );
//                }
//            }
//        });
//
//        return response([
//            'result' => true,
//        ]);
//    }

    public function store(CreateRequest $request)
    {
        $user = $this->userRepository->first([
            'id' => auth()->id(),
        ]);
        $created_time = time();
        $data_order = [
            'user_id' => $user->id ?? 0,
            'total_product' => 0,
            'total_discount' => 0,
            'total_discount_coupon' => 0,
            'total_discount_product' => 0,
            'total_vat' => 0,
            'total_fee' => 0,
            'total_cost' => 0,
            'total_profit' => 0,
            'total' => 0,
            'total_payment' => 0,
            'created_time' => $created_time,
            'status' => $this->orderRepository::STATUS_NEW,
            'status_payment' => $this->orderRepository::STATUS_PAYMENT_UNPAID,
            'payment_method' => $request->payment_method,
            'payment_time' => 0,
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
            'province_id' => $request->province_id,
            'district_id' => $request->district_id,
            'ward_id' => $request->ward_id,
            'note' => $request->note ?? '',
        ];
        $products = $request->products;
        $products = collect($products)->pluck(null, 'id')->toArray();
        $product_ids = array_keys($products);
        $where = [
            'id' => ['id', 'whereIn', $product_ids],
            'status' => $this->productRepository::STATUS_SHOW,
            'approval' => $this->productRepository::APPROVAL_DONE,
        ];
        $data = $this->productRepository->get($where);
        $data_ids = $data->pluck('id')->toArray();
        $hide_ids = array_diff($product_ids, $data_ids);
        if ($hide_ids) {
            return response([
                'result' => false,
                'message' => 'Sản phẩm hiện đã ngừng bán',
                'ids' => $hide_ids
            ]);
        }
        //$data->load('stocks');

        $data->load('flash_sale');
        $total_order_product = 0;
        $data_product = [];
        foreach ($data as $value) {
            $attributes_name = $products[$value->id]['attributes_name'] ?? '';
            $stock = $this->productStockRepository->first(['product_id' => $value->id, 'attributes_name' => $attributes_name]);
            if (!$stock) {
                return response([
                    'result' => false,
                    'message' => 'Sản phẩm ' . $value->name . ' ' . $attributes_name . 'không còn hàng',
                ]);
            }
            $price_sell = $stock->price_sell;
            $price_cost = $stock->price_cost;
            $num = $products[$value->id]['num'] ?? 1;
            if ($num < 1) {
                return response([
                    'result' => false,
                    'message' => 'Vui lòng nhập số lượng cho sản phẩm ' . $value->name . ' ' . $attributes_name,
                ]);
            }
            $weight = ($num * $value->weight);
            $total_product = $price_sell * $num;
            $total_cost = $price_cost * $num;
            /*tính tiền thuế*/
            $total_vat = 0;
            if ($value->tax_value > 0) {
                switch ($value->tax_type) {
                    case $this->productRepository::VAT_TYPE_PERCENT:
                        $total_vat += ceil($total_product * $value->tax_value / 100);
                        break;
                    case $this->productRepository::VAT_TYPE_FIAT:
                        $total_vat += $num * $value->tax_value;
                        break;
                }
            }
            if ($value->vat_value > 0) {
                switch ($value->vat_type) {
                    case $this->productRepository::VAT_TYPE_PERCENT:
                        $total_vat += ceil($total_product * $value->vat_value / 100);
                        break;
                    case $this->productRepository::VAT_TYPE_FIAT:
                        $total_vat += $num * $value->vat_value;
                        break;
                }
            }

            $total_discount = 0;
            if ($value->flash_sale) {
                $flash_sale = $value->flash_sale;
                switch ($flash_sale->discount_type) {
                    case $this->flashSaleProductRepository::DISCOUNT_TYPE_PERCENT:
                        $total_discount += floor($total_product * $flash_sale->discount_value / 100);
                        break;
                    case $this->flashSaleProductRepository::DISCOUNT_TYPE_FIAT:
                        $total_discount += $num * $flash_sale->discount_value;
                        break;
                }
            }
            $total_profit = $total_product - $total_discount + $total_vat - $total_cost;

            $data_product[] = [
                'user_id' => auth()->id(),
                'product_id' => $value->id,
                'category_id' => $value->category_id,
                'product_name' => $value->name,
                'product_sku' => $value->sku,
                'product_image' => $value->avatar,
                'attributes_name' => $stock->attributes_name,
                'product_stock_id' => $stock->id,
                'num' => $num,
                'price' => $price_sell,
                'price_cost' => $price_cost,
                'price_discount' => $price_sell - ($total_discount / $num),
                'total_product' => $total_product,
                'total_discount' => $total_discount,
                'total_vat' => $total_vat,
                'total_profit' => $total_profit,
                'total_cost' => $total_cost,
                'total' => $total_product - $total_discount + $total_vat,
                'created_time' => $created_time,
                'status' => $data_order['status'],
                'status_payment' => $data_order['status_payment'],
                'payment_time' => $data_order['payment_time'],
                'weight' => $weight
            ];
            $total_order_product += $total_product;
            $data_order['total_product'] += $total_product;
            $data_order['total_discount'] += $total_discount;
            $data_order['total_discount_product'] += $total_discount;
            $data_order['total_vat'] += $total_vat;
            $data_order['total_profit'] += $total_profit;
            $data_order['total_cost'] += $total_cost;
            $data_order['total'] += $total_product - $total_discount + $total_vat;
        }

        // xu ly ma giam gia
        $coupons = $request->coupons ?? [];
        if ($coupons) {
            $where = [
                'code' => ['code', 'whereIn', $coupons],
                'status' => $this->couponRepository::STATUS_ACTIVE,
            ];
            $data_coupon = $this->couponRepository->get($where);
            $data_coupon_ids = $data_coupon->pluck('code')->toArray();
            $hide_coupon_ids = array_diff($coupons, $data_coupon_ids);
            if ($hide_coupon_ids) {
                return response([
                    'result' => false,
                    'message' => 'Mã giảm giá không đúng',
                    'ids' => $hide_coupon_ids
                ]);
            }
            $data_discount = [];
            foreach ($data_coupon as $dt) {
                if ($dt->num > 0) {
                    if ($dt->num_used >= $dt->num) {
                        return response([
                            'result' => false,
                            'message' => 'Mã giảm giá ' . $dt->code . ' đã được sử dụng hết',
                        ]);
                    }
                }
                if ($dt->max_per_person > 0) {
                    $count = $this->orderCouponRepository->count([
                        'code' => $dt->code,
                        'user_id' => auth()->id()
                    ]);
                    if ($count >= $dt->max_per_person) {
                        return response([
                            'result' => false,
                            'message' => 'Mã giảm giá ' . $dt->code . ' đã được sử dụng',
                        ]);
                    }
                }
                $collection = collect($data_product);
                if ($dt->category_id > 0) {
                    $collection = $collection->where('category_id', $dt->category_id);
                }
                if ($dt->product_id > 0) {
                    $collection = $collection->where('product_id', $dt->product_id);
                }
                $total_order_product_coupon = $collection->sum('total_product');
                if ($dt->min_total_order > 0) {
                    if ($total_order_product_coupon < $dt->min_total_order) {
                        return response([
                            'result' => false,
                            'message' => 'Giá trị đơn hàng chưa đủ để sử dụng mã giảm giá ' . $dt->code,
                        ]);
                    }
                }
                $total_discount_coupon = 0;
                switch ($dt->discount_type) {
                    case $this->couponRepository::DISCOUNT_TYPE_PERCENT:
                        $total_discount_coupon = floor($total_order_product_coupon * $dt->discount_value / 100);
                        break;
                    case $this->couponRepository::DISCOUNT_TYPE_FIAT:
                        $total_discount_coupon += $dt->discount_value;
                        break;
                }
                $total_discount_coupon = ($total_discount_coupon <= $dt->discount_max_value) ? $total_discount_coupon : $dt->discount_max_value;

                $data_discount[] = [
                    'total' => $total_order_product_coupon,
                    'total_discount' => $total_discount_coupon,
                    'code' => $dt->code
                ];
//                dd($data_discount);
            }
            foreach ($data_discount as $dt) {
                $total_discount_coupon = floor($data_order['total_product'] * $dt['total_discount'] / $dt['total']);
                $data_order['total_discount_coupon'] += $total_discount_coupon;
                $data_order['total_discount'] += $total_discount_coupon;
                $data_order['total'] = ($data_order['total_product'] + $data_order['total_vat'] + $data_order['total_fee'] - $data_order['total_discount']);
                $data_order['coupons'][] = [
                    'user_id' => auth()->id(),
                    'code' => $dt['code'],
                    'coupon_value' => $total_discount_coupon
                ];
            }
        }

        DB::transaction(function () use ($data_order, $data_product,$coupons) {
            $order = $this->orderRepository->create($data_order);
            $order->details()->createMany($data_product);
//            if (isset($data_order['coupons'])) {
//                $order->coupons()->createMany($data_order['coupons']);
//            }

            //lực tính sl coupon đã dùng
            foreach ($coupons as $coupon_code) {
                $coupon = $this->couponRepository->first(['code' => $coupon_code]);
                $coupon->num_used += 1;
                $coupon->save();
            }
        });

        return response([
            'result' => true,
        ]);
    }

    public function logs($id)
    {
        $data = $this->orderRepository->first(['id' => $id, 'user_id' => auth()->id()]);
        if (!$data) {
            return response([
                'result' => false,
                'message' => 'Không tìm thấy thông tin đơn hàng'
            ]);
        }
        $logs = $data->logs;
        return response([
            'result' => true,
            'data' => $logs
        ]);
    }
    // đơn hàng của nhà hàng
    public function posOrder(Request $request){

    }
}
