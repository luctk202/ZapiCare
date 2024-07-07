<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\NotificationDelete\NotificationDeleteRepository;
use App\Repositories\NotificationRead\NotificationReadRepository;
use App\Repositories\NotificationRequest\NotificationRequestRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public $notificationRepository;
    public $notificationDeleteRepository;
    public $notificationReadRepository;
    public $notificationRequestRepository;
    public $userRepository;

    public function __construct(NotificationDeleteRepository  $notificationDeleteRepository,
                                NotificationRepository        $notificationRepository,
                                NotificationReadRepository    $notificationReadRepository,
                                UserRepository                $userRepository,
                                NotificationRequestRepository $notificationRequestRepository)
    {
        $this->notificationRepository = $notificationRepository;
        $this->notificationDeleteRepository = $notificationDeleteRepository;
        $this->notificationReadRepository = $notificationReadRepository;
        $this->notificationRequestRepository = $notificationRequestRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {
        $where = [];
        if (auth()->id()) {
            $user = $this->userRepository->find(auth()->id());
            $ids = $this->notificationDeleteRepository->pluckWhere(['user_id' => auth()->id()], 'notification_id');
            $where = [
                'user_id' => ['user_id', 'whereIn', [auth()->id(), 0]],
            ];
            if (!empty($request->type)) {
                $where['type'] = $request->type;
            }
            if (!empty($request->item_type)) {
                $where['item_type'] = $request->item_type;
            }
            if ($ids) {
                $where['id'] = ['id', 'whereNotIn', $ids];
            }
            $where['created_at'] = ['created_at', '>=', $user->created_at];
        } else {
            $where['type'] = 1;
        }
        $notifications = $this->notificationRepository->paginate($where, ['created_at' => 'desc'], [], [], $request->limit ?? 50);
        if ($notifications) {
            $notifications->load('read');
        }
        return response([
            'result' => true,
            'data' => $notifications
        ]);
    }

    public function count_by_item_type()
    {
        $ids_delete = $this->notificationDeleteRepository->pluckWhere(['user_id' => auth()->id()], 'notification_id')->toArray();
        $ids_read = $this->notificationReadRepository->pluckWhere(['user_id' => auth()->id()], 'notification_id')->toArray();
        $ids = array_merge($ids_delete, $ids_read);
        $where = [
            'user_id' => ['user_id', 'whereIn', [auth()->id(), 0]],
            'type' => $this->notificationRepository::TYPE_MEMBER,
        ];
        if ($ids) {
            $where['id'] = ['id', 'whereNotIn', $ids];
        }
        $notifications = $this->notificationRepository->get($where, [], ['item_type'], ['item_type', DB::raw('COUNT(*) as count')]);
        //$data = $this->userRepository->get($where, [], ['group_affiliate_id'], ['group_affiliate_id', DB::raw('COUNT(*) as count')]);
        /*if ($notifications) {
            $notifications->load('read');
        }*/
        return response([
            'result' => true,
            'data' => $notifications
        ]);
    }

    public function count(Request $request)
    {
        $count = 0;
        if (auth()->id()) {
            $ids_delete = $this->notificationDeleteRepository->pluckWhere(['user_id' => auth()->id()], 'notification_id')->toArray();
            $ids_read = $this->notificationReadRepository->pluckWhere(['user_id' => auth()->id()], 'notification_id')->toArray();
            $ids = array_merge($ids_delete, $ids_read);
            $where = [
                'user_id' => ['user_id', 'whereIn', [auth()->id(), 0]],
                //'type' => 1
            ];
            if ($ids) {
                $where['id'] = ['id', 'whereNotIn', $ids];
            }
            if (!empty($request->type)) {
                $where['type'] = $request->type;
            }
            $count = $this->notificationRepository->count($where);
        }
        return response([
            'result' => true,
            'data' => $count
        ]);
    }

    public function read(Request $request)
    {
        if (auth()->id()) {
            $this->notificationReadRepository->create([
                'user_id' => auth()->id(),
                'notification_id' => $request->id
            ]);
        }
        return response([
            'result' => true,
        ]);
    }

    public function delete(Request $request)
    {
        if (auth()->id()) {
            $this->notificationDeleteRepository->create([
                'user_id' => auth()->id(),
                'notification_id' => $request->id
            ]);
        }
        return response([
            'result' => true,
        ]);
    }

    public function show($id)
    {
        $where = [
            'id' => $id
        ];
        if (!auth()->id()) {
            $where['type'] = $this->notificationRequestRepository::TYPE_PUBLIC;
        }
        $notification = $this->notificationRequestRepository->find($id);
        /*if($notification->item_type == 'topic'){
            $notification->load('detail');
        }*/
        return response([
            'result' => true,
            'data' => $notification
        ]);
    }
}
