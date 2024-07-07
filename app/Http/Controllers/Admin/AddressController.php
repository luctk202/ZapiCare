<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\District\DistrictRepository;
use App\Repositories\Province\ProvinceRepository;
use App\Repositories\Ward\WardRepository;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    private $provinceRepository;
    private $districtRepository;
    private $wardRepository;

    public function __construct(ProvinceRepository $provinceRepository, DistrictRepository $districtRepository, WardRepository $wardRepository)
    {
        $this->provinceRepository = $provinceRepository;
        $this->districtRepository = $districtRepository;
        $this->wardRepository = $wardRepository;
    }

    public function district(Request $request){
        $province_id = $request->province_id;
        $district = $this->districtRepository->get(['province_id' => $province_id]);
        return response([
            'result' => true,
            'data' => $district
        ]);
    }

    public function ward(Request $request){
        $district_id = $request->district_id;
        $ward = $this->wardRepository->get(['district_id' => $district_id]);
        return response([
            'result' => true,
            'data' => $ward
        ]);
    }

}
