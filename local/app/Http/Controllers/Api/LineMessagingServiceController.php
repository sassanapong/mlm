<?php

namespace App\Http\Controllers\Api;

use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use LINE\LINEBot;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use App\Http\Controllers\Controller;


class LineMessagingServiceController extends Controller
{
    protected $bot;

    public function __construct()
    {
        $httpClient = new CurlHTTPClient(env('LINE_CHANNEL_ACCESS_TOKEN'));
        $this->bot = new LINEBot($httpClient, ['channelSecret' => env('LINE_CHANNEL_SECRET')]); // เปลี่ยนจาก LINE_USER_ID เป็น LINE_CHANNEL_SECRET

    }

    public function sendMessage($userId, $message)
    {
        $textMessageBuilder = new TextMessageBuilder($message);
        // dd($textMessageBuilder);
        $response = $this->bot->pushMessage($userId, $textMessageBuilder);

        if (!$response->isSucceeded()) {
            $errorMessage = $response->getRawBody(); // ดูข้อมูลที่ไม่ได้รับการตอบรับ
            // log หรือ debug ข้อความที่ได้รับ
            // dd($errorMessage);
        }
        return $response->isSucceeded();
    }

    public function sendImage($userId, $imageUrl)
    {
        $messageBuilder = new \LINE\LINEBot\MessageBuilder\ImageMessageBuilder($imageUrl, $imageUrl);

        $response = $this->bot->pushMessage($userId, $messageBuilder);

        if (!$response->isSucceeded()) {
            $errorMessage = $response->getRawBody();
            // คุณจะใส่ log หรือ debug ได้ตรงนี้ถ้าต้องการ
        }

        return $response->isSucceeded();
    }
}
