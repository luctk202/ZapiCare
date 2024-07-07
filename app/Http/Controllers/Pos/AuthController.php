<?php

namespace App\Http\Controllers\Pos;

use App\Http\Controllers\Controller;

use App\Http\Requests\Pos\BannerShop\CreateRequest;
use App\Http\Requests\Pos\BannerShop\UpdateRequest;
use App\Models\Banner;
use App\Models\BannerShop;
use App\Models\Shop;
use App\Repositories\Shop\ShopRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{
    public $shopRepository;

    public function __construct(ShopRepository $shopRepository)
    {
        $this->shopRepository = $shopRepository;
    }

    public function index(Request $request)
    {
        $shop = $this->shopRepository->first([
            'user_id' => auth()->id(),
            'status' => $this->shopRepository::STATUS_SHOW
        ]);
        return response([
            'result' => true,
            'data' => $shop
        ]);
    }

    public function update(Request $request){
        $shop = resolve('shop');
        $shop = Shop::find($shop->id);
        $data = [];
        if (!empty($request->description)) {
            $data['description'] = $request->description;
        }
        $avatar = $request->file('logo');
        if ($avatar) {
            $data['logo'] = Storage::putFileAs('shop', $avatar, $avatar->getClientOriginalName());
        }
        if (!empty($request->name)) {
            $data['name'] = $request->name;
        }
        $this->shopRepository->edit($shop, $data);
        return response([
            'result' => true,
            'message' => 'Sửa thành công',
        ]);
    }
}
