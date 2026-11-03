<?php

namespace App\Http\Middleware;

use App\CustomersKyc;
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
        if (!Auth::guard('c_user')->check()) {
            return redirect('/');
        }

        return $this->kycGate($request, $next);
    }

    // 1 Code 1 World: ยังไม่ส่งข้อมูล/ถูกตีกลับ ต้องยืนยันตัวตนที่หน้า kyc ก่อน ทำอย่างอื่นไม่ได้
    private function kycGate($request, Closure $next)
    {
        if (!config('kyc.enabled')) {
            return $next($request);
        }

        $route = optional($request->route())->getName();
        if (in_array($route, config('kyc.allow_routes'), true) || strpos((string) $route, 'payso') !== false) {
            return $next($request);
        }

        $kyc = CustomersKyc::where('customers_id', Auth::guard('c_user')->id())->first();
        if ($kyc && $kyc->status != CustomersKyc::STATUS_REJECTED) {
            return $next($request);
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['status' => 'kyc_required', 'error' => ['kyc' => ['กรุณายืนยันตัวตน 1 Code 1 World ก่อนใช้งาน']]], 403);
        }
        return redirect()->route('kyc');
    }
}
