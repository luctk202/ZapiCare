<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\ChangePasswordRequest;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\SignUpRequest;
use App\Repositories\District\DistrictRepository;
use App\Repositories\Province\ProvinceRepository;
use App\Repositories\User\UserRepository;
use App\Repositories\Ward\WardRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AuthController extends Controller
{

    public $userRepository;
    public $affiliateGroupRepository;
    public $provinceRepository;
    public $districtRepository;
    public $wardRepository;

    public function __construct(
        UserRepository $userRepository,
        ProvinceRepository $provinceRepository,
        DistrictRepository $districtRepository,
        WardRepository $wardRepository
    ) {
        $this->userRepository = $userRepository;
        $this->provinceRepository = $provinceRepository;
        $this->districtRepository = $districtRepository;
        $this->wardRepository = $wardRepository;
    }

    public function signup(SignUpRequest $request)
    {
        $user = $this->userRepository->first(['phone' => $request->phone]);
        if ($user != null) {
            return response()->json([
                'result' => false,
                'message' => 'Tài khoản đã tồn tại',
            ], 200);
        }
        $data = $request->only(['name', 'phone', 'email', 'address', 'province_id', 'district_id', 'ward_id']);
        $data['password'] = bcrypt($request->password);
        $data['verified'] = $this->userRepository::VERIFIED;
        $data['verified_time'] = time();
        $data['status'] = $this->userRepository::STATUS_ACTIVE;
        $data['device_token'] = $request->device_token ?? '';
        $avatar = $request->file('avatar');
        if ($avatar) {
            $data['avatar'] = Storage::putFileAs('user', $avatar, $avatar->getClientOriginalName());
        }
        if (!empty($data['province_id'])) {
            $province = $this->provinceRepository->find($data['province_id']);
            $data['province_name'] = $province->name ?? '';
        }
        if (!empty($data['district_id'])) {
            $district = $this->districtRepository->find($data['district_id']);
            $data['district_name'] = $district->name ?? '';
        }
        if (!empty($data['ward_id'])) {
            $ward = $this->wardRepository->find($data['ward_id']);
            $data['ward_name'] = $ward->name ?? '';
        }
        $this->userRepository->create($data);
        return response()->json([
            'result' => true,
            'message' => 'Đăng ký tài khoản thành công',
        ]);
    }

    public function login(LoginRequest $request)
    {
        $user = $this->userRepository->first(['phone' => $request->phone]);
        if (!empty($user)) {
            if (Hash::check($request->password, $user->password)) {

                if ($user->status == $this->userRepository::STATUS_BLOCK) {
                    return response()->json([
                        'result' => false,
                        'message' => 'Tài khoản không tồn tại hoặc đã bị khóa'
                    ]);
                }
                if ($request->device_token) {
                    $user->device_token = $request->device_token;
                    $user->save();
                }
                $user->load('shop');
                $user->load('partner');
                return $this->loginSuccess($user);
            } else {
                return response()->json([
                    'result' => false,
                    'message' => 'Mật khẩu không đúng',
                ]);
            }
        } else {
            return response()->json([
                'result' => false,
                'message' => 'Tài khoản không đúng',
            ]);
        }
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        $user = auth()->user();
        if (Hash::check($request->old_password, $user->password)) {
            $user->password = bcrypt($request->password);
            $user->save();
            return response()->json([
                'result' => true,
                'message' => 'Đổi mật khẩu thành công',
            ]);
        }
        return response()->json([
            'result' => false,
            'message' => 'Mật khẩu hiện tại không đúng',
            'data' => null
        ]);
    }

    public function resetPassword(Request $request)
    {
        $user = $this->userRepository->first([
            'phone' => $request->phone
        ]);
        if ($user != null) {
//            $user->verification_code = null;
            $user->password = Hash::make($request->password);
            $user->save();
            return response()->json([
                'result' => true,
                'message' => 'Cập nhật mật khẩu thành công',
            ], 200);
        } else {
            return response()->json([
                'result' => false,
                'message' => 'Không tìm thấy thông tin tài khoản',
            ], 200);
        }
    }

    public function user(Request $request)
    {
        $id = auth()->id();
        $user = $this->userRepository->find($id);
        $user->load('shop');
        $user->load('partner');
        return response()->json([
            'result' => true,
            'data' => $user
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $data = [];
        if (!empty($request->name)) {
            $data['name'] = $request->name;
        }
        if (!empty($request->email)) {
            $data['email'] = $request->email;
        }
        if (!empty($request->address)) {
            $data['address'] = $request->address;
        }
        if (!empty($request->province_id)) {
            $data['province_id'] = $request->province_id;
        }
        if (!empty($request->district_id)) {
            $data['district_id'] = $request->district_id;
        }
        if (!empty($request->ward_id)) {
            $data['ward_id'] = $request->ward_id;
        }
        if (!empty($request->bank_name) || !empty($request->bank_username) || !empty($request->bank_number)) {
            if (Hash::check($request->password, $user->password)) {
                if (!empty($request->bank_name)) {
                    $data['bank_name'] = $request->bank_name;
                }
                if (!empty($request->bank_username)) {
                    $data['bank_username'] = $request->bank_username;
                }
                if (!empty($request->bank_number)) {
                    $data['bank_number'] = $request->bank_number;
                }
            } else {
                return response()->json([
                    'result' => false,
                    'message' => 'Mật khẩu xác thực không đúng'
                ]);
            }
        }
        $avatar = $request->file('avatar');
        if ($avatar) {
            $data['avatar'] = Storage::putFileAs('user', $avatar, $avatar->getClientOriginalName());
        }
        $this->userRepository->edit($user, $data);

        return response()->json([
            'result' => true,
            'message' => 'Cập nhật thông tin tài khoản thành công'
        ]);
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        $user->device_token = '';
        $user->save();
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            'result' => true,
            'message' => 'Đăng xuất thành công'
        ]);
    }

    public function destroy(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return response(['result' => false, 'message' => 'Không tìm thấy thông tin tài khoản']);
        }
        $user->status = $this->userRepository::STATUS_BLOCK;
        $user->save();
        return response([
            'result' => true,
        ]);
    }

    protected function loginSuccess($user)
    {
        $token = $user->createToken('API Token')->plainTextToken;
        return response()->json([
            'result' => true,
            'message' => 'Đăng nhập thành công',
            'data' => [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_at' => null,
                'user' => $user
            ]
        ]);
    }

    public function search(Request $request)
    {
        $conditions = [];

        // Thêm điều kiện tìm kiếm theo tên nếu được cung cấp trong request
        if ($request->has('name')) {
            $conditions[] = ['name', 'like', '%' . $request->input('name') . '%'];
        }

        // Thực hiện tìm kiếm sử dụng UserRepository
        $users = $this->userRepository->get($conditions);

        // Kiểm tra xem có kết quả tìm kiếm không
        if ($users->isNotEmpty()) {
            return response()->json([
                'success' => true,
                'users' => $users,
            ]);
        }

        // Trả về thông báo nếu không tìm thấy người dùng nào
        return response()->json([
            'success' => false,
            'message' => 'Không tìm thấy người dùng',
        ]);
    }
}
