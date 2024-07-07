<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Notification\CreateRequest;
use App\Http\Requests\Admin\Notification\EditRequest;
use App\Models\Logs;
use App\Repositories\AffiliateGroup\AffiliateGroupRepository;
use App\Repositories\NotificationRequest\NotificationRequestRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller
{
    private $notificationRequestRepository;

    public function __construct(NotificationRequestRepository $notificationRequestRepository)
    {
        $this->notificationRequestRepository = $notificationRequestRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        if ($request->get('status', -1) > -1) {
            $where['status'] = (int)$request->status;
        }
        $data = $this->notificationRequestRepository->paginate($where, ['created_at' => 'DESC']);

        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['name' => "Danh sách thông báo"]
        ];
        $status = $this->notificationRequestRepository->aryStatus;
        $type = [
            1 => 'Khuyến mại',
            2 => 'Thành viên'
        ];
        return view('admin.content.notification_request.list')->with(compact('data', 'status', 'breadcrumbs', 'type'));
    }

    public function create()
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.notification.index'), 'name' => "Danh sách"], ['name' => 'Gửi thông báo']
        ];
        return view('admin.content.notification_request.create')->with(compact('breadcrumbs'));
    }

    public function store(CreateRequest $request)
    {
        $time_send = strtotime($request->time_send);
        if($time_send < time()){
            return back()->withErrors([
                'time_send' => 'Thời gian gửi thông báo không đúng'
            ])->withInput();
        }
        $data = [
            'title' => $request->title,
            'text' => $request->text,
            'type' => $request->type,
            'time_send' => $time_send,
            'status' => $this->notificationRequestRepository::STATUS_REQUEST,
            'description' => $request->description
        ];
        $image = $request->file('icon');
        if ($image) {
            $data['icon'] = Storage::putFileAs('notification', $image, $image->getClientOriginalName());;
        }
        DB::transaction(function () use ($data){
            $notification = $this->notificationRequestRepository->create($data);
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'create_notification',
                'item_id' => $notification->id,
                'data' => $data
            ]);
        });
        return redirect()->route('admin.notification.index');
    }

    public function edit($id)
    {
        $breadcrumbs = [
            ['link' => route('admin.dashboard'), 'name' => "Dashboard"], ['link' => route('admin.notification.index'), 'name' => "Danh sách"], ['name' => 'Sửa thông báo']
        ];
        //$user_type = $this->userRepository->aryType;
        $data = $this->notificationRequestRepository->first([
            'status' => $this->notificationRequestRepository::STATUS_REQUEST,
            'id' => $id
        ]);
        return view('admin.content.notification_request.edit')->with(compact('breadcrumbs', 'data'));
    }

    public function update($id, EditRequest $request)
    {
        $time_send = strtotime($request->time_send);
        if($time_send < time()){
            return back()->withErrors([
                'time_send' => 'Thời gian gửi thông báo không đúng'
            ])->withInput();
        }
        $data = [
            'title' => $request->title,
            'text' => $request->text,
            'type' => $request->type,
            'time_send' => $time_send,
            'status' => $this->notificationRequestRepository::STATUS_REQUEST,
            'description' => $request->description
        ];
        $image = $request->file('icon');
        if ($image) {
            $data['icon'] = Storage::putFileAs('notification', $image, $image->getClientOriginalName());;
        }
        $notification = $this->notificationRequestRepository->find($id);
        DB::transaction(function () use ($notification, $data){
            $this->notificationRequestRepository->edit($notification, $data);
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'edit_notification',
                'item_id' => $notification->id,
                'data' => $data
            ]);
        });
        return redirect()->route('admin.notification.index');
    }

    public function show()
    {

    }

    /*public function update_status($id, Request $request)
    {
        $user = $this->bannerRepository->find($id);
        $data = [
            'status' => (int)$request->status
        ];
        $this->bannerRepository->edit($user, $data);
        return response(['result' => true]);
    }*/

    public function delete($id)
    {
        $notification = $this->notificationRequestRepository->find($id);
        DB::transaction(function () use ($notification) {
            Logs::create([
                'user_id' => auth('admin')->id(),
                'source' => 1,
                'action' => 'delete_notification',
                'item_id' => $notification->id,
                'data' => []
            ]);
            $this->notificationRequestRepository->delete($notification);
        });
        return response(['result' => true]);
    }
}
