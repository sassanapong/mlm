<?php

namespace App\Support;

use App\Customers;
use App\CustomersBank;

/**
 * เงื่อนไขกันข้อมูลซ้ำตอนสมัครสมาชิกใหม่ (ใช้ร่วมกันทุกช่องทางสมัคร)
 *  - บัตรประชาชน 1 ใบ สมัครได้ 1 รหัส
 *  - เบอร์โทร 1 เบอร์ สมัครได้ 1 รหัส
 *  - เลขบัญชีธนาคาร 1 บัญชี สมัครได้ 1 รหัส
 */
class RegisterGuard
{
    public const MSG_ID_CARD = 'เลขบัตรประชาชนนี้ลงทะเบียนครบ 1 รหัสแล้ว ไม่สามารถลงทะเบียนเพิ่มได้';
    public const MSG_PHONE = 'เบอร์โทรศัพท์นี้ถูกใช้สมัครสมาชิกแล้ว ไม่สามารถสมัครซ้ำได้';
    public const MSG_BANK = 'เลขบัญชีธนาคารนี้ถูกใช้สมัครสมาชิกแล้ว ไม่สามารถสมัครซ้ำได้';

    public static function phoneExists($phone): bool
    {
        $phone = trim((string) $phone);
        return $phone !== '' && Customers::where('phone', $phone)->exists();
    }

    public static function bankNoExists($bank_no): bool
    {
        $bank_no = preg_replace('/\D+/', '', (string) $bank_no);
        return $bank_no !== '' && CustomersBank::where('bank_no', $bank_no)->exists();
    }

    /** ข้อความผิดพลาดตัวแรกที่พบ หรือ null ถ้าไม่ซ้ำ ($checkIdCard=false ถ้าเช็คแยกไว้แล้ว) */
    public static function duplicateMessage($id_card, $phone, $bank_no, bool $checkIdCard = true): ?string
    {
        if ($checkIdCard && Customers::where('id_card', $id_card)->exists()) {
            return self::MSG_ID_CARD;
        }
        if (self::phoneExists($phone)) {
            return self::MSG_PHONE;
        }
        if (self::bankNoExists($bank_no)) {
            return self::MSG_BANK;
        }
        return null;
    }
}
