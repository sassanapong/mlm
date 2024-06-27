<?php

namespace App\Http\Controllers\Frontend\FC2024;

use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Phattarachai\LineNotify\Facade\Line;

class RunPerDay_pv_ab01Controller extends Controller
{
    public $arr = array();
    //RunbonusPerday2024
    public static function RunbonusPerday()
    {

        // $current_time = date('H:i'); // รับค่าเวลาปัจจุบันในรูปแบบ HH:MM
        // $date = now();
        // $date = date("Y-m-d", strtotime("-1 day", strtotime($date)));
        // if ($current_time >= '00:00' && $current_time <= '08:00') {
        //     // เงื่อนไขที่เวลาอยู่ระหว่าง 00:00 ถึง 06:00
        // } else {
        //     return 'fail ';
        // }

        $s_date = Carbon::now()->subDay()->startOfDay();
        $e_date = Carbon::now()->subDay()->endOfDay();

        $jang_pv = DB::table('jang_pv')
            ->selectRaw('customer_username, code_order, count(code_order) as count_code')
            ->whereBetween('created_at', [$s_date, $e_date])
            ->groupBy('jang_pv.code_order')
            ->havingRaw('count_code > 1')
            ->get();

        if ($jang_pv->isNotEmpty()) {

            Line::send("การคำนวนคะแนนซ้ายขวาและขึ้นตำแหน่ง \n" . 'fail มีรายการซ้ำ 03 จากการสมัครสมาชิก ไม่ทำงานในฟังชั่นถัดไป');
            throw new \Exception('fail มีรายการซ้ำ 03 จากการสมัครสมาชิก ไม่ทำงานในฟังชั่นถัดไป');
        }

        $db_orders = DB::table('db_orders')
            ->selectRaw('db_orders.customers_user_name, code_order, COUNT(code_order) AS count_code')
            ->leftJoin('customers', 'db_orders.customers_user_name', '=', 'customers.user_name')
            ->whereBetween('db_orders.created_at', [$s_date, $e_date])
            ->groupBy('db_orders.code_order')
            ->havingRaw('count_code > 1')
            ->get();

        if ($db_orders->isNotEmpty()) {
            Line::send("การคำนวนคะแนนซ้ายขวาและขึ้นตำแหน่ง \n" . "fail รันรายวัน จากอ order 01 มีค่าซ้ำ ไม่ทำงานในฟังชั่นถัดไป");
            throw new \Exception('fail รันรายวัน จากออเดอ 01 มีค่าซ้ำ ไม่ทำงานในฟังชั่นถัดไป');
        }


        $pv_count = DB::table('customers')
            ->where('pv_today_downline_total', '>', 0)
            ->count();

        if ($pv_count > 0) {
            $pv_today_downline_total = DB::table('customers')
                ->where('pv_today_downline_total', '>', 0)
                ->update(['pv_today_downline_total' => 0]);
        }

        dd($pv_today_downline_total);

        try {
            DB::beginTransaction();

            $bonus_allsale_permounth_01 = RunPerDay_pv_ab01Controller::bonus_allsale_permounth_01();
            // if ($bonus_allsale_permounth_01['status'] !== 'success') {
            //     Line::send("การคำนวนคะแนนซ้ายขวาและขึ้นตำแหน่ง \n" . $bonus_allsale_permounth_01['message']);
            //     return $bonus_allsale_permounth_01['message'];
            // }

            $bonus_allsale_permounth_02 = RunPerDay_pv_ab01Controller::bonus_allsale_permounth_02();
            // if ($bonus_allsale_permounth_02['status'] !== 'success') {
            //     throw new \Exception($bonus_allsale_permounth_02['message']);
            // }

            $bonus_allsale_permounth_03 = RunPerDay_pv_ab01Controller::bonus_allsale_permounth_03();
            // if ($bonus_allsale_permounth_03['status'] !== 'success') {
            //     throw new \Exception($bonus_allsale_permounth_03['message']);
            // }

            $bonus_allsale_permounth_04 = RunPerDay_pv_ab01Controller::bonus_allsale_permounth_04();
            // if ($bonus_allsale_permounth_04['status'] !== 'success') {
            //     throw new \Exception($bonus_allsale_permounth_04['message']);
            // }

            DB::commit();

            $ms =
                "การคำนวนคะแนนซ้ายขวาและขึ้นตำแหน่ง \n" .
                $bonus_allsale_permounth_01['message'] . "\n" .
                $bonus_allsale_permounth_02['message'] . "\n" .
                $bonus_allsale_permounth_03['message'] . "\n" .
                $bonus_allsale_permounth_04['message'] . "\n";

            Line::send($ms);
            return $ms;
            // } else {
            //     DB::commit();

            //     $ms =
            //         "การคำนวนคะแนนซ้ายขวาและขึ้นตำแหน่ง \n" .
            //         $bonus_allsale_permounth_01['message'] . "\n" .
            //         $bonus_allsale_permounth_02['message'] . "\n" .
            //         $bonus_allsale_permounth_03['message'] . "\n" .
            //         $bonus_allsale_permounth_04['message'] . "\n" .
            //         'Fail รอรันรายการ 05 เพื่ออัพคะแนน';

            //     Line::send($ms);
            //     return $ms;
            // }
        } catch (\Exception $e) {
            DB::rollBack();
            Line::send($e->getMessage());
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()], 500);
        }
    }



    public static function bonus_allsale_permounth_01() //รันรายวัน จากออเดอ
    {
        $s_date = Carbon::now()->subDay()->startOfDay();
        $e_date = Carbon::now()->subDay()->endOfDay();

        try {


            $db_orders = DB::table('db_orders')
                ->selectRaw('customers_user_name, SUM(pv_total) AS pv_type_1234')
                ->whereIn('order_status_id_fk', [4, 5, 6, 7])
                ->where('type_order', 'pv')
                ->whereBetween('created_at', [$s_date, $e_date])
                ->groupBy('customers_user_name')
                ->get();

            if ($db_orders->isEmpty()) {
                throw new \Exception('ไม่พบรายการ สั่งซื้อ 01 order');
            }

            foreach ($db_orders as $order) { //เอาไปเก็บที่อื่นก่อน
                $y = date('Y');
                $m = date('m');
                $d = date('d');

                $dataPrepare = [
                    'user_name' => $order->customers_user_name,
                    'pv' => $order->pv_type_1234,
                    'year' => $y,
                    'month' => $m,
                    'day' => $d,
                ];

                // ตรวจสอบว่ามีข้อมูลซ้ำอยู่แล้วหรือไม่
                $existingOrder = DB::table('order_pvrun_upline_perday')
                    ->where('user_name', $order->customers_user_name)
                    ->where('year', $y)
                    ->where('month', $m)
                    ->where('day', $d)
                    ->first();

                // หากไม่มีข้อมูลซ้ำ ทำการอัพเดทหรืออินเสิร์ต
                if (empty($existingOrder)) {
                    DB::table('order_pvrun_upline_perday')
                        ->updateOrInsert(
                            ['user_name' => $order->customers_user_name, 'year' => $y, 'month' => $m, 'day' => $d],
                            $dataPrepare
                        );
                } elseif ($existingOrder and  $existingOrder->status_run_pv_upline == 'pending') {
                    DB::table('order_pvrun_upline_perday')
                        ->updateOrInsert(
                            ['user_name' => $order->customers_user_name, 'year' => $y, 'month' => $m, 'day' => $d],
                            $dataPrepare
                        );
                } else {
                    throw new \Exception('พบรายการที่ถูกรันไปเเล้วกรุณาตรวจสอบ สั่งซื้อ 01');
                }
            }

            return ['status' => 'success', 'message' => 'การคำนวณโบนัสเสร็จสมบูรณ์ 01'];
        } catch (\Exception $e) {
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }

    public static function bonus_allsale_permounth_02() //รันรายวัน จากออเดอ
    {
        $s_date = Carbon::now()->subDay()->startOfDay(); // ลบหนึ่งวันและกำหนดเวลาเริ่มต้นของวัน
        $e_date = Carbon::now()->subDay()->endOfDay(); // ลบหนึ่งวันและกำหนดเวลาสิ้นสุดของวัน
        try {


            $db_orders = DB::table('order_pvrun_upline_perday')
                ->selectRaw('id,user_name,pv AS pv_type_1234')

                ->where('status_run_pv_upline', 'pending')
                ->whereBetween('created_at', [$s_date, $e_date])
                ->get();

            if ($db_orders->isEmpty()) {
                throw new \Exception('ไม่พบรายการ สั่งซื้อ 02 การเตรียมข้อมูล Order');
            }

            foreach ($db_orders as $order) {
                $customer = DB::table('customers')
                    ->where('user_name', $order->user_name)
                    ->first();

                if ($customer) {
                    $result = self::runbonus_01($customer->upline_id, $order->pv_type_1234, 0, $order->user_name, 'order');
                    if ($result['status'] !== 'success') {
                        throw new \Exception($result['message']);
                    }

                    DB::table('customers')
                        ->where('user_name', '=', $order->user_name)
                        ->update(['status_run_pv_upline' => 'pending']);

                    DB::table('order_pvrun_upline_perday')
                        ->where('id', '=', $order->id)
                        ->update(['status_run_pv_upline' => 'success']);
                }
            }

            return ['status' => 'success', 'message' => 'การคำนวณโบนัสเสร็จสมบูรณ์ 02'];
        } catch (\Exception $e) {
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }


    public static function bonus_allsale_permounth_03() // รันรายวัน จากการสมัครสมาชิก
    {
        $s_date = Carbon::now()->subDay()->startOfDay(); // ลบหนึ่งวันและกำหนดเวลาเริ่มต้นของวัน
        $e_date = Carbon::now()->subDay()->endOfDay(); // ลบหนึ่งวันและกำหนดเวลาสิ้นสุดของวัน
        // $s_date = Carbon::now()->startOfDay();
        // $e_date = Carbon::now()->endOfDay();

        $pending =  DB::table('jang_pv')
            ->where('status_run_pv_upline', 'success')
            ->whereBetween('created_at', [$s_date, $e_date])
            ->update(['status_run_pv_upline' => 'pending']);

        dd($pending);

        try {

            // ดึงข้อมูลคำสั่งซื้อที่เกี่ยวข้องกับ PV
            $jang_pv = DB::table('jang_pv')
                ->selectRaw('id, customer_username, to_customer_username, pv AS pv_type_1234')
                ->wherein('type', [1, 2, 3, 4])
                ->where('status_run_pv_upline', 'pending')
                ->where('status', 'success')
                ->whereBetween('created_at', [$s_date, $e_date])
                ->get();

            if ($jang_pv->isEmpty()) {
                throw new \Exception('ไม่พบรายการ 03 1.สมัครใหม่ 2.แจงสะสมส่วนตัว 3.ยืนยันสิทธิ์ 4.RE CashBack');
            }

            foreach ($jang_pv as $value) {
                $customer = DB::table('customers')
                    ->select('pv_upgrad', 'user_name', 'introduce_id', 'upline_id', 'status_run_pv_upline')
                    ->where('user_name', $value->to_customer_username)
                    ->first();

                if ($customer) {
                    $result = self::runbonus_01($customer->upline_id, $value->pv_type_1234, 0, $value->to_customer_username, 'regis');
                    if ($result['status'] !== 'success') {
                        throw new \Exception($result['message']);
                    } else {
                        DB::table('customers')
                            ->where('user_name', '=', $value->to_customer_username)
                            ->update(['status_run_pv_upline' => 'pending']);

                        DB::table('jang_pv')
                            ->where('id', '=', $value->id)
                            ->update(['status_run_pv_upline' => 'success']);
                    }
                }
            }

            return ['status' => 'success', 'message' => 'การคำนวณโบนัสเสร็จสมบูรณ์ 03'];
        } catch (\Exception $e) {
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }

    public static function bonus_allsale_permounth_04()
    {
        $y = date('Y');
        $m = date('m');
        $d = date('d');

        try {
            $status_run_pv_upline = DB::table('customers')
                ->where('status_run_pv_upline', '=', 'pending')
                ->count();

            if ($status_run_pv_upline <= 0) {
                throw new \Exception('ไม่พบรายการที่มีการเคลื่อนไหวคะแนน 04');
            }

            $pv_today_downline_total = DB::table('customers')
                ->select('id', 'pv_upgrad', 'user_name', 'introduce_id', 'upline_id', 'pv_today_downline_total')
                ->where('pv_today_downline_total', '>', 0)
                ->where('status_run_pv_upline', '=', 'pending')
                ->get();

            foreach ($pv_today_downline_total as $value) {
                $user_a = DB::table('customers')
                    ->select('pv_upgrad', 'pv_today_downline_total')
                    ->where('upline_id', $value->user_name)

                    ->where('type_upline', 'A')
                    ->first();


                if ($user_a) {
                    $pv_a =  $user_a->pv_upgrad + $user_a->pv_today_downline_total;
                } else {
                    $pv_a =  0;
                }

                $user_b = DB::table('customers')
                    ->select('pv_upgrad', 'pv_today_downline_total')
                    ->where('upline_id', $value->user_name)
                    ->where('type_upline', 'B')
                    ->first();


                if ($user_b) {
                    $pv_b =  $user_b->pv_upgrad + $user_b->pv_today_downline_total;
                } else {
                    $pv_b =  0;
                }

                $dataPrepare = [
                    'user_name' => $value->user_name,
                    'customer_id_fk' => $value->id,
                    'pv_upgrad' => $value->pv_upgrad,
                    'pv_a' => $pv_a,
                    'pv_b' => $pv_b,
                    'year' => $y,
                    'month' => $m,
                    'day' => $d,
                    'status' => 'success',
                ];

                DB::table('report_pv_per_day')
                    ->updateOrInsert(
                        ['user_name' => $value->user_name, 'year' => $y, 'month' => $m, 'day' => $d],
                        $dataPrepare
                    );

                DB::table('customers')
                    ->where('user_name', $value->user_name)
                    ->update([
                        'status_run_pv_upline' => 'success',
                        'pv_today_downline_a' => $pv_a,
                        'pv_today_downline_b' => $pv_b,
                    ]);

                // self::up_lv($value->user_name);
            }

            return ['status' => 'success', 'message' => 'การคำนวณโบนัสเสร็จสมบูรณ์ 04'];
        } catch (\Exception $e) {
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }

    public static function runbonus_01($customers_user_name, $pv, $i, $userbuy, $type)
    {
        $y = date('Y');
        $m = date('m');
        $d = date('d');

        $user = DB::table('customers')
            ->select('id', 'pv', 'user_name', 'introduce_id', 'upline_id', 'pv_today_downline_total')
            ->where('user_name', $customers_user_name)
            ->first();

        if (!$user) {
            return ['status' => 'success', 'message' => 'ไม่พบผู้ใช้'];
        }

        DB::beginTransaction();

        try {

            if ($user->pv_today_downline_total) {
                $pv_today_downline_total = $user->pv_today_downline_total + $pv;
            } else {
                $pv_today_downline_total = 0 + $pv;
            }

            $data = DB::table('customers')
                ->where('user_name', '=', $user->user_name)
                ->update(['pv_today_downline_total' => $pv_today_downline_total, 'status_run_pv_upline' => 'pending']);

            //log เก็บประวัติว่าได้มาจากรหัสอะไร

            $data = DB::table('customers')
                ->where('user_name', '=', $user->user_name)
                ->update(['pv_today_downline_total' => $pv_today_downline_total, 'status_run_pv_upline' => 'pending']);

            $dataPrepare = [
                'user_name' => $user->user_name,
                'customer_id_fk' => $user->id,
                'user_name_recive' =>  $userbuy,
                'pv_upline_total' =>  $pv_today_downline_total,
                'pv' =>  $pv,
                'type' => $type,
                'year' => $y,
                'month' => $m,
                'day' => $d,

            ];

            $log_pv_per_day =  DB::table('log_pv_per_day')
                ->updateOrInsert(['user_name' => $user->user_name, 'year' => $y, 'month' => $m, 'day' => $d, 'type' => 'order'], $dataPrepare);

            DB::commit();

            if ($user->upline_id && $user->upline_id !== 'AA') {
                $i++;

                $result = self::runbonus_01($user->upline_id, $pv, $i, $userbuy, $type);
                if ($result['status'] !== 'success') {

                    return $result;
                }
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'สำเร็จ'];
        } catch (Exception $e) {

            return ['status' => 'fail', 'message' => 'การอัปเดต PvPayment ล้มเหลว'];
        }
    }
}
