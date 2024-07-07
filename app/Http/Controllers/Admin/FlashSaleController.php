<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FlashSale\CreateRequest;
use App\Http\Requests\Admin\FlashSale\EditRequest;
use App\Models\Logs;
use App\Repositories\DiscountGroup\DiscountGroupRepository;
use App\Repositories\FlashSale\FlashSaleRepository;
use App\Repositories\FlashSaleProduct\FlashSaleProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FlashSaleController extends Controller
{
    private $flashSaleRepository;
    private $flashSaleProductRepository;

    public function __construct(FlashSaleRepository $flashSaleRepository,
                                FlashSaleProductRepository $flashSaleProductRepository,
    )
    {
        $this->flashSaleRepository = $flashSaleRepository;
        $this->flashSaleProductRepository = $flashSaleProductRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        /*if (!empty($request->category_id)) {
            $where['category_id'] = (int)$request->category_id;
        }*/

        $data = $this->flashSaleRepository->paginate($where, ['id' => 'DESC']);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách"]
        ];
        //$categories = $this->categoryRepository->tree();
        return view('admin.content.flash_sale.list')->with(compact('data', 'breadcrumbs'));
    }

    public function create()
    {
        //$categories = $this->categoryRepository->tree();
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.flash-sale.index'), 'name' => "Danh sách"], ['name' => 'Tạo mới']
        ];
        //$aryDiscountType = $this->flashSaleProductRepository->aryDiscountType;
        return view('admin.content.flash_sale.create')->with(compact('breadcrumbs'));
    }

    public function store(CreateRequest $request)
    {
        $aryTime = explode('to', $request->time);
        $data = [
            'title' => $request->title,
            'start_time' => !empty($aryTime[0]) ? strtotime($aryTime[0]) : 0,
            'end_time' => !empty($aryTime[1]) ? (strtotime($aryTime[1])) : (strtotime($aryTime[0])),
            'status' => $this->flashSaleRepository::STATUS_ACTIVE,
            'start_hour' => $request->start_hour ?? 0,
            'end_hour' => $request->end_hour ?? 24
        ];
        $image = $request->file('image');
        if ($image) {
            $data['image'] = Storage::putFileAs('flash-sale', $image, $image->getClientOriginalName());;
        }
        $products = $request->products;
        $discount_value = $request->discount_value;
        $discount_type = $request->discount_type;
        //$discount_group = $request->discount_group;
        $data_product = [];
        if($products){
            foreach ($products as $product_id){
                $data_product[] = [
                    'product_id' => $product_id,
                    //'group_discount_id' => $discount_group[$product_id] ?? 0,
                    'discount_value' => $discount_value[$product_id] ?? 0,
                    'discount_type' => $discount_type[$product_id] ?? $this->flashSaleProductRepository::DISCOUNT_TYPE_FIAT,
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'start_hour' => $data['start_hour'],
                    'end_hour' => $data['end_hour'],
                    'status' => $data['status'],
                ];
            }
        }
        DB::transaction(function () use ($data, $data_product){
            $flash = $this->flashSaleRepository->create($data);
            //$flash->products()->delete();
            $flash->products()->createMany($data_product);
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'create_flash_sale',
                'item_id' => $flash->id,
                'data' => [
                    'data' => $data,
                    'data_product' => $data_product
                ]
            ]);
        });
        return redirect()->route('admin.flash-sale.index');
    }

    public function edit($id)
    {
        //$categories = $this->categoryRepository->tree();
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.flash-sale.index'), 'name' => "Danh sách"], ['name' => 'Sửa']
        ];
        $data = $this->flashSaleRepository->find($id);
        $data->load('products.product');
        //$aryDiscountType = $this->flashSaleProductRepository->aryDiscountType;
        //$discount_groups = $this->discountGroupRepository->all()->toArray();
        return view('admin.content.flash_sale.edit')->with(compact('breadcrumbs', 'data'));
    }

    public function update($id, EditRequest $request)
    {
        $flash = $this->flashSaleRepository->find($id);

        $aryTime = explode('to', $request->time);
        $data = [
            'title' => $request->title,
            'start_time' => !empty($aryTime[0]) ? strtotime($aryTime[0]) : 0,
            'end_time' => !empty($aryTime[1]) ? (strtotime($aryTime[1])) : (strtotime($aryTime[0])),
            'start_hour' => $request->start_hour ?? 0,
            'end_hour' => $request->end_hour ?? 24
        ];
        $image = $request->file('image');
        if ($image) {
            $data['image'] = Storage::putFileAs('flash-sale', $image, $image->getClientOriginalName());;
        }
        $products = $request->products;
        $discount_value = $request->discount_value;
        $discount_type = $request->discount_type;
        //$discount_group = $request->discount_group;
        $data_product = [];
        if($products){
            foreach ($products as $product_id){
                $data_product[] = [
                    'product_id' => $product_id,
                    //'group_discount_id' => $discount_group[$product_id] ?? 0,
                    'discount_value' => $discount_value[$product_id] ?? 0,
                    'discount_type' => $discount_type[$product_id] ?? $this->flashSaleProductRepository::DISCOUNT_TYPE_FIAT,
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                    'start_hour' => $data['start_hour'],
                    'end_hour' => $data['end_hour'],
                    'status' => $flash->status
                ];
            }
        }
        DB::transaction(function () use ($flash, $data, $data_product){
            $flash = $this->flashSaleRepository->edit($flash, $data);
            $flash->products()->delete();
            $flash->products()->createMany($data_product);
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'edit_flash_sale',
                'item_id' => $flash->id,
                'data' => [
                    'data' => $data,
                    'data_product' => $data_product
                ]
            ]);
        });
        return redirect()->route('admin.flash-sale.index');
    }

    public function show()
    {

    }

    public function update_status($id, Request $request)
    {
        $flash = $this->flashSaleRepository->find($id);
        $status = (int)$request->status;
        DB::transaction(function () use ($flash, $status) {
            $this->flashSaleRepository->edit($flash, [
                'status' => $status
            ]);
            $this->flashSaleProductRepository->editWhere([
                'flash_sale_id' => $flash->id
            ], [
                'status' => $status
            ]);
        });
        return response(['result' => true]);
    }

    public function update_home($id, Request $request)
    {
        $flash = $this->flashSaleRepository->find($id);
        $home = (int)$request->home;
        $this->flashSaleRepository->edit($flash, [
            'home' => $home
        ]);
        return response(['result' => true]);
    }

    /*public function delete($id)
    {
        $package = $this->bannerRepository->find($id);
        $this->bannerRepository->delete($package);
        return response(['result' => true]);
    }*/
}
