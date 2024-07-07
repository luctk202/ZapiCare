<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 7/25/18
 * Time: 4:57 PM
 */

namespace App\Support\Telegram;

use GuzzleHttp\Client;

class TelegramService
{
    private static $_url = 'https://api.telegram.org/bot';
    private static $_token = '7169414896:AAHpPeRzdEBP0eQ4ug8POJgLcg8URSGTPfc';
    private static $_chat_id = '-4197196409';

    public function __construct()
    {

    }

    public static function sendMessage($text)
    {
        $uri = self::$_url . self::$_token . '/sendMessage?parse_mode=html';
        $params = [
            'chat_id' => self::$_chat_id,
            'text' => $text,
        ];
        $option['verify'] = false;
        $option['form_params'] = $params;
        $option['http_errors'] = false;
        $client = new Client();
        $response = $client->request("POST", $uri, $option);
        return json_decode($response->getBody(), true);
    }

}
