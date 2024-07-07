<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Jobs\PaymentOrder;
use App\Jobs\UpdateWalletZone;
use App\Models\Export;
use App\Models\ExportDetail;
use App\Models\ExportLog;
use App\Models\ExportProduct;
use App\Models\Order;
use App\Models\OrderProfit;
use App\Models\User;
use App\Models\WalletLog;
use App\Repositories\AffiliateGroup\AffiliateGroupRepository;
use App\Repositories\AffiliateSetting\AffiliateSettingRepository;
use App\Repositories\DeliveryGroup\DeliveryGroupRepository;
use App\Repositories\DeliveryGroupSetting\DeliveryGroupSettingRepository;
use App\Repositories\Order\OrderRepository;
use App\Repositories\OrderProfit\OrderProfitRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\WalletLog\WalletLogRepository;
use App\Repositories\Shop\ZoneRepository;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function index(OrderRepository            $orderRepository,
                           UserRepository             $userRepository,
                           AffiliateGroupRepository   $affiliateGroupRepository,
                           AffiliateSettingRepository $affiliateSettingRepository,
                           OrderProfitRepository      $orderProfitRepository,
                           ZoneRepository      $zoneRepository,
                           DeliveryGroupSettingRepository      $deliveryGroupSettingRepository,
                           DeliveryGroupRepository      $deliveryGroupRepository
    )
    {
        $order_id = 100569;
        $order = $orderRepository->find($order_id);
        $details = $order->details;

        $data = [];
        if ($order->affiliate_id > 0) {
            $user_affiliate = $userRepository->find($order->affiliate_id);
            $data = [
                'user_id' => $order->affiliate_id,
                'group_affiliate_id' => $user_affiliate->group_affiliate_id,
                'child_id' => $affiliateGroupRepository->smallest($user_affiliate->group_affiliate_id)
            ];
        }
        if ($order->user_id > 0) {
            $user_child = $userRepository->find($order->user_id);
            if ($user_child->parent_id) {
                $user_affiliate = $userRepository->find($user_child->parent_id);
                $data = [
                    'user_id' => $user_affiliate->id,
                    'group_affiliate_id' => $user_affiliate->group_affiliate_id,
                    'child_id' => $user_child->group_affiliate_id
                ];
            }
        }
        $parent_data = [];
        if ($data) {
            $this->parents($data['user_id'], $parent_data);
        }
        if($data){
            array_push($parent_data, $data);
        }


        $ward_zone = $zoneRepository->first(['ward_id' => $order->ward_id]);
        $user_ward_zone_id = $ward_zone->user_id ?? 0;
        $user_global_ward_zone_id = $ward_zone->user_develop_id ?? 0;
        $district_zone = $zoneRepository->first(['district_id' => $order->district_id, 'ward_id' => 0]);
        $user_district_zone_id = $district_zone->user_id ?? 0;
        $user_global_district_zone_id = $district_zone->user_develop_id ?? 0;

        $total_discount_ward = 0;
        $total_discount_district = 0;
        $total_discount_global = 0;

        foreach ($details as $detail) {
            $group_discount_id = $detail->group_discount_id;

            $discount_ward = $deliveryGroupSettingRepository->first(['group_discount_id' => $group_discount_id, 'type' => $deliveryGroupRepository::TYPE_WARD]);
            if ($discount_ward) {
                switch ($discount_ward->discount_type) {
                    case $deliveryGroupSettingRepository::TYPE_PERCENT:
                        $total_discount_ward += floor($detail->total_product * $discount_ward->discount_value / 100);
                        break;
                    case $deliveryGroupSettingRepository::TYPE_FIAT:
                        $total_discount_ward += ($detail->num * $discount_ward->discount_value);
                        break;
                }
            }
            $discount_district = $deliveryGroupSettingRepository->first(['group_discount_id' => $group_discount_id, 'type' => $deliveryGroupRepository::TYPE_DISTRICT]);
            if ($discount_district) {
                switch ($discount_district->discount_type) {
                    case $deliveryGroupSettingRepository::TYPE_PERCENT:
                        $total_discount_district += floor($detail->total_product * $discount_district->discount_value / 100);
                        break;
                    case $deliveryGroupSettingRepository::TYPE_FIAT:
                        $total_discount_district += ($detail->num * $discount_district->discount_value);
                        break;
                }
            }
            $discount_global = $deliveryGroupSettingRepository->first(['group_discount_id' => $group_discount_id, 'type' => $deliveryGroupRepository::TYPE_GLOBAL]);
            dump($discount_global);
            if ($discount_global) {
                switch ($discount_global->discount_type) {
                    case $deliveryGroupSettingRepository::TYPE_PERCENT:
                        $total_discount_global += floor($detail->total_product * $discount_global->discount_value / 100);
                        break;
                    case $deliveryGroupSettingRepository::TYPE_FIAT:
                        $total_discount_global += ($detail->num * $discount_global->discount_value);
                        break;
                }
                dump($total_discount_global);
            }

        }
        dump($total_discount_global);
        dump($parent_data);
        if ($parent_data) {
            foreach ($parent_data as $parent) {
                $total_discount_group = 0;
                $discounts = $affiliateSettingRepository->get(['group_id' => $parent['group_affiliate_id'], 'child_id' => $parent['child_id']])->pluck(null, 'group_discount_id')->toArray();
                foreach ($details as $detail) {
                    $group_discount_id = $detail->group_discount_id;
                    $discount = $discounts[$group_discount_id];
                    dump($discount);
                    switch ($discount['discount_type']) {
                        case $affiliateSettingRepository::TYPE_PERCENT:
                            $total_discount_group += floor($detail->total_product * $discount['discount_value'] / 100);
                            break;
                        case $affiliateSettingRepository::TYPE_FIAT:
                            $total_discount_group += (int)($detail->num * $discount['discount_value']);
                            break;
                    }
                }
                dump($total_discount_group);
                /*if ($total_discount_group > 0) {
                    $data_order_profit = [
                        'user_id' => $parent['user_id'],
                        'order_id' => $order->id,
                        'amount' => $total_discount_group,
                        'type' => $orderProfitRepository::TYPE_ONLINE,
                        'created_time' => time()
                    ];
                    $orderProfitRepository->create($data_order_profit);
                }*/
            }
        }
        dd(1);
        if ($user_ward_zone_id > 0 && $total_discount_ward > 0) {
            $data_ward_profit = [
                'user_id' => $user_ward_zone_id,
                'order_id' => $order->id,
                'amount' => $total_discount_ward,
                'type' => $orderProfitRepository::TYPE_OFFLINE,
                'created_time' => time()
            ];
            $orderProfitRepository->create($data_ward_profit);
        }

        if ($user_district_zone_id > 0 && $total_discount_district > 0) {
            $data_district_profit = [
                'user_id' => $user_district_zone_id,
                'order_id' => $order->id,
                'amount' => $total_discount_district,
                'type' => $orderProfitRepository::TYPE_OFFLINE,
                'created_time' => time()
            ];
            $orderProfitRepository->create($data_district_profit);
        }
        if ($user_global_ward_zone_id > 0 && $total_discount_global > 0) {
            $data_global_ward_profit = [
                'user_id' => $user_global_ward_zone_id,
                'order_id' => $order->id,
                'amount' => $total_discount_global,
                'type' => $orderProfitRepository::TYPE_DEVELOP,
                'created_time' => time()
            ];
            $orderProfitRepository->create($data_global_ward_profit);
        }
        if ($user_global_district_zone_id > 0 && $total_discount_global > 0) {
            $data_global_district_profit = [
                'user_id' => $user_global_district_zone_id,
                'order_id' => $order->id,
                'amount' => $total_discount_global,
                'type' => $orderProfitRepository::TYPE_DEVELOP,
                'created_time' => time()
            ];
            $orderProfitRepository->create($data_global_district_profit);
        }
        //dd($parent_data);
        DB::transaction(function () use ($parent_data, $order, $details, $affiliateSettingRepository, $orderProfitRepository, $user_ward_zone_id, $total_discount_ward, $user_district_zone_id, $total_discount_district, $user_global_ward_zone_id, $user_global_district_zone_id, $total_discount_global) {
        });
    }

    public function parents($user_id, &$parent_data)
    {
        $user = User::find($user_id);
        if ($user->parent_id > 0) {
            $parent = User::find($user->parent_id);
            $parent_data[] = [
                'user_id' => $parent->id,
                'group_affiliate_id' => $parent->group_affiliate_id,
                'child_id' => $user->group_affiliate_id
            ];
            $this->parents($parent->id, $parent_data);
        }
    }


    public function update_wallet(ZoneRepository $zoneRepository, DeliveryGroupSettingRepository $deliveryGroupSettingRepository, DeliveryGroupRepository $deliveryGroupRepository){
        $order_ids = OrderProfit::pluck('order_id')->toArray();
        $order_ids = array_values(array_unique($order_ids));
        //dd($order_ids);
        //$order_ids = Order::whereIn('id', $order_ids)->where('status_payment', OrderRepository::STATUS_PAYMENT_PAID)->where('status', OrderRepository::STATUS_DONE)->pluck('id');
        //dd($order_ids);
        foreach ($order_ids as $order_id){
            UpdateWalletZone::dispatch($order_id)->onQueue('default');
        }
        dd($order_ids);
    }

        public function update_payment(){
        $order_ids = Order::where('status', OrderRepository::STATUS_DONE)->where('status_payment', OrderRepository::STATUS_PAYMENT_UNPAID)->pluck('id')->toArray();
        foreach ($order_ids as $order_id){
            $order = Order::find($order_id);
            $order->status_payment = OrderRepository::STATUS_PAYMENT_PAID;
            $order->payment_time = $order->export_time;
            $order->payment_id = $order->export_id;
            $order->save();
            PaymentOrder::dispatch($order_id)->onQueue('order');
        }
        dd($order_id);
    }

    public function update_cancel(){
        $orders = Order::where('status', OrderRepository::STATUS_CANCEL)->where('handle_id', '>', 0)->where('total_cost', '>', 0)->get();
        foreach ($orders as $order){
            $wallet_logs = WalletLog::where('order_id', $order->id)->where('user_id', $order->handle_id)->where('user_id', $order->handle_id)->where('type', WalletLogRepository::TYPE_CREDIT)->where('reason_type', WalletLogRepository::REASON_TYPE_HANDLE)->first();
            $wallet_logs->amount = $order->total - $order->total_cost;
            $wallet_logs->new_value = $wallet_logs->new_value - $order->total_cost;
            $wallet_logs->save();
            $user = User::find( $order->handle_id);
            $user->balance = $user->balance - $order->total_cost;
            $user->save();
            dump($order->id);
        }
    }

    public function update_export(){
        /*$orders = Order::where('status', OrderRepository::STATUS_DONE)->where('status_payment', OrderRepository::STATUS_PAYMENT_PAID)->whereIn('payment_method', [2, 3, 5])->where('total_cost', '>', 0)->where('handle_id', '>', 0)->get();
        foreach ($orders as $order){
            $details = $order->details;
            foreach ($details as $detail){
                $export_log = ExportLog::where('order_id', $order->id)->where('user_id', $order->handle_id)->where('product_id', $detail->product_id)->where('product_stock_id', $detail->product_stock_id)->first();
                $export_product = ExportProduct::where('user_id', $order->handle_id)->where('product_id', $detail->product_id)->where('product_stock_id', $detail->product_stock_id)->first();
                $user  = User::find($order->handle_id);
                DB::transaction(function () use ($user, $export_product, $export_log){
                    $user->total_cost = $user->total_cost - ($export_log->price_cost * $export_log->num);
                    $user->save();
                    $export_product->total_cost = $export_product->total_cost - ($export_log->price_cost * $export_log->num);
                    $export_product->save();
                    $export_log->price_cost = 0;
                    $export_log->save();
                });
            }
        }*/

        /*$orders = Order::where('status', OrderRepository::STATUS_DONE)->where('status_payment', OrderRepository::STATUS_PAYMENT_PAID)->where('total_cost', '>', 0)->where('handle_id', '>', 0)->get();
        $i = 0;
        foreach ($orders as $order){
            $export_log = ExportLog::where('user_id', $order->handle_id)->select(DB::raw('SUM(price_cost * num) as total_sum'))->get();
            $total_cost = $export_log[0]->total_sum;
            //$export_product = ExportProduct::where('user_id', $order->handle_id)->where('product_id', $detail->product_id)->where('product_stock_id', $detail->product_stock_id)->first();
            $user = User::find($order->handle_id);
            $export_total = Export::where('user_id', $order->handle_id)->sum('total_cost');
            $total = $total_cost - ($user->total_cost + $export_total);
            if($total != 0){
                dump($user->id);
            }
        }*/
        $exports = Export::where('total_cost', '>', 0)->get();
        foreach ($exports as $export){
            DB::transaction(function () use ($export){
                $details = $export->details;
                $user = User::find($export->user_id);
                $total_cost_user = 0;
                foreach ($details as $detail){
                    $export_product = ExportProduct::where('user_id', $export->user_id)->where('product_id', $detail->product_id)->where('product_stock_id', $detail->product_stock_id)->first();
                    $total = $export_product->total_cost + $detail->total_cost;
                    $total_num = $export_product->num + $detail->num;
                    if ($export_product->num == 0) {
                        $detail->total_cost = $total;
                        $detail->save();
                        $export_product->total_cost = 0;
                        $export_product->save();
                    } else {
                        $price_cost = (int)($total/$total_num);
                        $total_cost = ($price_cost * $detail->num);
                        $detail->total_cost = $total_cost;
                        $detail->save();
                        $export_product->total_cost = $total - $total_cost;
                        $export_product->save();
                        $total_cost_user += ($total - $total_cost);
                    }
                }
                $user->total_cost = $total_cost_user;
                $user->save();
            });
        }
        echo 'done';
    }

    public function update_ward(){
        $uri = 'https://connect-my.vnpost.vn/customer-partner/getAllCommune';
        $params = [

        ];
        $option['verify'] = false;
        $option['query'] = $params;
        $option['headers'] = [
            'token' => 'CCBM0a0WqkVPAvIDw3ph+wxWk6CKrWlsEsTrnGk+z5V+RxZhNxWbDKlmFYH17BUJu9zaduykVf5HOTp13XVxIyctvLxEIuRHfsBwdEI+lp7UgIEEPjj7IrFbQ91lcRk73H04bl5Bwegkb/oWBHzaYA=='
        ];
        $option['http_errors'] = false;
        $client = new Client();
        $response = $client->request("GET", $uri, $option);
        $result = json_decode($response->getBody(), true);
        dd($result);
    }

    public function convert_wallet(){
        $orders = Order::where('status', OrderRepository::STATUS_CANCEL)->where('handle_id', '>', 0)->where('id', '>', 105600)->get();
        foreach ($orders as $order){
            $debit = WalletLog::where('type', WalletLogRepository::TYPE_DEBIT)->where('reason_type', WalletLogRepository::REASON_TYPE_HANDLE)->where('order_id', $order->id)->where('user_id', $order->handle_id)->first();
            $credit = WalletLog::where('type', WalletLogRepository::TYPE_CREDIT)->where('reason_type', WalletLogRepository::REASON_TYPE_HANDLE)->where('order_id', $order->id)->where('user_id', $order->handle_id)->first();
            if($debit->amount != $credit->amount){
                /*if($debit->amount > $credit->amount){
                    $total = $debit->amount - $credit->amount;
                    DB::transaction(function () use ($credit, $debit, $total, $order){
                        $credit->amount = $debit->amount;
                        $credit->save();
                        User::where('id', $order->handle_id)->increment('balance', $total);
                    });
                }
                if($debit->amount < $credit->amount){
                    $total = $credit->amount - $debit->amount;
                    DB::transaction(function () use ($credit, $debit, $total, $order){
                        $credit->amount = $debit->amount;
                        $credit->save();
                        User::where('id', $order->handle_id)->decrement('balance', $total);
                    });
                }*/
                dump($debit->amount, $credit->amount);
            }
        }
        echo 'done';
    }

    public function update_profit(Request $request, OrderProfitRepository $orderProfitRepository, OrderRepository $orderRepository, WalletLogRepository $walletLogRepository, UserRepository $userRepository){
        //dd($request->id);
        $order = $orderRepository->find($request->id);
        $order_profits = $orderProfitRepository->get(['order_id' => $order->id]);
        DB::transaction(function () use ($order, $order_profits, $walletLogRepository, $orderProfitRepository, $userRepository) {
            if ($order_profits) {
                foreach ($order_profits as $value) {
                    $reason_type = 0;
                    $reason = '';
                    switch ($value->type) {
                        case $orderProfitRepository::TYPE_ONLINE:
                            $reason_type = $walletLogRepository::REASON_TYPE_AFFILIATE;
                            $reason = 'Hoa hồng thành viên đặt hàng';
                            break;
                        case $orderProfitRepository::TYPE_OFFLINE:
                            $reason_type = $walletLogRepository::REASON_TYPE_ZONE;
                            $reason = 'Hoa hồng độc quyền khu vực ' . $value->zone_name;
                            break;
                        case $orderProfitRepository::TYPE_DEVELOP:
                            $reason_type = $walletLogRepository::REASON_TYPE_DEVELOP;
                            $reason = 'Hoa hồng phát triển cửa hàng ' . $value->zone_name;
                            break;
                        case $orderProfitRepository::TYPE_FFD:
                            $reason_type = $walletLogRepository::REASON_FFD;
                            $reason = 'Hoa hồng hội khuyết tật ' . $value->zone_name;
                            break;
                    }
                    if ($value->type == $orderProfitRepository::TYPE_OFFLINE || $value->type == $orderProfitRepository::TYPE_DEVELOP || $value->type == $orderProfitRepository::TYPE_FFD) {
                        $userRepository->increment([
                            'id' => $value->user_id
                        ], 'balance', $value->amount);
                    }
                    if ($value->type == $orderProfitRepository::TYPE_ONLINE) {
                        $user = $userRepository->find($value->user_id);
                        $userRepository->edit($user, [
                            'total_revenue' => $user->total_revenue + $order->total_product,
                            'total_revenue_member' => $user->total_revenue_member + $order->total_product,
                            'balance' => $user->balance + $value->amount
                        ]);
                    }
                    $data_wallet_logs = [
                        'user_id' => $value->user_id,
                        'order_id' => $order->id,
                        'type' => $walletLogRepository::TYPE_CREDIT,
                        'amount' => $value->amount,
                        'created_time' => time(),
                        'reason_type' => $reason_type,
                        'reason' => $reason,
                        'new_value' => $userRepository->first(['id' => $value->user_id])->balance
                    ];
                    $walletLogRepository->create($data_wallet_logs);
                }
            }
        });
        echo $request->id;
    }
}
