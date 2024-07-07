<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Shop\ShopRepository;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public $shopRepository;

    public function __construct(ShopRepository $shopRepository)
    {
        $this->shopRepository = $shopRepository;
    }

    public function index(Request $request)
    {
        $where = [
            'status' => $this->shopRepository::STATUS_SHOW,
        ];
        if(!empty($request->province_id)){
            $where['province_id'] = $request->province_id;
        }
        if (!empty($request->search)) {
            $where['orWhere'] = [['name', 'like', $request->search]];
        }
        $orderBy = [];
        if (!empty($request->orderBy)) {
            $orderBy[$request->orderBy] = $request->orderType ?? 'DESC';
        }
        $data = $this->shopRepository->paginate($where, $orderBy, [], [], (int)($request->limit ?? 50));
        return response([
            'result' => true,
            'data' => $data
        ]);
    }

    public function show($id)
    {
        $data = $this->shopRepository->first([
            'id' => $id,
            'status' => $this->shopRepository::STATUS_SHOW,
        ]);
        $data->load('category');
        return response([
            'result' => true,
            'data' => $data
        ]);
    }

}
