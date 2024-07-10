<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;

class Customer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // ตรวจสอบการล็อกอิน
        if (Auth::guard('c_user')->check() && $request->isMethod('post')) {
            $userId = Auth::guard('c_user')->id(); // ดึง User ID จาก c_user guard
            $url = $request->url();
            $key = 'prevent_repeated_clicks:' . $userId . ':' . md5($url);

            if (Cache::has($key)) {
                // แสดง Alert ด้วย JavaScript
                echo "<script>alert('กรุณารอ 5 วินาที ก่อนที่จะดำเนินการอีกครั้ง');</script>";
                exit; // หยุดการทำงานต่อไปหลังจากแสดง Alert
            }

            // ตั้งค่า cache ให้หมดอายุใน 5 วินาที
            Cache::put($key, true, 5);

            return $next($request);
        } else {
            return redirect('/');
        }
    }
}
