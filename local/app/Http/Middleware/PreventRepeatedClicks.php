<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request; // แก้จาก Illuminate\Support\Facades\Request เป็น Illuminate\Http\Request

class PreventRepeatedClicks
{

    //dsdsd
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $url = $request->fullUrl(); // ใช้ $request ที่รับเข้ามาจาก Illuminate\Http\Request
        $key = 'prevent_repeated_clicks:' . $request->ip() . ':' . md5($url);

        if (Cache::has($key)) {
            // แสดง Alert ด้วย JavaScript
            echo "<script>alert('กรุณารอ 5 วินาที ก่อนที่จะดำเนินการอีกครั้ง');</script>";
            exit; // หยุดการทำงานต่อไปหลังจากแสดง Alert
        }
        Cache::put($key, true, 5);

        return $next($request);
    }
}
