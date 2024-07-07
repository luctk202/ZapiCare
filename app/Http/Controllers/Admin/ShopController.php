<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Shop\CreateRequest;
use App\Http\Requests\Admin\Shop\EditRequest;
use App\Models\Logs;
use App\Repositories\District\DistrictRepository;
use App\Repositories\Province\ProvinceRepository;
use App\Repositories\Ward\WardRepository;
use App\Repositories\Shop\ShopRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    public $shopRepository;
    public $provinceRepository;
    public $districtRepository;
    public $wardRepository;

    public function __construct(ShopRepository $shopRepository, ProvinceRepository $provinceRepository, DistrictRepository $districtRepository, WardRepository $wardRepository)
    {
        $this->shopRepository = $shopRepository;
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
        $zones = $this->shopRepository->paginate($where_zone, ['id' => 'DESC'], [], [], $request->limit ?? 50);
        $zones->load(['user','partner']);

        $status = ['-1' => 'Vui lòng chọn'] + $this->shopRepository->aryStatus;
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách"]
        ];
        return view('admin.content.shop.list')->with(compact('zones', 'breadcrumbs', 'province', 'district', 'ward', 'status'));
    }

    public function create(Request $request)
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.shop.index'), 'name' => "Danh sách"], ['name' => 'Tạo mới']
        ];
        $province = $this->provinceRepository->get([], ['order' => 'ASC']);
        $district = $ward = null;
        if (!empty($request->old('province_id'))) {
            $district = $this->districtRepository->get(['province_id' => $request->old('province_id')]);
        }
        if (!empty($request->old('district_id'))) {
            $ward = $this->wardRepository->get(['district_id' => $request->old('district_id')]);
        }
        return view('admin.content.shop.create')->with(compact('breadcrumbs', 'province', 'district', 'ward'));
    }

    public function store(CreateRequest $request)
    {
        $data = $request->only(['name', 'code', 'partner_id','user_id', 'phone', 'address', 'province_id', 'district_id', 'ward_id','description']);
        $data['status'] = $this->shopRepository::STATUS_HIDE;
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
            $zone = $this->shopRepository->create($data);
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'create_zone',
                'item_id' => $zone->id,
                'data' => $data
            ]);
        });
        return redirect()->route('admin.shop.index');
    }

    public function edit($id, Request $request)
    {
        $zone = $this->shopRepository->find($id);
        $zone->load(['user','partner']);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.shop.index'), 'name' => "Danh sách"], ['name' => 'Sửa']
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
        return view('admin.content.shop.edit')->with(compact('zone', 'breadcrumbs', 'ward', 'district', 'province'));
    }

    public function update($id, EditRequest $request)
    {
        $zone = $this->shopRepository->find($id);

        $data = $request->only(['name', 'zone', 'partner_id','user_id','description', 'phone', 'address', 'province_id', 'district_id', 'ward_id']);

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
            $this->shopRepository->edit($zone, $data);
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'edit_zone',
                'item_id' => $zone->id,
                'data' => $data
            ]);
        });
        return redirect()->route('admin.shop.index');
    }

    public function update_status($id, Request $request)
    {
        $user = $this->shopRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->shopRepository->edit($user, $data);
        return response(['result' => true]);
    }

    public function delete($id)
    {
        $zone = $this->shopRepository->find($id);
        DB::transaction(function () use ($zone) {
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'delete_zone',
                'item_id' => $zone->id,
                'data' => []
            ]);
            $this->shopRepository->delete($zone);
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
        $shops = $this->shopRepository->paginate($where, ['id' => 'DESC']);
        return response([
            'result' => true,
            'data' => $shops
        ]);
    }
}
