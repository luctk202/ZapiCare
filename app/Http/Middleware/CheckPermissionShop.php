<?php

namespace App\Http\Middleware;

use App\Repositories\Shop\ShopRepository;
use Closure;
use Illuminate\Http\Request;

class CheckPermissionShop
{
    private $shopRepository;

    public function __construct(ShopRepository $shopRepository)
    {
        $this->shopRepository = $shopRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $shop = $this->shopRepository->first([
            'user_id' => auth()->id(),
//            'id' => $request->shop_id
        ]);
        if (!$shop) {
            return response([
                'result' => false,
                'message' => 'Không tìm thấy thông tin cửa hàng',
            ]);
        }
        app()->instance('shop', $shop);
        return $next($request);
    }
}
