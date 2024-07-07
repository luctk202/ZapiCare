<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Filter\FilterRepository;
use App\Repositories\FilterAttributeProduct\FilterAttributeProductRepository;
use App\Repositories\ProductCategory\ProductCategoryRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FilterController extends Controller
{
    public $filterRepository;
    public $productCategoryRepository;
    public $filterAttributeProductRepository;

    public function __construct(FilterRepository $filterRepository, ProductCategoryRepository $productCategoryRepository, FilterAttributeProductRepository $filterAttributeProductRepository)
    {
        $this->filterRepository = $filterRepository;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->filterAttributeProductRepository = $filterAttributeProductRepository;
    }

    public function index(){
        $data = $this->filterRepository->active_all();
        return response([
            'result' => true,
            'data' => $data
        ]);
    }

    public function get_by_category(Request $request){
        $ids = [(int)$request->category_id] + $this->productCategoryRepository->get_child_id($request->category_id);
        $where = [
            'category_id' => ['category_id' , 'whereIn', $ids]
        ];
        $data = $this->filterAttributeProductRepository->get($where, [],  ['filter_attribute_id'], ['filter_attribute_id', DB::raw('COUNT(id) as total')], 1000)->pluck('total', 'filter_attribute_id')->toArray();
        return response([
            'result' => true,
            'data' => $data
        ]);
    }
}
