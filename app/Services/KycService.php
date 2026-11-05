<?php

namespace App\Services;

use App\CustomersBank;
use App\CustomersKyc;
use App\Customers;

// 1 Code 1 World: ตรวจข้อมูลซ้ำ สำหรับขั้นตอนสมัครสมาชิกใหม่
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
