<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\RegisterApplication;
use App\Services\PublicRegisterService;
use Illuminate\Http\Request;

/*
| ตรวจสลิปใบสมัครที่สมัครผ่านลิงก์ /join (ชำระแบบแนบสลิป)
| อนุมัติ -> สร้างสมาชิก/ออเดอร์/โบนัส (PublicRegisterService::activate)
*/
class RegisterApplicationController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;
        $apps = RegisterApplication::when($status, function ($q) use ($status) {
            $q->where('status', $status);
        })->orderBy('id', 'DESC')->limit(300)->get();

        return view('backend.register_applications.index', compact('apps', 'status'));
    }

    public function approve($id)
    {
        $app = RegisterApplication::findOrFail($id);
        if ($app->pay_method !== 'slip' || $app->status !== 'slip_pending') {
            return back()->withError('รายการนี้ไม่ได้อยู่ในสถานะรออนุมัติสลิป');
        }

        $result = PublicRegisterService::activate($app->id, [
            'gateway_status' => 'slip_approved',
            'paid_at' => now()->format('Y-m-d H:i:s'),
        ]);

        if ($result['status'] !== 'success') {
            return back()->withError('อนุมัติไม่สำเร็จ: ' . ($result['message'] ?? ''));
        }

        return back()->withSuccess('อนุมัติสำเร็จ สร้างรหัสสมาชิก ' . $result['user_name']);
    }

    public function reject(Request $request, $id)
    {
        $app = RegisterApplication::findOrFail($id);
        if ($app->status !== 'slip_pending') {
            return back()->withError('รายการนี้ไม่ได้อยู่ในสถานะรออนุมัติสลิป');
        }

        $app->status = 'failed';
        $app->note = 'ปฏิเสธสลิป: ' . $request->note;
        $app->save();

        return back()->withSuccess('ปฏิเสธรายการแล้ว');
    }
}
