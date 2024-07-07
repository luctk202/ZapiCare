<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Disease\DiseaseRepository;
use App\Repositories\Product\ProductRepository;
class DiseaseController extends Controller
{
    protected $diseaseRepository;
    protected $productRepository;

    public function __construct(DiseaseRepository $diseaseRepository, ProductRepository $productRepository)
    {
        $this->diseaseRepository = $diseaseRepository;
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $data = $this->diseaseRepository->all();
        $data->load('products');
        return response()->json([
            'result'=>true,
            'message'=>'danh sách bệnh lý',
            'data'=>$data
        ]);
    }
}
