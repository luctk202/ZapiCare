<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Fee\FeeRepository;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    public $feeRepository;

    public function __construct(FeeRepository $feeRepository)
    {
        $this->feeRepository = $feeRepository;
    }

    public function index(Request $request)
    {
        $order_type = $request->order_type ?? $this->feeRepository::TYPE_SALE;
        $fee = $this->feeRepository->active_all($order_type);
        return response([
            'result' => true,
            'data' => $fee
        ]);
    }
}
