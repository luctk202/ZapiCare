<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\NotificationRequest\NotificationRequestRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

class CronjobController extends Controller
{
    public $notificationRequestRepository;
    public $userRepository;

    public function __construct(NotificationRequestRepository $notificationRequestRepository,UserRepository $userRepository)
    {
        $this->notificationRequestRepository = $notificationRequestRepository;
        $this->userRepository = $userRepository;
    }

    public function notification()
    {
        $requests = $this->notificationRequestRepository->get([
            'status' => 0,
            'time_send' => ['time_send', '<=', time()]
        ]);//::where('status', 0)->where('time_send', '<=', time())
        if ($requests) {
            foreach ($requests as $req) {
                NotificationTopic::dispatch($req->id)->onQueue('notification');
            }
        }
    }
}
