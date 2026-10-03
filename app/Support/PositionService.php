<?php

namespace App\Support;

/*
|--------------------------------------------------------------------------
| PositionService
|--------------------------------------------------------------------------
|
| ศูนย์กลางของตรรกะ "อัพตำแหน่ง" ทั้งระบบ
|
| เดิมตรรกะนี้กระจายอยู่หลายที่ (JPController, ConfirmCartController,
| Register*Controller) ทำให้เกณฑ์ PV ไม่ตรงกันและแก้ยาก
| ต่อไปถ้าจะเปลี่ยนเกณฑ์ PV หรือลำดับตำแหน่ง ให้แก้ที่ไฟล์นี้ที่เดียว
|
*/

class PositionService
{
    /*
    |--------------------------------------------------------------------------
    | ตำแหน่งเริ่มต้น
    |--------------------------------------------------------------------------
    */
    const DEFAULT_POSITION = 'MC';

    /*
    |--------------------------------------------------------------------------
    | จำนวนวันที่ต่ออายุต่อการแจงอัพ 1 ครั้ง
    |--------------------------------------------------------------------------
    */
    const EXTEND_DAYS = 33;

    /*
    |--------------------------------------------------------------------------
    | บันไดตำแหน่ง (เรียงจากต่ำไปสูง)
    |--------------------------------------------------------------------------
    |
    | pv = PV สะสม (pv_upgrad รวม) ขั้นต่ำที่ต้องถึงจึงจะได้ตำแหน่งนั้น
    |
    | *** แก้เกณฑ์ PV ที่นี่ที่เดียว ***
    |
    */
    const LADDER = [
        ['code' => 'MC',   'pv' => 0],
        ['code' => 'MB',   'pv' => 20],
        ['code' => 'MO',   'pv' => 270],
        ['code' => 'VIP',  'pv' => 900],
        ['code' => 'VVIP', 'pv' => 2700],
    ];

    /*
    |--------------------------------------------------------------------------
    | โปรเปิดตัว: ตำแหน่งตอนสมัคร (ใช้เฉพาะหน้าสมัครเท่านั้น)
    |--------------------------------------------------------------------------
    |
    | สมัครด้วย PV ถึงเกณฑ์ -> ได้ตำแหน่งนี้ทันที
    | ไม่เกี่ยวกับ LADDER / calculate() ที่ใช้แจงอัพตำแหน่งภายหลัง
    | จบโปร: ลบค่าในนี้ให้เป็น [] ก็จะกลับไปใช้ LADDER ปกติ
    |
    */
    const REGISTER_PROMO = [
        ['code' => 'VVIP', 'pv' => 800],
        ['code' => 'STAR', 'pv' => 1600],
    ];

    /*
    |--------------------------------------------------------------------------
    | PV ขั้นต่ำที่ต่ออายุ expire_date_bonus / expire_date_bonus_balance ด้วย
    |--------------------------------------------------------------------------
    |
    | ต่ำกว่านี้ (แต่ถึง MB) จะต่อแค่ expire_date อย่างเดียว
    |
    */
    const PV_EXTEND_BONUS = 270;

    /*
    |--------------------------------------------------------------------------
    | Normalize Qualification
    |--------------------------------------------------------------------------
    |
    | ค่าว่าง / null / '-' ให้ถือเป็น MC
    |
    */
    public static function normalize($qualification)
    {
        if (empty($qualification) || $qualification === '-') {
            return self::DEFAULT_POSITION;
        }

        return $qualification;
    }

    /*
    |--------------------------------------------------------------------------
    | ลำดับขั้นของตำแหน่ง
    |--------------------------------------------------------------------------
    |
    | MC = 0, MB = 1, MO = 2, VIP = 3, VVIP = 4
    | ตำแหน่งที่ไม่อยู่ในบันได (STAR, MG, MR ฯลฯ) คืน null
    |
    */
    public static function rank($position)
    {
        foreach (self::LADDER as $index => $step) {
            if ($step['code'] === $position) {
                return $index;
            }
        }

        return null;
    }

    /*
    |--------------------------------------------------------------------------
    | PV สะสม -> ตำแหน่งสูงสุดที่ถึงเกณฑ์
    |--------------------------------------------------------------------------
    */
    public static function fromPv($pvTotal)
    {
        $pvTotal = (float) $pvTotal;
        $position = self::DEFAULT_POSITION;

        foreach (self::LADDER as $step) {
            if ($pvTotal >= $step['pv']) {
                $position = $step['code'];
            }
        }

        return $position;
    }

    /*
    |--------------------------------------------------------------------------
    | ตำแหน่งตอนสมัคร (LADDER ปกติ + โปรเปิดตัว)
    |--------------------------------------------------------------------------
    */
    public static function fromRegisterPv($pv)
    {
        $position = self::fromPv($pv);

        foreach (self::REGISTER_PROMO as $step) {
            if ((float) $pv >= $step['pv']) {
                $position = $step['code'];
            }
        }

        return $position;
    }

    /*
    |--------------------------------------------------------------------------
    | คำนวณตำแหน่งใหม่
    |--------------------------------------------------------------------------
    |
    | กติกา: ตำแหน่งใหม่ = ค่าที่สูงกว่าระหว่าง "ตำแหน่งเดิม" กับ "ตำแหน่งตาม PV"
    |        ไม่มีการลดตำแหน่งลง
    |
    | ตำแหน่งที่ไม่อยู่ในบันได (STAR, MG, MR, ME, MD ฯลฯ) จะคืนค่าเดิม
    | ไม่ถูกแตะต้อง
    |
    */
    public static function calculate($oldPosition, $pvUpgradTotal)
    {
        $oldPosition = self::normalize($oldPosition);
        $oldRank = self::rank($oldPosition);

        // ตำแหน่งนอกบันได ไม่ยุ่ง
        if ($oldRank === null) {
            return $oldPosition;
        }

        $newPosition = self::fromPv($pvUpgradTotal);
        $newRank = self::rank($newPosition);

        return $newRank > $oldRank ? $newPosition : $oldPosition;
    }

    /*
    |--------------------------------------------------------------------------
    | ต่ออายุวันหมดอายุ
    |--------------------------------------------------------------------------
    |
    | - ไม่มีวันหมดอายุเดิม     -> วันนี้ + 33 วัน
    | - เหลือน้อยกว่า 33 วัน    -> ดันให้ครบ 33 วันนับจากวันนี้
    | - เหลือตั้งแต่ 33 วันขึ้นไป -> คงเดิม
    |
    */
    public static function extendExpireDate($expireDate)
    {
        $days = self::EXTEND_DAYS;

        if (empty($expireDate)) {
            return date('Y-m-d', strtotime("+{$days} day"));
        }

        $today = strtotime(date('Y-m-d'));
        $expireTime = strtotime($expireDate);

        $daysDiff = ceil(($expireTime - $today) / 86400);

        if ($daysDiff < $days) {
            $daysToAdd = $days - $daysDiff;

            return date('Y-m-d', strtotime("+{$daysToAdd} day", $expireTime));
        }

        return $expireDate;
    }

    /*
    |--------------------------------------------------------------------------
    | คำนวณวันหมดอายุใหม่จาก PV ที่แจงเข้ามาครั้งนี้
    |--------------------------------------------------------------------------
    |
    | $current = [
    |     'expire_date'               => ...,
    |     'expire_date_bonus'         => ...,
    |     'expire_date_bonus_balance' => ...,
    | ]
    |
    | เกณฑ์:
    |   - PV < 20            -> ไม่ต่ออะไรเลย
    |   - PV 20 ถึง < 270    -> ต่อ expire_date
    |   - PV >= 270          -> ต่อทั้ง 3 ค่า
    |
    */
    public static function expireDates(array $current, $pvInput)
    {
        $pvInput = (float) $pvInput;

        $expire_date = $current['expire_date'] ?? null;
        $expire_date_bonus = $current['expire_date_bonus'] ?? null;
        $expire_date_bonus_balance = $current['expire_date_bonus_balance'] ?? null;

        $pvMinUpgrade = self::LADDER[1]['pv']; // PV ขั้นต่ำที่ถือว่าแจงอัพ (MB)

        if ($pvInput >= $pvMinUpgrade) {
            $expire_date = self::extendExpireDate($expire_date);
        }

        if ($pvInput >= self::PV_EXTEND_BONUS) {
            $expire_date_bonus = self::extendExpireDate($expire_date_bonus);
            $expire_date_bonus_balance = self::extendExpireDate($expire_date_bonus_balance);
        }

        return [
            'expire_date' => $expire_date,
            'expire_date_bonus' => $expire_date_bonus,
            'expire_date_bonus_balance' => $expire_date_bonus_balance,
        ];
    }
}
