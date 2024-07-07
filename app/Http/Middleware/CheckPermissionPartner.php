<?php

namespace App\Http\Middleware;

use App\Repositories\Partner\PartnerRepository;
use Closure;
use Illuminate\Http\Request;

class CheckPermissionPartner
{
    private $partnerRepository;

    public function __construct(PartnerRepository $partnerRepository)
    {
        $this->partnerRepository = $partnerRepository;
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
        $partner = $this->partnerRepository->first([
            'user_id' => auth()->id(),
//            'id' => $request->shop_id
        ]);
        if (!$partner) {
            return response([
                'result' => false,
                'message' => 'Không tìm thấy thông tin đối tác',
            ]);
        }
        app()->instance('partner', $partner);
        return $next($request);
    }
}
