<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Jobs\ReadSmsBank;
use App\Support\Telegram\TelegramService;
use Illuminate\Http\Request;

class SmsController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        //TelegramService::sendMessage(json_encode($request->all()));
        $text = $request->bellhome_sms_forward ?? '';
        if(!empty($text)){
            ReadSmsBank::dispatch($text)->onQueue('default');
            return response([
                'result' => true
            ]);
        }

    }

}
