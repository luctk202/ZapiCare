<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\ChangePasswordRequest;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Repositories\Admin\AdminRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public $adminRepository;

    public function __construct(AdminRepository $adminRepository)
    {
        $this->adminRepository = $adminRepository;
    }

    public function login()
    {
        return view('admin.content.auth.login');
    }

    public function authenticate(LoginRequest $request)
    {
        if (auth('admin')->attempt(['email' => $request->email, 'password' => $request->password, 'status' => $this->adminRepository::STATUS_ACTIVE])) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Tài khoản hoặc mật khẩu không đúng'
        ])->withInput();
    }

    public function logout(Request $request)
    {
        auth('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.auth.login');
    }

    public function changePassword(){
        return view('admin.content.auth.password');
    }

    public function updatePassword(ChangePasswordRequest $request)
    {
        if (!(Hash::check($request->old_password, auth('admin')->user()->password))) {
            return back()->withErrors([
                'old_password' => 'Mật khẩu cũ không đúng'
            ])->withInput();
        }
        $data = [
            'password' => $request->password
        ];
        $this->adminRepository->edit(auth()->user(), $data);
        return redirect()->route('admin.dashboard');
    }
}
