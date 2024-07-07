<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Logs;
use App\Repositories\Coupon\CouponRepository;
use App\Repositories\ProductCategory\ProductCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public $couponRepository;
    public $categoryRepository;

    public function __construct(CouponRepository $couponRepository, ProductCategoryRepository $categoryRepository)
    {
        $this->couponRepository = $couponRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        if (!empty($request->code)) {
            $where['code'] = ['code', 'like', $request->code];
        }
        if (!empty($request->shop_id)) {
            $where['shop_id'] = (int)$request->shop_id;
        }
        $data = $this->couponRepository->paginate($where, ['id' => 'DESC']);
        $data->load('source');
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['name' => "Danh sách"]
        ];
        //$categories = $this->categoryRepository->tree();
        return view('admin.content.coupon.list')->with(compact('data', 'breadcrumbs'));
    }

    public function create()
    {
        $categories = $this->categoryRepository->tree();
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['link' => route('admin.coupon.index'), 'name' => "Danh sách"],
            ['name' => 'Tạo mới']
        ];
        $aryDiscountType = $this->couponRepository->aryDiscountType;
        return view('admin.content.coupon.create')->with(compact('breadcrumbs', 'categories', 'aryDiscountType'));
    }

    public function store(Request $request)
    {
        $code = $request->code;
        if (empty($code) || ($code && Str::length($code) < 3)) {
            return back()->withErrors([
                'code' => 'Vui lòng nhập mã giảm giá'
            ])->withInput();
        }
        $coupon = $this->couponRepository->first([
            'code' => $code
        ]);
        if ($coupon) {
            return back()->withErrors([
                'code' => 'Mã giảm giá đã tồn tại'
            ])->withInput();
        }
        $aryTime = explode('to', $request->time);
        $data = [
            'code' => $request->code,
//            'source_id' => (int)$request->source_id ?? 0,
            'discount_value' => (int)$request->discount_value ?? 0,
            'discount_type' => (int)$request->discount_type ?? 0,
            'discount_max_value' => (int)$request->discount_max_value ?? 0,
            'num' => (int)$request->num ?? 0,
            'max_per_person' => (int)$request->max_per_person ?? 0,
            'min_total_order' => (int)$request->min_total_order ?? 0,
            'concurrency' => (int)$request->concurrency ?? 0,
//            'shop_id' => (int)$request->shop_id ?? 0,
            'category_id' => (int)$request->category_id ?? 0,
            'product_id' => (int)$request->product_id ?? 0,
            'description' => $request->description ?? '',
        ];
        $data['start_time'] = !empty($aryTime[0]) ? strtotime($aryTime[0]) : 0;
        $data['end_time'] = !empty($aryTime[1]) ? (strtotime($aryTime[1])) : $data['start_time'];
        $data['status'] = $this->couponRepository::STATUS_ACTIVE;
        DB::transaction(function () use ($data) {
            $coupon = $this->couponRepository->create($data);
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'create_coupon',
                'item_id' => $coupon->id,
                'data' => $data
            ]);
        });
        return redirect()->route('admin.coupon.index');
    }

    public function edit($id)
    {
        $categories = $this->categoryRepository->tree();
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"],
            ['link' => route('admin.coupon.index'), 'name' => "Danh sách"],
            ['name' => 'Sửa']
        ];
        $data = $this->couponRepository->find($id);
        $aryDiscountType = $this->couponRepository->aryDiscountType;
        //$discount_groups = $this->discountGroupRepository->all()->toArray();
        return view('admin.content.coupon.edit')->with(compact('breadcrumbs', 'data', 'categories', 'aryDiscountType'));
    }

    public function update($id, Request $request)
    {
        $coupon = $this->couponRepository->find($id);

        $code = $request->code;

        if (empty($code) || ($code && Str::length($code) < 3)) {
            return back()->withErrors([
                'code' => 'Vui lòng nhập mã giảm giá'
            ])->withInput();
        }
        $old_coupon = $this->couponRepository->first([
            'code' => $code
        ]);
        if ($old_coupon && $old_coupon->code != $coupon->code) {
            return back()->withErrors([
                'code' => 'Mã giảm giá đã tồn tại'
            ])->withInput();
        }
        $aryTime = explode('to', $request->time);
        $data = [
            'code' => $request->code,
//            'source_id' => (int)$request->source_id ?? 0,
            'discount_value' => (int)$request->discount_value ?? 0,
            'discount_type' => (int)$request->discount_type ?? 0,
            'discount_max_value' => (int)$request->discount_max_value ?? 0,
            'num' => (int)$request->num ?? 0,
            'max_per_person' => (int)$request->max_per_person ?? 0,
            'min_total_order' => (int)$request->min_total_order ?? 0,
            'concurrency' => (int)$request->concurrency ?? 0,
//            'shop_id' => (int)$request->shop_id ?? 0,
            'category_id' => (int)$request->category_id ?? 0,
            'product_id' => (int)$request->product_id ?? 0,
            'description' => $request->description ?? '',
        ];
        $data['start_time'] = !empty($aryTime[0]) ? strtotime($aryTime[0]) : 0;
        $data['end_time'] = !empty($aryTime[1]) ? (strtotime($aryTime[1])) : $data['start_time'];
        DB::transaction(function () use ($coupon, $data) {
            $coupon = $this->couponRepository->edit($coupon, $data);
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'edit_coupon',
                'item_id' => $coupon->id,
                'data' => $data
            ]);
        });
        return redirect()->route('admin.coupon.index');
    }

    public function delete($id)
    {
        $admin = $this->couponRepository->find($id);
        $this->couponRepository->delete($admin);
        return response(['result' => true]);
    }
}
