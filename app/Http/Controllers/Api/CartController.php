<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Cart\CartRepository;
use App\Repositories\FlashSale\FlashSaleRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\ProductStock\ProductStockRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;


class CartController extends Controller
{
    public $cartRepository;
    public $productRepository;
    public $userRepository;
    public $productStockRepository;
    public $flashSaleRepository;

    public function __construct(CartRepository $cartRepository, ProductRepository $productRepository, UserRepository $userRepository, ProductStockRepository $productStockRepository, FlashSaleRepository $flashSaleRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->productRepository = $productRepository;
        $this->userRepository = $userRepository;
        $this->productStockRepository = $productStockRepository;
        $this->flashSaleRepository = $flashSaleRepository;
    }

//    public function index()
//    {
//        $data = $this->cartRepository->get([
//            'user_id' => auth()->id()
//        ]);
//        if ($data) {
//            $data->load('product.flash_sale');
//            foreach ($data as $key => $value) {
//                if ($value->product) {
//                    $total_discount_stock = 0;
//                    $flash_sale = $value->product->flash_sale ?? null;
//                    $stock = $this->productStockRepository->first([
//                        'product_id' => $value->product_id,
//                        'attributes_name' => $value->attributes_name,
//                    ]);
//                    if ($flash_sale) {
//                        switch ($flash_sale->discount_type) {
//                            case $this->flashSaleRepository::DISCOUNT_TYPE_PERCENT:
//                                $total_discount_stock = floor($stock->price_sell * $flash_sale->discount_value / 100);
//                                break;
//                            case $this->flashSaleRepository::DISCOUNT_TYPE_FIAT:
//                                $total_discount_stock = (int)$flash_sale->discount_value;
//                                break;
//                        }
//                    }
//                    $stock->price_sell = $stock->price_sell - $total_discount_stock;
//                    $value->stock = $stock;
//                } else {
//                    unset($data[$key]);
//                }
//            }
//        }
//        return response([
//            'result' => true,
//            'data' => $data->values()
//        ]);
//    }
    public function index()
    {
        $data = $this->cartRepository->get([
            'user_id' => auth()->id()
        ]);

        if ($data) {
            $data->load('product.flash_sale');

            foreach ($data as $key => $value) {
                if ($value->product) {
                    $total_discount_stock = 0;
                    $flash_sale = $value->product->flash_sale;
                    $stock = $this->productStockRepository->first([
                        'product_id' => $value->product_id,
                        'attributes_name' => $value->attributes_name,
                    ]);
                    if ($stock) {
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
                        $value->stock = $stock;
                    } else {
                        unset($data[$key]);
                    }
                } else {
                    unset($data[$key]);
                }
            }
        }

        return response([
            'result' => true,
            'data' => $data->values()
        ]);
    }


//    public function store(Request $request)
//    {
//        if (empty($request->product_id)) {
//            return response([
//                'result' => false,
//                'message' => 'Vui lòng thêm sản phẩm vào giỏ hàng'
//            ]);
//        }
//        $cart = $this->cartRepository->first([
//            'product_id' => $request->product_id,
//            'attributes_name' => $request->attributes_name ?? '',
//            'user_id' => auth()->id()
//        ]);
//        if ($cart) {
//            $this->cartRepository->increment([
//                'product_id' => $request->product_id,
//                'attributes_name' => $request->attributes_name ?? '',
//                'user_id' => auth()->id()
//            ], 'num', $request->num ?? 1);
//        } else {
//            $data = [
//                'product_id' => $request->product_id,
//                'attributes_name' => $request->attributes_name ?? '',
//                'num' => $request->num ?? 1,
//                'user_id' => auth()->id()
//            ];
//            $this->cartRepository->create($data);
//        }
//        return response([
//            'result' => true,
//        ]);
//    }

    public function store(Request $request)
    {
        // Xác thực rằng đầu vào là một mảng và mỗi phần tử trong mảng là một sản phẩm hợp lệ
        $request->validate([
            '*.product_id' => 'required|integer',
            '*.num' => 'required|integer|min:1',
            '*.attributes_name' => 'string|nullable'
        ]);
        foreach ($request->all() as $product) {
            // Kiểm tra nếu product_id trống
            if (empty($product['product_id'])) {
                continue;  // Bỏ qua sản phẩm này và tiếp tục với sản phẩm tiếp theo
            }

            // Tìm sản phẩm trong giỏ hàng
            $cart = $this->cartRepository->first([
                'product_id' => $product['product_id'],
                'attributes_name' => $product['attributes_name'] ?? '',
                'user_id' => auth()->id()
            ]);

            if ($cart) {
                // Tăng số lượng sản phẩm nếu sản phẩm đã tồn tại trong giỏ hàng
                $this->cartRepository->increment([
                    'product_id' => $product['product_id'],
                    'attributes_name' => $product['attributes_name'] ?? '',
                    'user_id' => auth()->id()
                ], 'num', $product['num']);
            } else {
                // Tạo mới sản phẩm trong giỏ hàng nếu chưa tồn tại
                $data = [
                    'product_id' => $product['product_id'],
                    'attributes_name' => $product['attributes_name'] ?? '',
                    'num' => $product['num'],
                    'user_id' => auth()->id()
                ];
                $this->cartRepository->create($data);
            }
        }

        // Trả về phản hồi chung
        return response()->json(['result' => true]);
    }


    public function update_num($id, Request $request)
    {
        $cart = $this->cartRepository->first([
            'id' => $id,
            'user_id' => auth()->id()
        ]);
        $this->cartRepository->edit($cart, [
            'num' => $request->num ?? 1
        ]);
        return response([
            'result' => true,
        ]);
    }

    public function remove($id)
    {
        $cart = $this->cartRepository->first([
            'id' => $id,
            'user_id' => auth()->id()
        ]);
        if ($cart) {
            $this->cartRepository->delete($cart);
        }
        return response([
            'result' => true,
        ]);
    }

    public function delete()
    {
        $this->cartRepository->deleteWhere([
            'user_id' => auth()->id()
        ]);
        return response([
            'result' => true,
        ]);
    }
}
