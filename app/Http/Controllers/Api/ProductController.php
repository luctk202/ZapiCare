<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\FilterAttributeProduct\FilterAttributeProductRepository;
use App\Repositories\FlashSale\FlashSaleRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\ProductCategory\ProductCategoryRepository;
use App\Repositories\ProductDiscount\ProductDiscountRepository;
use App\Repositories\ProductProvince\ProductProvinceRepository;
use App\Repositories\ProductStock\ProductStockRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public $productRepository;
    public $productStockRepository;
    public $productDiscountRepository;
    public $productCategoryRepository;
    public $userRepository;
    public $filterAttributeProductRepository;
    public $productProvinceRepository;
    public $flashSaleRepository;

    public function __construct(ProductRepository                $productRepository,
                                ProductStockRepository           $productStockRepository,
                                ProductDiscountRepository        $productDiscountRepository,
                                ProductCategoryRepository        $productCategoryRepository,
                                FilterAttributeProductRepository $filterAttributeProductRepository,
                                ProductProvinceRepository        $productProvinceRepository,
                                FlashSaleRepository              $flashSaleRepository,
                                UserRepository                   $userRepository)
    {
        $this->productRepository = $productRepository;
        $this->productStockRepository = $productStockRepository;
        $this->productDiscountRepository = $productDiscountRepository;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->filterAttributeProductRepository = $filterAttributeProductRepository;
        $this->productProvinceRepository = $productProvinceRepository;
        $this->userRepository = $userRepository;
        $this->flashSaleRepository = $flashSaleRepository;
    }

    public function index(Request $request)
    {
        $where = [
            'status' => $this->productRepository::STATUS_SHOW,
            'approval' => $this->productRepository::APPROVAL_DONE
        ];
        if (!empty($request->ids)) {
            $where['id'] = ['id', 'whereIn', $request->ids];
        }
        if (!empty($request->filters)) {
            $ids = $this->filterAttributeProductRepository->pluckWhere(['filter_attribute_id' => ['filter_attribute_id', 'whereIn', $request->filters]], 'product_id')->toArray();
            $where['filter_id'] = ['id', 'whereIn', $ids];
        }
        if (!empty($request->category_id)) {
            $ids = array_merge($this->productCategoryRepository->get_child_id($request->category_id), [(int)$request->category_id]);
            $where['category_id'] = ['category_id', 'whereIn', $ids];
        }
        if (!empty($request->province_id)) {
            $ids = $this->productProvinceRepository->pluckWhere(['province_id' => ['province_id', 'whereIn', [0, $request->province_id]]], 'product_id')->toArray();
            $where['province_id'] = ['id', 'whereIn', $ids];
        }
        if (!empty($request->shop_id)) {
            $where['shop_id'] = (int)$request->shop_id;
        }
        if (!empty($request->brand_id)) {
            $where['brand_id'] = (int)$request->brand_id;
        }
        if (!empty($request->typical)) {
            $where['typical'] = (int)$request->typical;
        }
        if (!empty($request->search)) {
            $where['orWhere'] = [['name', 'like', $request->search], ['barcode', 'like', $request->search], ['sku', 'like', $request->search]];
        }
        if (!empty($request->price_min)) {
            $where['price_min'] = ['price_sell', '>=', $request->price_min];
        }
        if (!empty($request->price_max)) {
            $where['price_max'] = ['price_sell', '<=', $request->price_max];
        }
        $orderBy = [];
        if (!empty($request->sortBy)) {
            $orderBy[$request->sortBy] = $request->orderType ?? 'DESC';
        }
        $data = $this->productRepository->paginate($where, $orderBy, [], [], (int)($request->limit ?? 50));
        if ($data) {
            $data->load('stocks', 'category', 'flash_sale', 'shop');
            foreach ($data as $key => $value) {
                $stocks = $value->stocks;
                $flash_sale = $value->flash_sale;
                $total_discount = 0;
                if ($flash_sale) {
                    switch ($flash_sale->discount_type) {
                        case $this->flashSaleRepository::DISCOUNT_TYPE_PERCENT:
                            $total_discount = floor($value->price_sell * $flash_sale->discount_value / 100);
                            break;
                        case $this->flashSaleRepository::DISCOUNT_TYPE_FIAT:
                            $total_discount = (int)$flash_sale->discount_value;
                            break;
                    }
                }
                $value->price_sell = $value->price_sell - $total_discount;
                foreach ($stocks as $stock) {
                    $total_discount_stock = 0;
                    if ($flash_sale) {
                        switch ($flash_sale->discount_type) {
                            case $this->flashSaleRepository::DISCOUNT_TYPE_PERCENT:
                                $total_discount_stock = floor($stock->price_sell * $flash_sale->discount_value / 100);
                                break;
                            case $this->flashSaleRepository::DISCOUNT_TYPE_FIAT:
                                $total_discount_stock = (int)$flash_sale->discount_value;
                                break;
                        }
                    }
                    $stock->price_sell = $stock->price_sell - $total_discount_stock;
                }
                $value->stocks = $stocks;

                // Giải mã chuỗi JSON để trả về mảng tag labels
                $value->tag_label_ids = json_decode($value->tag_label_ids, true);
            }
        }
        return response([
            'result' => true,
            'data' => $data
        ]);
    }


    public function show($id)
    {
        $where = [
            'id' => $id,
            'status' => $this->productRepository::STATUS_SHOW,
            'approval' => $this->productRepository::APPROVAL_DONE
        ];
        $data = $this->productRepository->first($where);
        if ($data) {
            $data->load('stocks', 'category', 'brand', 'flash_sale', 'shop');
            $flash_sale = $data->flash_sale;
            $total_discount = 0;
            if ($flash_sale) {
                switch ($flash_sale->discount_type) {
                    case $this->flashSaleRepository::DISCOUNT_TYPE_PERCENT:
                        $total_discount = floor($data->price_sell * $flash_sale->discount_value / 100);
                        break;
                    case $this->flashSaleRepository::DISCOUNT_TYPE_FIAT:
                        $total_discount = (int)$flash_sale->discount_value;
                        break;
                }
            }
            $data->price_sell = $data->price_sell - $total_discount;
            $stocks = $data->stocks;
            foreach ($stocks as $stock) {
                $total_discount_stock = 0;
                if ($flash_sale) {
                    switch ($flash_sale->discount_type) {
                        case $this->flashSaleRepository::DISCOUNT_TYPE_PERCENT:
                            $total_discount_stock = floor($stock->price_sell * $flash_sale->discount_value / 100);
                            break;
                        case $this->flashSaleRepository::DISCOUNT_TYPE_FIAT:
                            $total_discount_stock = (int)$flash_sale->discount_value;
                            break;
                    }
                }
                $stock->price_sell = $stock->price_sell - $total_discount_stock;
            }
            $data->stocks = $stocks;
        }
        return response([
            'result' => true,
            'data' => $data
        ]);
    }

    public function suggest(Request $request)
    {
        $user = null;
        if (auth()->id()) {
            $user = $this->userRepository->first([
                'id' => auth()->id(),
            ]);
        }
        if ($user) {
            $ffd_group = $this->FFDGroupRepository->first(['user_id' => $user->id]);
        }
        if ($user && ($user->hkt_province_id > 0 || !empty($ffd_group->id))) {
            $where = [
                'type' => $this->productRepository::TYPE_RETAIL,
                'status' => $this->productRepository::STATUS_SHOW,
                'status_group' => $this->productRepository::STATUS_GROUP_SHOW,
                //'status_website' => $this->productRepository::STATUS_WEB_SHOW
            ];
        } else {
            $where = [
                'type' => $this->productRepository::TYPE_RETAIL,
                'status' => $this->productRepository::STATUS_SHOW,
                'status_website' => $this->productRepository::STATUS_WEB_SHOW
            ];
        }
        if (!empty($request->category_id)) {
            $ids = array_merge($this->productCategoryRepository->get_child_id($request->category_id), [(int)$request->category_id]);
            $where['category_id'] = ['category_id', 'whereIn', $ids];
        }
        if (!empty($request->brand_id)) {
            $where['brand_id'] = (int)$request->brand_id;
        }
        $orderBy = [
            'updated_at' => 'DESC'
        ];
        $data = $this->productRepository->paginate($where, $orderBy, [], [], (int)($request->limit ?? 50));
        if ($data) {
            $data->load('category', 'brand', 'flash_sale');
            if (auth()->id() || $request->references_code) {
                $ctv = null;
                if ($request->references_code) {
                    $ctv = $this->userRepository->first([
                        'references_code' => $request->references_code,
                        'type' => $this->userRepository::TYPE_CTV
                    ]);
                }
                $discounts = [];
                if ($user && $user->type == $this->userRepository::TYPE_CTV) {
                    $discounts = $this->affiliateSettingRepository->get(['group_id' => $user->group_affiliate_id, 'child_id' => $user->group_affiliate_id])->pluck(null, 'group_discount_id')->toArray();
                }
                if ($ctv) {
                    $group_affiliate_id = $this->affiliateGroupRepository->smallest($ctv->group_affiliate_id);
                    $discounts = $this->affiliateSettingRepository->get(['group_id' => $group_affiliate_id, 'child_id' => $group_affiliate_id])->pluck(null, 'group_discount_id')->toArray();
                }
                foreach ($data as $key => $value) {
                    $total_discount_group = 0;
                    $group_discount_id = ($value->flash_sale) ? $value->flash_sale->group_discount_id : $value->group_discount_id;
                    if (isset($discounts[$group_discount_id])) {
                        $discount = $discounts[$group_discount_id];
                        switch ($discount['discount_type']) {
                            case $this->affiliateSettingRepository::TYPE_PERCENT:
                                $total_discount_group = floor($value->price_sell * $discount['discount_value'] / 100);
                                break;
                            case $this->affiliateSettingRepository::TYPE_FIAT:
                                $total_discount_group = (int)$discount['discount_value'];
                                break;
                        }
                    }
                    $value->price_sell_user = $value->price_sell - $total_discount_group;
                    $stocks = $value->stocks;
                    foreach ($stocks as $stock) {
                        $total_stock_discount_group = 0;
                        if (isset($discounts[$group_discount_id])) {
                            $discount = $discounts[$group_discount_id];
                            switch ($discount['discount_type']) {
                                case $this->affiliateSettingRepository::TYPE_PERCENT:
                                    $total_stock_discount_group = floor($stock->price_sell * $discount['discount_value'] / 100);
                                    break;
                                case $this->affiliateSettingRepository::TYPE_FIAT:
                                    $total_stock_discount_group = (int)$discount['discount_value'];
                                    break;
                            }
                        }
                        $stock->price_sell_user = $total_stock_discount_group;
                    }
                    $value->stocks = $stocks;
                }
            }
        }
        return response([
            'result' => true,
            'data' => $data
        ]);
    }
}
