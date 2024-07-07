<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CampaignPoint\CreateRequest;
use App\Http\Requests\Admin\CampaignPoint\EditRequest;
use App\Repositories\CampaignPoint\CampaignPointRepository;
use App\Repositories\CampaignUserPoint\CampaignUserPointRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CampaignPointController extends Controller
{
    public $campaignPointRepository;
    public $campaignUserPointRepository;

    public function __construct(CampaignPointRepository $campaignPointRepository, CampaignUserPointRepository $campaignUserPointRepository)
    {
        $this->campaignPointRepository = $campaignPointRepository;
        $this->campaignUserPointRepository = $campaignUserPointRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        if ($request->get('status', -1) > -1) {
            $where['status'] = (int)$request->status;
        }
        $data = $this->campaignPointRepository->paginate($where, ['id' => 'DESC']);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách"]
        ];
        $status = $this->campaignPointRepository->aryStatus;
        return view('admin.content.campaign_point.list')->with(compact('data', 'status', 'breadcrumbs'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.campaign-point.index'), 'name' => "Danh sách"], ['name' => 'Tạo mới']
        ];

        return view('admin.content.campaign_point.create')->with(compact('breadcrumbs'));
    }

    public function store(CreateRequest $request)
    {
        $aryTime = explode('to', $request->time);
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'start_time' => !empty($aryTime[0]) ? strtotime($aryTime[0]) : 0,
            'end_time' => !empty($aryTime[1]) ? (strtotime($aryTime[1]) + 86399) : (strtotime($aryTime[0]) + 86399),
            'status' => $this->campaignPointRepository::STATUS_ACTIVE
        ];
        $image = $request->file('image');
        if ($image) {
            $data['image'] = Storage::putFileAs('banner', $image, $image->getClientOriginalName());;
        }
        $this->campaignPointRepository->create($data);
        return redirect()->route('admin.campaign-point.index');
    }

    public function edit($id)
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.campaign-point.index'), 'name' => "Danh sách"], ['name' => 'Sửa']
        ];
        $data = $this->campaignPointRepository->find($id);
        return view('admin.content.campaign_point.edit')->with(compact('breadcrumbs', 'data'));
    }

    public function update($id, EditRequest $request)
    {
        $aryTime = explode('to', $request->time);
        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'start_time' => !empty($aryTime[0]) ? strtotime($aryTime[0]) : 0,
            'end_time' => !empty($aryTime[1]) ? (strtotime($aryTime[1]) + 86399) : (strtotime($aryTime[0]) + 86399),
        ];
        $image = $request->file('image');
        if ($image) {
            $data['image'] = Storage::putFileAs('banner', $image, $image->getClientOriginalName());;
        }
        $banner = $this->campaignPointRepository->find($id);
        $this->campaignPointRepository->edit($banner, $data);
        return redirect()->route('admin.campaign-point.index');
    }

    public function show($id, Request $request)
    {
        $where = [
            'campaign_point_id' => $id
        ];
        if (!empty($request->user_id)) {
            $where['user_id'] = (int)$request->user_id;
        }
        $data = $this->campaignUserPointRepository->paginate($where, ['point' => 'DESC']);
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.campaign-point.index'), 'name' => "Danh sách"], ['name' => "Top điểm"]
        ];
        return view('admin.content.campaign_point.show')->with(compact('data', 'breadcrumbs', 'id'));
    }

    public function update_status($id, Request $request)
    {
        $user = $this->campaignPointRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->campaignPointRepository->edit($user, $data);
        return response(['result' => true]);
    }

    public function delete($id)
    {
        $package = $this->campaignPointRepository->find($id);
        $this->campaignPointRepository->delete($package);
        return response(['result' => true]);
    }
}
