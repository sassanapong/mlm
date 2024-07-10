<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Auth;
use Closure;
use User;
use Illuminate\Support\Facades\Cache;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // ตรวจสอบการล็อกอิน
        if (Auth::guard('admin')->check() && $request->isMethod('post')) {
            $userId = Auth::guard('admin')->id(); // ดึง User ID จาก 'admin' guard
            $url = $request->url();
            $key = 'prevent_repeated_clicks:' . $userId . ':' . md5($url);

            // ตรวจสอบว่ามี cache key นี้หรือไม่
            if (Cache::has($key)) {
                // แสดง Alert ด้วย JavaScript
                echo "<script>alert('กรุณารอ 5 วินาที ก่อนที่จะดำเนินการอีกครั้ง');</script>";
                exit; // หยุดการทำงานต่อไปหลังจากแสดง Alert
            }

            // ตั้งค่า cache key ด้วยเวลาหมดอายุ 15 วินาที
            Cache::put($key, true, 5);

            return $next($request);
        } else {
            return redirect('/admin');
        }
    }
}
