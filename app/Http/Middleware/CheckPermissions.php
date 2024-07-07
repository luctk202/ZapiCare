<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class CheckPermissions
{
    protected $auth;
    /**
     * Creates a new instance of the middleware.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param $permissions
     * @return mixed
     */
    public function handle($request, Closure $next, $permissions)
    {
        if (! is_array($permissions)) {
            $permissions = explode("|", $permissions);
        }
//        dd($permissions);
        if($request->user()->email != 'admin@example.com'){
            if ($this->auth->guest() || ! $request->user()->hasPermission($permissions)) {
//                if (!$request->ajax()) {
//                    return response()->json(['result' => false, 'message' => 'Bạn không có quyền truy cập'], 200);
//                }
                return response()->view('admin.errors.403', [], 403);
                //abort(403, "Không có quyền truy cập.");
            }
        }

        return $next($request);
    }
}
