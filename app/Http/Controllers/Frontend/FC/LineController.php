<?php

namespace App\Http\Controllers\Frontend\FC;

use Illuminate\Http\Request;

use App\Http\Controllers\Api\LineMessagingServiceController;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class LineController extends Controller
{
    protected $lineService;

    public function __construct(LineMessagingServiceController $lineService)
    {
        $this->lineService = $lineService;
    }

    public function sendText($message, $img_url = null)
    {
        $userId = env('LINE_USER_ID'); // ID ของผู้รับ

        if ($this->lineService->sendMessage($userId, $message)) {
            if (!empty($img_url)) {
                $statusImage = $this->lineService->sendImage($userId, $img_url);
            }
            return response()->json(['status' => 'success', 'message' => 'ส่งข้อความสำเร็จ']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'ส่งข้อความล้มเหลว']);
        }
    }


    public  function sendTestMessage($user_name, $message_line, $register_user_name = null, $rs, $qualification_1, $code_order, $pv_total, $price_total_line, $img_url)
    {
        $userId = env('LINE_USER_ID'); // ID ของผู้รับ

        // สร้างข้อความ
        $message = "\n" . "รหัส : " .  $user_name . "\n";
        $message .= "ชื่อ: " . $message_line->first_name . "\n";
        if ($register_user_name) {
            $message .= "สมัครสมาชิก: " . $register_user_name . "\n";
        }

        $message .= "ผู้แนะนำ: " . $rs->sponsor . "\n";
        $message .= "ตำแหน่ง: " . $qualification_1->business_qualifications . "\n";
        $message .= "เลขรายการสินค้า: " . $code_order . "\n";
        $message .= "Pv รวม: " . number_format($pv_total) . "\n";
        $message .= "ยอดรวม: " . number_format($price_total_line) . " บาท";

        // ส่งข้อความไปยัง Line

        $statusText = $this->lineService->sendMessage($userId, $message);

        // ส่งรูปภาพไปยัง Line (ถ้ามี)
        if (!empty($img_url)) {
            $statusImage = $this->lineService->sendImage($userId, $img_url);
        }

        // ตรวจสอบผลลัพธ์
        if ($statusText && (!isset($statusImage) || $statusImage)) {
            return response()->json(['status' => 'success', 'message' => 'ส่งข้อความสำเร็จ']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'ส่งข้อความล้มเหลว']);
        }
    }



    public function LineWebhook(Request $request)
    {

        @app(\App\Http\Controllers\Frontend\FC\LineController::class)->sendText('test');
        // ตรวจสอบว่ามีข้อมูลเข้ามาหรือไม่
        if (!$request->isJson()) {
            Log::channel('payment')->info('รูปแบบข้อมูลไม่ถูกต้อง', ['data' => $request->all()]);
            return response()->json(['message' => 'Invalid request format'], 400);
        }

        $data = $request->all();

        // บันทึกข้อมูลลง Log
        Log::info($request->all());
        Log::channel('payment')->info('รับข้อมูลจาก LINE Webhook', ['data' => $data]);

        return response()->json(['message' => 'Webhook received successfully'], 200);
    }
}
