<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\ProductAttribute\ProductAttributeRepository;
use Illuminate\Http\Request;

class ProductAttributeController extends Controller
{
    public $attributeRepository;

    public function __construct(ProductAttributeRepository $attributeRepository)
    {
        $this->attributeRepository = $attributeRepository;
    }

    public function index(){
        $attributes = $this->attributeRepository->all();
        return response([
            'result' => true,
            'data' => $attributes
        ]);
    }
}
