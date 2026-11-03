<?php

namespace App\Services;

use App\CustomersBank;
use App\CustomersKyc;
use App\Customers;
use Illuminate\Support\Facades\Cache;

// 1 Code 1 World: ตรวจข้อมูลซ้ำ + OTP (mock) สำหรับขั้นตอนสมัครสมาชิกใหม่
class KycService
{
    public static function phoneTaken($phone, $exceptCustomerId = null)
    {
        return Customers::where('phone', $phone)
                ->when($exceptCustomerId, function ($q) use ($exceptCustomerId) { $q->where('id', '!=', $exceptCustomerId); })
                ->exists()
            || CustomersKyc::where('phone', $phone)
                ->when($exceptCustomerId, function ($q) use ($exceptCustomerId) { $q->where('customers_id', '!=', $exceptCustomerId); })
                ->exists();
    }

    public static function bankNoTaken($bankNo, $exceptCustomerId = null)
    {
        return CustomersBank::where('bank_no', $bankNo)
                ->when($exceptCustomerId, function ($q) use ($exceptCustomerId) { $q->where('customers_id', '!=', $exceptCustomerId); })
                ->exists()
            || CustomersKyc::where('bank_no', $bankNo)
                ->when($exceptCustomerId, function ($q) use ($exceptCustomerId) { $q->where('customers_id', '!=', $exceptCustomerId); })
                ->exists();
    }

    private static function otpKey($phone)
    {
        return 'reg_otp_' . session()->getId() . '_' . $phone;
    }

    // OTP (mock): สุ่มเลข 6 หลักส่งกลับไปแสดงบนหน้าจอ เมื่อต่อ SMS จริงให้ส่ง SMS และเลิกคืนค่า OTP
    public static function sendOtp($phone)
    {
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put(self::otpKey($phone), $otp, now()->addMinutes(5));
        return $otp;
    }

    // ยืนยัน OTP แล้วจำไว้ใน session ว่าเบอร์นี้ผ่านแล้ว (ตรวจซ้ำตอน submit ได้โดยไม่ต้องกรอกอีก)
    public static function verifyOtp($phone, $otp)
    {
        if (session('reg_otp_ok') === $phone) {
            return true;
        }
        if ($otp && Cache::get(self::otpKey($phone)) === (string) $otp) {
            session(['reg_otp_ok' => $phone]);
            Cache::forget(self::otpKey($phone));
            return true;
        }
        return false;
    }

    // สมาชิกใหม่ส่งเอกสารครบตอนสมัคร: บันทึกเป็น "รอตรวจ" เพื่อให้แอดมินตรวจ และไม่ถูกบังคับยืนยันซ้ำ
    public static function registerPending($customerId, $userName, $idCard, $phone, $cardUrl, $cardFile, $bankNo, $bankId, $bankName, $bankUrl, $bankFile)
    {
        CustomersKyc::updateOrCreate(['customers_id' => $customerId], [
            'user_name' => $userName,
            'id_card' => $idCard,
            'img_card' => $cardUrl . '/' . $cardFile,
            'bank_no' => $bankNo,
            'bank_id_fk' => $bankId,
            'bank_name' => $bankName,
            'img_bank' => $bankUrl . '/' . $bankFile,
            'phone' => $phone,
            'terms_accepted_at' => now(),
            'status' => CustomersKyc::STATUS_PENDING,
            'note' => null,
        ]);
    }
}
