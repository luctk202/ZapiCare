<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\FlashSale\FlashSaleRepository;
use App\Repositories\FlashSaleProduct\FlashSaleProductRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\ProductCategory\ProductCategoryRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public $flashSaleRepository;
    public $flashSaleProductRepository;
    public $productRepository;
    public $productCategoryRepository;
    public $userRepository;

    public function __construct(FlashSaleRepository        $flashSaleRepository,
                                FlashSaleProductRepository $flashSaleProductRepository,
                                ProductRepository          $productRepository,
                                ProductCategoryRepository  $productCategoryRepository,
                                UserRepository             $userRepository)
    {
        $this->flashSaleRepository = $flashSaleRepository;
        $this->flashSaleProductRepository = $flashSaleProductRepository;
        $this->productRepository = $productRepository;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $where = [
            'status' => $this->flashSaleRepository::STATUS_ACTIVE,
            'start_time' => ['start_time', '<=', time()],
            'end_time' => ['end_time', '>=', time()],
        ];
        if (empty($request->shop_id)) {
            $where['home'] = $this->flashSaleRepository::HOME;
        }
        $data = $this->flashSaleRepository->get($where);
        if ($data) {
            $data->load('products.product');
            foreach ($data as $dt) {
                $products = $dt->products;
                $data_product = [];
                foreach ($products as $product) {
                    if (empty($request->shop_id) || ($product->product->shop_id == $request->shop_id)) {
                        $data_product[] = $product->product;
                    }
                }
                $dt->products = $data_product;
            }
        }
        return response([
            'result' => true,
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $flash = $this->flashSaleRepository->first([
            'id' => $id,
            'status' => $this->flashSaleRepository::STATUS_ACTIVE,
        ]);
        if (!$flash) {
            return response([
                'result' => false,
                'message' => 'Không tìm thấy thông tin chương trình'
            ]);
        }
        $flash->load('products.product');
        $products = $flash->products;
        $data_product = [];
        foreach ($products as $product) {
            if (empty($request->shop_id) || ($product->product->shop_id == $request->shop_id)) {
                $data_product[] = $product->product;
            }
        }
        $flash->products = $data_product;
        return response([
            'result' => true,
            'data' => $flash
        ]);
    }

}
