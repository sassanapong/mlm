<?php

namespace App\Http\Controllers\Frontend\FC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customers;
use App\Jang_pv;
use DB;

/**
 * ลด PV คงเหลือของลูกค้าแบบ Manual (รันเอง ไม่ใช่ Cron)
 *
 * เงื่อนไข : PV ใหม่ = 90% ของ PV เดิม (ตัดออก 10%)
 * ทุกค่าเป็นจำนวนเต็ม ไม่มีทศนิยม ปัดเศษแบบ ต่ำกว่า .5 ปัดลง ตั้งแต่ .5 ขึ้นไปปัดขึ้น
 * เช่น PV 1005 => เหลือ 905 ตัด 100 (904.5 ปัดขึ้น) , PV 999 => เหลือ 899 ตัด 100 (899.1 ปัดลง)
 *
 * ยอดที่ถูกตัดถูกบันทึกลงตาราง jang_pv 1 แถวต่อ 1 ลูกค้า
 * (pv_old = PV ก่อนตัด, pv = ยอดที่ตัด, pv_balance = PV หลังตัด)
 * pv_old - pv = pv_balance เสมอ
 *
 * ตัวอย่างการเรียก
 *   ดูผลก่อน (ไม่บันทึก) : /reduce_pv_preview
 *   รันจริงทั้งระบบ       : /reduce_pv_run?confirm=RUN
 *   เฉพาะบางรหัส        : /reduce_pv_run?user_name=A0001,A0002&confirm=RUN
 */
class ReducePvManualController extends Controller
{
    dd('class ReducePvManualController is disabled, please use ReducePvCronController instead');
    /** เปอร์เซ็นต์ PV ที่ให้คงเหลือ (90 = เหลือ 90% ตัดออก 10%) */
    const PERCENT_KEEP = 90;

    /** type ของรายการใน jang_pv สำหรับการตัด PV แบบ manual (เพิ่มชื่อใน jang_type ให้ตรงด้วย) */
    const JANG_TYPE = 9;

    /** จำนวนรายการสูงสุดที่ส่งกลับใน detail (กัน JSON ใหญ่เกินไป) ยอดรวมยังนับครบทุกคน */
    const DETAIL_LIMIT = 500;

    /**
     * ดูผลลัพธ์ก่อนรันจริง ไม่มีการเขียน DB
     */
    public function reduce_pv_preview(Request $rs)
    {
        return response()->json($this->reduce_pv($rs, false), 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * รันจริง ต้องส่ง confirm=RUN มาด้วย
     */
    public function reduce_pv_run(Request $rs)
    {
        if ($rs->input('confirm') !== 'RUN') {
            return response()->json([
                'status'  => 'error',
                'message' => 'ต้องส่ง confirm=RUN เพื่อยืนยันการตัด PV จริง (แนะนำให้เรียก reduce_pv_preview ดูผลก่อน)',
            ], 400, [], JSON_UNESCAPED_UNICODE);
        }

        return response()->json($this->reduce_pv($rs, true), 200, [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * ตัวหลักของการตัด PV รันรวดเดียวทั้งหมดใน transaction เดียว
     *
     * @param  bool  $commit  true = บันทึกจริง, false = preview เฉยๆ
     */
    private function reduce_pv(Request $rs, $commit)
    {
        set_time_limit(0);
        ini_set('memory_limit', '-1');

        $limit = (int) $rs->input('limit', 0);

        $user_name = array_values(array_filter(array_map(
            'trim',
            explode(',', (string) $rs->input('user_name', ''))
        ), function ($v) {
            return $v !== '';
        }));

        $batch = 'RPV' . date('YmdHis');

        $query = DB::table('customers')
            ->select('id', 'user_name', 'pv', 'qualification_id')
            ->where('pv', '>', 0)
            ->where(function ($w) {
                $w->where('status_customer', '<>', 'cancel')
                    ->orWhereNull('status_customer');
            })
            ->orderBy('id');

        if (!empty($user_name)) {
            $query->whereIn('user_name', $user_name);
        }

        if ($limit > 0) {
            $query->limit($limit);
        }

        $customers = $query->get();

        if ($customers->isEmpty()) {
            return [
                'status'  => 'success',
                'message' => 'ไม่มีรายชื่อที่เข้าเงื่อนไข',
                'batch'   => $batch,
                'total'   => 0,
                'detail'  => [],
            ];
        }

        $detail     = [];
        $error      = [];
        $sum_pv_old = 0;
        $sum_pv_cut = 0;
        $sum_pv_new = 0;
        $total      = 0;

        if ($commit) {
            DB::beginTransaction();
        }

        try {
            foreach ($customers as $row) {

                // preview ใช้ค่าจาก query ได้เลย, รันจริงต้องอ่านซ้ำแบบ lock กัน PV เปลี่ยนระหว่างรัน
                if ($commit) {
                    $customer_update = Customers::lockForUpdate()->find($row->id);

                    if (empty($customer_update)) {
                        $error[] = ['user_name' => $row->user_name, 'message' => 'ไม่พบรหัสนี้ในระบบ'];
                        continue;
                    }

                    $pv_old = self::to_int((float) $customer_update->pv);
                } else {
                    $customer_update = null;
                    $pv_old          = self::to_int((float) $row->pv);
                }

                // ทั้งหมดเป็นจำนวนเต็ม, + 50 คือการปัดเศษ .5 ขึ้น โดยไม่ผ่าน float
                $pv_new = intdiv($pv_old * self::PERCENT_KEEP + 50, 100);
                $pv_cut = $pv_old - $pv_new;

                if ($pv_cut <= 0) {
                    continue;
                }

                $sum_pv_old += $pv_old;
                $sum_pv_cut += $pv_cut;
                $sum_pv_new += $pv_new;
                $total++;

                $line = [
                    'user_name' => $row->user_name,
                    'pv_old'    => $pv_old,
                    'pv_cut'    => $pv_cut,
                    'pv_new'    => $pv_new,
                    'code'      => null,
                ];

                if ($commit) {
                    $code = $this->gen_code_pv();

                    if (empty($code)) {
                        throw new \Exception('ออกเลขที่รายการ (db_code_pv) ไม่สำเร็จ user_name : ' . $row->user_name);
                    }

                    $jang_pv                       = new Jang_pv();
                    $jang_pv->code                 = $code;
                    $jang_pv->customer_username    = $row->user_name;
                    $jang_pv->to_customer_username = $row->user_name;
                    $jang_pv->position             = $row->qualification_id;
                    $jang_pv->bonus_percen         = self::PERCENT_KEEP;
                    $jang_pv->pv_old               = $pv_old;
                    $jang_pv->pv                   = $pv_cut;
                    $jang_pv->pv_balance           = $pv_new;
                    $jang_pv->wallet               = 0;
                    $jang_pv->old_wallet           = 0;
                    $jang_pv->wallet_balance       = 0;
                    $jang_pv->type                 = self::JANG_TYPE;
                    $jang_pv->status               = 'Success';
                    $jang_pv->date_active          = date('Y-m-d');
                    $jang_pv->note_orther          = 'ตัด PV เหลือ ' . self::PERCENT_KEEP . '% [' . $batch . ']';
                    $jang_pv->save();

                    $customer_update->pv = $pv_new;
                    $customer_update->save();

                    $line['code'] = $code;
                }

                if (count($detail) < self::DETAIL_LIMIT) {
                    $detail[] = $line;
                }
            }

            if ($commit) {
                DB::commit();
            }
        } catch (\Exception $e) {
            if ($commit) {
                DB::rollback();
            }

            return [
                'status'  => 'error',
                'message' => $e->getMessage(),
                'batch'   => $batch,
                'commit'  => $commit,
                'note'    => 'rollback ทั้งหมดแล้ว ไม่มีรายการใดถูกบันทึก',
            ];
        }

        return [
            'status'       => 'success',
            'commit'       => $commit,
            'message'      => $commit
                ? 'ตัด PV เหลือ ' . self::PERCENT_KEEP . '% เรียบร้อย บันทึกลง jang_pv แล้ว'
                : 'PREVIEW เท่านั้น ยังไม่มีการบันทึก',
            'batch'        => $batch,
            'percent_keep' => self::PERCENT_KEEP,
            'total'        => $total,
            'sum_pv_old'   => $sum_pv_old,
            'sum_pv_cut'   => $sum_pv_cut,
            'sum_pv_new'   => $sum_pv_new,
            'error'        => $error,
            'detail_note'  => $total > self::DETAIL_LIMIT
                ? 'แสดง detail แค่ ' . self::DETAIL_LIMIT . ' รายการแรก ดูทั้งหมดที่ jang_pv ด้วย batch นี้'
                : '',
            'detail'       => $detail,
        ];
    }

    /**
     * แปลงเป็นจำนวนเต็ม ปัดเศษ ต่ำกว่า .5 ปัดลง ตั้งแต่ .5 ขึ้นไปปัดขึ้น
     */
    private static function to_int($pv)
    {
        return (int) round($pv, 0, PHP_ROUND_HALF_UP);
    }

    /**
     * ออกเลขที่รายการ PV, db_code_pv() ของเดิมมีเคสที่ recursive แล้วไม่ return ค่ากลับมา จึงวนซ้ำให้
     */
    private function gen_code_pv($try = 5)
    {
        for ($i = 0; $i < $try; $i++) {
            $code = \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_pv();

            if (!empty($code)) {
                return $code;
            }
        }

        return null;
    }
}
