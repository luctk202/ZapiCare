<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Banner\BannerRepository;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public $bannerRepository;

    public function __construct(BannerRepository $bannerRepository)
    {
        $this->bannerRepository = $bannerRepository;
    }

    public function index(Request $request)
    {
        $where = [
            'status' => $this->bannerRepository::STATUS_ACTIVE,
            'start_time' => ['start_time', '<=', time()],
            'end_time' => ['end_time', '>=', time()],
        ];
        if (!empty($request->category_id)) {
            $where['category_id'] = (int)$request->category_id;
        }
        /*if (!empty($request->group_id)) {
            $where['group_id'] = (int)$request->group_id;
        }*/
        if (!empty($request->position)) {
            $where['position'] = (int)$request->position;
        }
        $data = $this->bannerRepository->get($where);
        return response([
            'result' => true,
            'data' => $data
        ]);
    }

}
