<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function search(Request $request){
        $where = [];
        //$where['type'] = ['type', 'whereIn', [$this->userRepository::TYPE_SALE, $this->userRepository::TYPE_CUSTOMER]];
        if (!empty($request->search)) {
            $where['orWhere'] = [
                ['phone', 'like', $request->search],
                ['name', 'like', $request->search],
                ['email', 'like', $request->search]
            ];
        }
        $users = $this->userRepository->paginate($where, ['id' => 'DESC']);
        return response([
            'result' => true,
            'data' => $users
        ]);
    }
}
