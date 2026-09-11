<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\eWallet;
use DB;

/**
 * โบนัส STAR ReCash
 *
 * โบนัสขยายธุรกิจชั้นที่ 1 จ่ายตามตำแหน่งของผู้แนะนำ MB=50% MO=70% VIP=90% และ VVIP ขึ้นไป=120%
 * ถ้าชั้นที่ 1 ได้ต่ำกว่าเพดาน 120% ส่วนต่างที่เหลือจะวิ่งขึ้นไปตามสายผู้แนะนำ (introduce_id)
 * จ่ายให้ "คนแรก" ที่ถือตำแหน่ง STAR ขึ้นไป และ expire_date_bonus ยังมากกว่าวันปัจจุบัน
 *
 * จ่ายทันที เก็บรายการไว้ที่ตาราง report_bonus_star_recash
 *
 * ต้นทางที่เรียกใช้ (ทุกที่ที่มีโบนัสชั้นที่ 1 แบบเพดาน 120%)
 *   - สมัครสมาชิก      RegisterController            -> pay(...,'register')      อ่าน report_bonus_register
 *   - สมัครผ่านลิงก์    RegisterUrlController        -> pay(...,'register_url')  อ่าน report_bonus_register
 *   - อัพตำแหน่ง       JPController                 -> pay(...,'jangpv')        อ่าน report_bonus_register
 *   - ยืนยันสิทธิ์      BonusActiveController        -> payFromActive($code)     อ่าน report_bonus_active
 *
 * ต้องเรียกภายใน transaction ของผู้เรียก
 */
class StarReCashController extends Controller
{
    /** เพดานโบนัสขยายธุรกิจชั้นที่ 1 */
    const FULL_RATE = 120;

    /** เปอร์เซ็นต์ของชั้นที่ 1 ที่ทำให้เกิดส่วนต่าง */
    const G1_RATES = [50, 70, 90];

    /** ภาษีหัก ณ ที่จ่าย (%) ใช้อัตราเดียวกับโบนัสข้ออื่น */
    const TAX_PERCEN = 3;

    /** ประเภทรายการใน ewallet (1-15 ถูกใช้ไปแล้ว) */
    const EWALLET_TYPE = 16;

    /** กัน loop ไม่รู้จบกรณีสายงานอ้างวนกันเอง */
    const MAX_HOP = 200;

    /**
     * ตำแหน่งที่มีสิทธิ์รับ STAR ReCash ระบุเป็นชื่อที่ใช้เรียกทางธุรกิจ
     *
     * ระวัง: customers.qualification_id เก็บเป็น dataset_qualification.code
     * ซึ่งไม่ตรงกับชื่อเรียก (เช่น ชื่อ "MG" มี code = "XVVIP", ชื่อ "MD" มี code = "MR")
     * จึงต้องแปลงผ่าน business_qualifications ทุกครั้ง ห้าม hard-code code ตรง ๆ
     */
    public static $positions = [
        'STAR',
        'MDK STAR',
        'MG',
        'ME',
        'MR',
        'MD',
        'MDD.',
        'MCD.',
        'MCK.',
    ];

    /**
     * @return array  ['<code>' => '<ชื่อที่ใช้เรียก>', ...]
     */
    public static function position_codes()
    {
        return DB::table('dataset_qualification')
            ->whereIn('business_qualifications', self::$positions)
            ->pluck('business_qualifications', 'code')
            ->toArray();
    }

    /**
     * ต้นทาง report_bonus_register (สมัครสมาชิก / สมัครผ่านลิงก์ / อัพตำแหน่ง)
     *
     * @param  string  $code_bonus       code_bonus ชุดเดียวกับ report_bonus_register
     * @param  string  $ref_user_name    รหัสเจ้าของรายการ (regis_user_name)
     * @param  string  $type             register | register_url | jangpv
     * @return array
     */
    public static function pay($code_bonus, $ref_user_name, $type = 'register')
    {
        $g1 = DB::table('report_bonus_register')
            ->where('code_bonus', '=', $code_bonus)
            ->where('regis_user_name', '=', $ref_user_name)
            ->where('g', '=', 1)
            ->first();

        if (empty($g1)) {
            return ['status' => 'skip', 'message' => 'ไม่พบรายการโบนัสชั้นที่ 1'];
        }

        return self::payFromG1([
            'code_bonus' => $code_bonus,
            'ref_code' => null,
            'ref_user_name' => $ref_user_name,
            'ref_name' => $g1->regis_name,
            'pv' => $g1->pv,
            'g1_user_name' => $g1->user_name_g,
            'g1_name' => $g1->name_g,
            'g1_qualification' => $g1->qualification,
            'g1_percen' => $g1->percen,
        ], $type);
    }

    /**
     * ต้นทาง report_bonus_active (ยืนยันสิทธิ์)
     *
     * ใช้ code ของ jang_pv เป็นตัวอ้างอิง เพราะ report_bonus_active
     * สร้าง code_bonus ไหม่ทุกชั้น ไม่ได้ใช้ชุดเดียวกันทั้ง 5 ชั้น
     *
     * @param  string  $jang_pv_code
     * @return array
     */
    public static function payFromActive($jang_pv_code)
    {
        $g1 = DB::table('report_bonus_active')
            ->where('code', '=', $jang_pv_code)
            ->where('g', '=', 1)
            ->first();

        if (empty($g1)) {
            return ['status' => 'skip', 'message' => 'ไม่พบรายการโบนัสชั้นที่ 1'];
        }

        return self::payFromG1([
            'code_bonus' => $g1->code_bonus,
            'ref_code' => $jang_pv_code,
            'ref_user_name' => $g1->user_name,
            'ref_name' => $g1->name,
            'pv' => $g1->pv,
            'g1_user_name' => $g1->user_name_g,
            'g1_name' => $g1->name_g,
            'g1_qualification' => $g1->qualification,
            'g1_percen' => $g1->percen,
        ], 'active');
    }

    /**
     * แกนกลางของโบนัส ใช้ร่วมกันทุกต้นทาง
     *
     * @param  array   $g1    code_bonus, ref_code, ref_user_name, ref_name, pv,
     *                        g1_user_name, g1_name, g1_qualification, g1_percen
     * @param  string  $type  register | register_url | jangpv | active
     * @return array          ['status' => success|notfound|skip|fail, 'message' => ...]
     */
    public static function payFromG1(array $g1, $type = 'register')
    {
        try {
            $g1_percen = (int) $g1['g1_percen'];

            // ชั้นที่ 1 ได้เต็มเพดาน 120% แล้ว (หรือเป็น MC ที่ไม่ได้โบนัส) ไม่มีส่วนต่างให้วิ่ง
            if (!in_array($g1_percen, self::G1_RATES, true)) {
                return ['status' => 'skip', 'message' => 'ชั้นที่ 1 ไม่มีส่วนต่าง (' . $g1_percen . '%)'];
            }

            // กันจ่ายซ้ำ ถ้าถูกเรียกมากกว่า 1 ครั้งกับรายการเดียวกัน
            $dup = DB::table('report_bonus_star_recash');

            if (!empty($g1['ref_code'])) {
                $dup->where('ref_code', '=', $g1['ref_code'])->where('type', '=', $type);
            } else {
                $dup->where('code_bonus', '=', $g1['code_bonus'])
                    ->where('regis_user_name', '=', $g1['ref_user_name']);
            }

            if (!empty($dup->first())) {
                return ['status' => 'skip', 'message' => 'รายการนี้ถูกบันทึกไปแล้ว'];
            }

            $percen = self::FULL_RATE - $g1_percen;
            $pv = (float) $g1['pv'];

            $bonus_full = round($pv * $percen / 100, 2);
            $tax_total = round($bonus_full * self::TAX_PERCEN / 100, 2);
            $bonus = round($bonus_full - $tax_total, 2);

            $row = [
                'code_bonus' => $g1['code_bonus'],
                'ref_code' => $g1['ref_code'],
                'regis_user_name' => $g1['ref_user_name'],
                'regis_name' => $g1['ref_name'],
                'pv' => $g1['pv'],

                'g1_user_name' => $g1['g1_user_name'],
                'g1_name' => $g1['g1_name'],
                'g1_qualification' => $g1['g1_qualification'],
                'g1_percen' => $g1_percen,

                'percen' => $percen,
                'tax_percen' => self::TAX_PERCEN,
                'bonus_full' => $bonus_full,
                'tax_total' => $tax_total,
                'bonus' => $bonus,

                'type' => $type,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $codes = self::position_codes();
            $receiver = self::find_receiver($g1['g1_user_name'], array_keys($codes));

            // ไม่มีใครในสายงานด้านบนเข้าเงื่อนไข บันทึกไว้เป็นหลักฐานว่าส่วนต่างนี้ไม่ได้จ่ายใคร
            if (empty($receiver)) {
                $row['status'] = 'notfound';
                $row['note'] = 'ไม่พบผู้แนะนำด้านบนที่ถือตำแหน่ง STAR ขึ้นไป และ expire_date_bonus มากกว่าวันปัจจุบัน';

                DB::table('report_bonus_star_recash')->insert($row);

                return ['status' => 'notfound', 'message' => $row['note']];
            }

            // ---- จ่ายเข้า eWallet ทันที ----
            $wallet_old = empty($receiver->ewallet) ? 0 : $receiver->ewallet;
            $use_old = empty($receiver->ewallet_use) ? 0 : $receiver->ewallet_use;
            $bonus_total_old = empty($receiver->bonus_total) ? 0 : $receiver->bonus_total;

            $wallet_new = $wallet_old + $bonus;
            $use_new = $use_old + $bonus;
            $bonus_total_new = $bonus_total_old + $bonus;

            DB::table('customers')
                ->where('user_name', '=', $receiver->user_name)
                ->update([
                    'ewallet' => $wallet_new,
                    'ewallet_use' => $use_new,
                    'bonus_total' => $bonus_total_new,
                ]);

            $ewallet = new eWallet();
            $ewallet->transaction_code = $g1['code_bonus'];
            $ewallet->customers_id_fk = $receiver->id;
            $ewallet->customer_username = $receiver->user_name;
            $ewallet->tax_percen = self::TAX_PERCEN;
            $ewallet->tax_total = $tax_total;
            $ewallet->bonus_full = $bonus_full;
            $ewallet->amt = $bonus;
            $ewallet->old_balance = $wallet_old;
            $ewallet->balance = $wallet_new;
            $ewallet->type = self::EWALLET_TYPE;
            $ewallet->note_orther = 'โบนัส STAR ReCash ' . $percen . '% (' . self::type_label($type) . ') รหัส ' . $receiver->user_name
                . ' จากชั้นที่ 1 รหัส ' . $g1['g1_user_name'] . ' (' . $g1_percen . '%)'
                . ' รายการของรหัส ' . $g1['ref_user_name'];
            $ewallet->receive_date = now();
            $ewallet->receive_time = now();
            $ewallet->status = 2;
            $ewallet->save();

            $row['transaction_code'] = $g1['code_bonus'];
            $row['user_name_g'] = $receiver->user_name;
            $row['name_g'] = trim($receiver->name . ' ' . $receiver->last_name);
            $row['qualification'] = $receiver->qualification_id;
            $row['qualification_name'] = $receiver->business_qualifications;
            $row['expire_date_bonus'] = $receiver->expire_date_bonus;
            $row['level_up'] = $receiver->level_up;
            $row['status'] = 'success';

            DB::table('report_bonus_star_recash')->insert($row);

            return [
                'status' => 'success',
                'message' => 'จ่าย STAR ReCash ' . number_format($bonus, 2) . ' บาท ให้รหัส ' . $receiver->user_name,
                'user_name_g' => $receiver->user_name,
                'bonus' => $bonus,
            ];
        } catch (\Exception $e) {
            // ไม่ throw ต่อ เพื่อไม่ให้รายการหลัก (สมัคร/อัพตำแหน่ง/ยืนยันสิทธิ์) ล้มเหลวเพราะโบนัสข้อนี้
            \Log::error('STAR ReCash fail: ' . $e->getMessage(), [
                'type' => $type,
                'code_bonus' => isset($g1['code_bonus']) ? $g1['code_bonus'] : null,
                'ref_code' => isset($g1['ref_code']) ? $g1['ref_code'] : null,
                'ref_user_name' => isset($g1['ref_user_name']) ? $g1['ref_user_name'] : null,
            ]);

            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }

    /**
     * ชื่อต้นทางที่แสดงในหมายเหตุ eWallet
     */
    public static function type_label($type)
    {
        if ($type == 'jangpv') {
            return 'อัพตำแหน่ง';
        }

        if ($type == 'active') {
            return 'ยืนยันสิทธิ์';
        }

        return 'สมัครสมาชิก';
    }


    /**
     * ไต่สายผู้แนะนำขึ้นไปจาก $from_user_name หา "คนแรก" ที่เข้าเงื่อนไข
     * เริ่มนับจาก introduce_id ของ $from_user_name (ไม่นับตัวเอง เพราะชั้นที่ 1 ได้โบนัสไปแล้ว)
     *
     * @param  string  $from_user_name  รหัสของชั้นที่ 1
     * @param  array   $codes           dataset_qualification.code ที่มีสิทธิ์รับ
     * @return object|null
     */
    public static function find_receiver($from_user_name, array $codes)
    {
        if (empty($from_user_name) || empty($codes)) {
            return null;
        }

        $start = DB::table('customers')
            ->select('introduce_id')
            ->where('user_name', '=', $from_user_name)
            ->first();

        if (empty($start)) {
            return null;
        }

        $today = date('Y-m-d');
        $next = $start->introduce_id;
        $level = 0;

        while (!empty($next) && $next != 'AA' && $level < self::MAX_HOP) {
            $level++;

            $up = DB::table('customers')
                ->select(
                    'customers.id',
                    'customers.name',
                    'customers.last_name',
                    'customers.user_name',
                    'customers.introduce_id',
                    'customers.qualification_id',
                    'customers.status_customer',
                    'customers.expire_date_bonus',
                    'customers.ewallet',
                    'customers.ewallet_use',
                    'customers.bonus_total',
                    'dataset_qualification.business_qualifications'
                )
                ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                ->where('customers.user_name', '=', $next)
                ->first();

            // ไม่มีรหัสนี้ในระบบ สุดสายเเล้ว
            if (empty($up)) {
                return null;
            }

            if (
                in_array($up->qualification_id, $codes, true)
                && $up->status_customer != 'cancel'
                && !empty($up->expire_date_bonus)
                && strtotime($up->expire_date_bonus) > strtotime($today)
            ) {
                $up->level_up = $level;

                return $up;
            }

            $next = $up->introduce_id;
        }

        return null;
    }
}
