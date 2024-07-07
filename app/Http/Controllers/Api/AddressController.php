<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Address\CreateRequest;
use App\Http\Requests\Api\Address\EditRequest;
use App\Repositories\Address\AddressRepository;
use App\Repositories\District\DistrictRepository;
use App\Repositories\Province\ProvinceRepository;
use App\Repositories\Ward\WardRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    public $provinceRepository;
    public $districtRepository;
    public $wardRepository;
    public $addressRepository;

    public function __construct(ProvinceRepository $provinceRepository, DistrictRepository $districtRepository, WardRepository $wardRepository, AddressRepository $addressRepository)
    {
        $this->provinceRepository = $provinceRepository;
        $this->districtRepository = $districtRepository;
        $this->wardRepository = $wardRepository;
        $this->addressRepository = $addressRepository;
    }

    public function province()
    {
        $provinces = $this->provinceRepository->get([], ['order' => 'ASC']);
        return response([
            'result' => true,
            'data' => $provinces
        ]);
    }

    public function district(Request $request)
    {
        $districts = $this->districtRepository->get(['province_id' => (int)$request->province_id]);
        return response([
            'result' => true,
            'data' => $districts
        ]);
    }

    public function ward(Request $request)
    {
        $wards = $this->wardRepository->get(['district_id' => (int)$request->district_id]);
        return response([
            'result' => true,
            'data' => $wards
        ]);
    }

    public function index(Request $request)
    {
        $where = [
            'user_id' => auth()->id()
        ];
        if (!empty($request->search)) {
            $where['orWhere'] = [['name', 'like', $request->search], ['phone', 'like', $request->search]];
        }
        $data = $this->addressRepository->paginate($where, [], [], [], $request->limit ?? 30);
        return response([
            'result' => true,
            'data' => $data
        ]);
    }

    public function store(CreateRequest $request)
    {
        $param = $request->only(['name', 'phone', 'address', 'province_id', 'district_id', 'ward_id', 'default']);
        $param['user_id'] = auth()->id();
        $data = DB::transaction(function () use ($param) {
            if (!empty($param['default'])) {
                $this->addressRepository->editWhere(['user_id' => auth()->id()], ['default' => 0]);
            }
            $data = $this->addressRepository->create($param);
            return $data;
        });
        return response([
            'result' => true,
            'data' => $data
        ]);
    }

    public function update($id, EditRequest $request)
    {
        $data = $this->addressRepository->first([
            'user_id' => auth()->id(),
            'id' => $id
        ]);
        if (!$data) {
            return response([
                'result' => false,
                'message' => 'Không tìm thấy thông tin địa chỉ'
            ]);
        }
        $param = $request->only(['name', 'phone', 'address', 'province_id', 'district_id', 'ward_id', 'default']);
        $data = DB::transaction(function () use ($data, $param) {
            if (!empty($param['default'])) {
                $this->addressRepository->editWhere(['user_id' => auth()->id()], ['default' => 0]);
            }
            $data = $this->addressRepository->edit($data, $param);
            return $data;
        });
        return response([
            'result' => true,
            'data' => $data
        ]);
    }

    public function set_default($id)
    {
        $data = $this->addressRepository->first([
            'user_id' => auth()->id(),
            'id' => $id
        ]);
        if (!$data) {
            return response([
                'result' => false,
                'message' => 'Không tìm thấy thông tin địa chỉ'
            ]);
        }
        $data = DB::transaction(function () use ($data) {
            $this->addressRepository->editWhere(['user_id' => auth()->id()], ['default' => 0]);
            $data = $this->addressRepository->edit($data, [
                'default' => 1
            ]);
            return $data;
        });
        return response([
            'result' => true,
            'data' => $data
        ]);
    }

    public function delete($id)
    {
        $data = $this->addressRepository->first([
            'user_id' => auth()->id(),
            'id' => $id
        ]);
        if (!$data) {
            return response([
                'result' => false,
                'message' => 'Không tìm thấy thông tin địa chỉ'
            ]);
        }
        $this->addressRepository->delete($data);
        return response([
            'result' => true,
        ]);
    }
}
