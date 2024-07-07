<?php

namespace App\Support\Firebase;

use App\Repositories\FirebaseNotification\FirebaseNotificationRepository;
use App\Repositories\Notification\NotificationRepository;
use App\Repositories\PosNotification\PosNotificationRepository;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;

class FirebaseService
{
    const TYPE_TOPIC = 'topic';
    const TYPE_HANDLE_ORDER = 'handle_order';

    public $client;

    public $notificationRepository;

    public $posNotificationRepository;

    public static $key = 'AAAApp6BE-o:APA91bG0jGRDd1btrzLB0jU7HP-iMA6RPqgVw6UaDzW0oqqJdRUz5tYgcwSO4YfBZgvqUBZeYGpOJs2vH-0P5VTzpo5mN_c7jPB-7qZG4r5FLqhWcj8tLv9JOZ5o2Aiutfu5oQeC9Oja';
    public static $pos_key = 'AAAAyD8GTdk:APA91bFc3JoEj_1OCHcU-2LC434NNcnceSG7asBm1G3q2eCMuesbu_Wn57P4XIZxDFvRRgUke6VtutBuASxX0ofij8vYQpKygcgB9tA8hjqYi_N-XGu7EUpDapRpNQ6QgdRlYMKPRfIu';

    public function __construct(NotificationRepository $notificationRepository)
    {
        $this->client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://fcm.googleapis.com',
            // You can set any number of default request options.
            'timeout' => 3,
        ]);
        $this->notificationRepository = $notificationRepository;
    }

    public function send($data)
    {
        $response = '';
        if (!empty($data['device_token'])) {
            $url = 'https://fcm.googleapis.com/fcm/send';
            $notification = [
                'body' => $data['text'],
                'title' => $data['title'],
                'sound' => 'default', /*Default sound*/
                //'icon' => !empty($data['icon']) ? Storage::disk('public')->url($data['icon']) : ''
            ];
            if(!empty($data['icon'])){
                $notification['icon'] = Storage::disk('public')->url($data['icon']);
            }
            $fields = array
            (
                'to' => $data['device_token'],
                'notification' => $notification,
                'data' => [
                    'item_type' => $data['item_type'],
                    'item_id' => $data['item_id'],
                    'click_action' => 'FLUTTER_NOTIFICATION_CLICK'
                ]
            );
            $headers = array(
                'Authorization' => 'key=' . self::$key,
                'Content-Type' => 'application/json'
            );
            $client = new Client();
            $option['verify'] = false;
            $option['http_errors'] = false;
            $option['headers'] = $headers;
            $option['json'] = $fields;
            $response = $client->request("POST", $url, $option);
        }
        $data['response'] = $response ? $response->getBody() : '';
        $this->notificationRepository->create($data);
        return $response ? json_decode($response->getBody(), true) : [];
    }

}
