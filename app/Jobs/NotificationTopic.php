<?php

namespace App\Jobs;

use App\Repositories\NotificationRequest\NotificationRequestRepository;
use App\Support\Firebase\FirebaseService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class NotificationTopic implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $id;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(NotificationRequestRepository $notificationRequestRepository, FirebaseService $firebaseService)
    {
        $id = $this->id;
        $notification = $notificationRequestRepository->find($id);
        $notificationRequestRepository->edit($notification, ['status' => $notificationRequestRepository::STATUS_SENT]);
        $data = [
            'device_token' => '/topics/' . $notification->type,
            'title' => $notification->title,
            'text' => $notification->text,
            'icon' => $notification->icon,
            'item_type' => FirebaseService::TYPE_TOPIC,
            'item_id' => $notification->id,
            'user_id' => 0,
            'type' => $notification->type,
        ];
        $firebaseService->send($data);
    }
}
