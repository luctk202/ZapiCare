<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ProductCategory\ProductCategoryRepository;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public $categoryRepository;

    public function __construct(ProductCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index(){
        $where = [
            'status' => $this->categoryRepository::STATUS_ACTIVE
        ];
        $categories = $this->categoryRepository->get($where);
        $data = [];
        $this->categoryRepository->sort_parent($categories,0, '', $data);
        return response([
            'result' => true,
            'data' => $data
        ]);
    }
}
