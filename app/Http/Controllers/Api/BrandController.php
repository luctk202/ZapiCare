<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Brand\BrandRepository;
use App\Repositories\Product\ProductRepository;
use App\Repositories\ProductCategory\ProductCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public $brandRepository;
    public $productRepository;
    public $productCategoryRepository;

    public function __construct(BrandRepository $brandRepository, ProductRepository $productRepository, ProductCategoryRepository $productCategoryRepository)
    {
        $this->brandRepository = $brandRepository;
        $this->productRepository = $productRepository;
        $this->productCategoryRepository = $productCategoryRepository;
    }

    public function index(Request $request){
        $where = [
            'status' => $this->brandRepository::STATUS_SHOW
        ];
        if(!empty($request->hot)){
            $where['hot'] = (int)$request->hot;
        }
        $brand = $this->brandRepository->get($where);
        return response([
            'result' => true,
            'data' => $brand
        ]);
    }

    public function get_by_category(Request $request){
        $ids = [(int)$request->category_id] + $this->productCategoryRepository->get_child_id($request->category_id);
        $where = [
            'type' => $this->productRepository::TYPE_RETAIL,
            'status' => $this->productRepository::STATUS_SHOW,
            'status_website' => $this->productRepository::STATUS_WEB_SHOW,
            'category_id' => ['id' , 'whereIn', $ids]
        ];
        $data = $this->productRepository->get($where, [],  ['brand_id'], ['brand_id', DB::raw('COUNT(id) as total')], 1000)->pluck('total', 'brand_id')->toArray();
        $brand_ids = array_keys($data);
        $brand = $this->brandRepository->get([
            'id' => ['id', 'whereIn', $brand_ids]
        ]);
        if($brand){
            foreach ($brand as $key => $value){
                $value->total = $data[$value->id];
            }
        }
        return response([
            'result' => true,
            'data' => $brand
        ]);
    }
}
