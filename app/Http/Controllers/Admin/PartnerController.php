<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Partner\CreateRequest;
use App\Http\Requests\Admin\Partner\EditRequest;
use App\Models\Company;
use App\Models\Logs;
use App\Models\PartnerSetting;
use App\Repositories\District\DistrictRepository;
use App\Repositories\Partner\PartnerRepository;
use App\Repositories\PartnerSetting\PartnerSettingRepository;
use App\Repositories\Province\ProvinceRepository;
use App\Repositories\Ward\WardRepository;
use App\Repositories\Shop\ShopRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    public $partnerRepository;
    public $partnerSettingRepository;
    public $provinceRepository;
    public $districtRepository;
    public $wardRepository;

    public function __construct(PartnerRepository $partnerRepository,PartnerSettingRepository $partnerSettingRepository, ProvinceRepository $provinceRepository, DistrictRepository $districtRepository, WardRepository $wardRepository)
    {
        $this->partnerRepository = $partnerRepository;
        $this->partnerSettingRepository = $partnerSettingRepository;
        $this->provinceRepository = $provinceRepository;
        $this->districtRepository = $districtRepository;
        $this->wardRepository = $wardRepository;
    }

    public function index(Request $request)
    {
        $where_zone = [];
        $province = $this->provinceRepository->get([], ['order' => 'ASC']);
        $district = $ward = null;
        if (!empty($request->province_id)) {
            $where_zone['province_id'] = $request->province_id;
            $district = $this->districtRepository->get(['province_id' => $request->province_id]);
        }
        if (!empty($request->district_id)) {
            $where_zone['district_id'] = $request->district_id;
            $ward = $this->wardRepository->get(['district_id' => $request->district_id]);
        }
        if (!empty($request->ward_id)) {
            $where_zone['ward_id'] = $request->ward_id;
        }
        if (!empty($request->user_id)) {
            $where_zone['user_id'] = $request->user_id;
        }
        if (!empty($request->name)) {
            $where_zone['name'] = ['name', 'like', $request->name];
        }
        if (!empty($request->phone)) {
            $where_zone['phone'] = $request->phone;
        }
        if (!empty($request->code)) {
            $where_zone['code'] = $request->code;
        }
        if ($request->get('status', -1) > -1) {
            $where_zone['status'] = (int)$request->status;
        }
        $zones = $this->partnerRepository->paginate($where_zone, ['id' => 'DESC'], [], [], $request->limit ?? 50);
        $zones->load(['user']);
        $status = ['-1' => 'Vui lòng chọn'] + $this->partnerRepository->aryStatus;
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách"]
        ];
        return view('admin.content.partner.list')->with(compact('zones', 'breadcrumbs', 'province', 'district', 'ward', 'status'));
    }

    public function create(Request $request)
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.partner.index'), 'name' => "Danh sách"], ['name' => 'Tạo mới']
        ];
        $province = $this->provinceRepository->get([], ['order' => 'ASC']);
        $district = $ward = null;
        if (!empty($request->old('province_id'))) {
            $district = $this->districtRepository->get(['province_id' => $request->old('province_id')]);
        }
        if (!empty($request->old('district_id'))) {
            $ward = $this->wardRepository->get(['district_id' => $request->old('district_id')]);
        }
        return view('admin.content.partner.create')->with(compact('breadcrumbs', 'province', 'district', 'ward'));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->only(['name', 'code', 'user_id', 'phone', 'address', 'province_id', 'district_id', 'ward_id','description']);
        $data['status'] = $this->partnerRepository::STATUS_HIDE;
        if (!empty($data['province_id'])) {
            $province = $this->provinceRepository->find($data['province_id']);
        }
        if (!empty($data['district_id'])) {
            $district = $this->districtRepository->first(['id' => $data['district_id'], 'province_id' => $data['province_id']]);
        }
        if (!empty($data['ward_id'])) {
            $ward = $this->wardRepository->first(['district_id' => $data['district_id'], 'id' => $data['ward_id']]);
        }
        $data['province_name'] = $province->name ?? '';
        $data['district_name'] = $district->name;
        $data['ward_name'] = $ward->name ?? '';
        $image = $request->file('logo');
        if ($image) {
            $data['logo'] = Storage::putFileAs('shop', $image, $image->getClientOriginalName());;
        }
        DB::transaction(function () use ($data) {
            $zone = $this->partnerRepository->create($data);
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'create_zone',
                'item_id' => $zone->id,
                'data' => $data
            ]);
        });
        return redirect()->route('admin.partner.index');
    }

    public function edit($id, Request $request)
    {
        $zone = $this->partnerRepository->find($id);
        $zone->load('user');
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.partner.index'), 'name' => "Danh sách"], ['name' => 'Sửa']
        ];
        $province = $this->provinceRepository->get([], ['order' => 'ASC']);
        $district = $ward = null;
        if ($request->old('province_id')) {
            if (!empty($request->old('province_id'))) {
                $district = $this->districtRepository->get(['province_id' => $request->old('province_id')]);
            }
            if (!empty($request->old('district_id'))) {
                $ward = $this->wardRepository->get(['district_id' => $request->old('district_id')]);
            }
        } else {
            $district = $this->districtRepository->get(['province_id' => $zone->province_id]);
            $ward = $this->wardRepository->get(['district_id' => $zone->district_id]);
        }
        return view('admin.content.partner.edit')->with(compact('zone', 'breadcrumbs', 'ward', 'district', 'province'));
    }

    public function update($id, EditRequest $request)
    {
        $zone = $this->partnerRepository->find($id);

        $data = $request->only(['name', 'code','user_id','description', 'phone', 'address', 'province_id', 'district_id', 'ward_id']);

        if (empty($data['district_id'])) {
            return back()->withErrors([
                'district_id' => 'Vui lòng nhập khu vực của cửa hàng'
            ])->withInput();
        }

        if (!empty($data['province_id'])) {
            $province = $this->provinceRepository->find($data['province_id']);
        }
        if (!empty($data['district_id'])) {
            $district = $this->districtRepository->first(['id' => $data['district_id'], 'province_id' => $data['province_id']]);
        }
        if (!empty($data['ward_id'])) {
            $ward = $this->wardRepository->first(['district_id' => $data['district_id'], 'id' => $data['ward_id']]);
        }

        $data['province_name'] = $province->name ?? '';
        $data['district_name'] = $district->name;
        $data['ward_name'] = $ward->name ?? '';
        $image = $request->file('logo');
        if ($image) {
            $data['logo'] = Storage::putFileAs('shop', $image, $image->getClientOriginalName());;
        }
        DB::transaction(function () use ($zone, $data) {
            $this->partnerRepository->edit($zone, $data);
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'edit_zone',
                'item_id' => $zone->id,
                'data' => $data
            ]);
        });
        return redirect()->route('admin.partner.index');
    }

    public function update_status($id, Request $request)
    {
        $user = $this->partnerRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->partnerRepository->edit($user, $data);
        return response(['result' => true]);
    }

    public function delete($id)
    {
        $zone = $this->partnerRepository->find($id);
        DB::transaction(function () use ($zone) {
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'delete_zone',
                'item_id' => $zone->id,
                'data' => []
            ]);
            $this->partnerRepository->delete($zone);
        });
        return response(['result' => true]);
    }

    public function search(Request $request)
    {
        $where = [];
        if (!empty($request->search)) {
            $where['orWhere'] = [
                ['phone', 'like', $request->search],
                ['name', 'like', $request->search],
            ];
        }
        $partners = $this->partnerRepository->paginate($where, ['id' => 'DESC']);
        return response([
            'result' => true,
            'data' => $partners
        ]);
    }
    public function setting($id,Request $request){
        $partner = $this->partnerRepository->find($id);
        $settings = $this->partnerSettingRepository->first(['partner_id' => (int)$id]);
        return view('admin.content.partner.setting',compact('partner','settings'));
    }
    function updateSetting(Request $request,$id){
        $partner = $this->partnerRepository->find($id);
        $data = [
            'commission'=>$request->commission,
        ];
        DB::transaction(function () use ($partner, $data) {
            $partner->partner_setting()->delete();
            if($data){
                $partner->partner_setting()->create($data);
            }
        });
        return redirect()->route('admin.partner.index');
    }
}
