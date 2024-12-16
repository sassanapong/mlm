<?php

namespace App\Http\Controllers\Frontend\FC2024;

use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Phattarachai\LineNotify\Facade\Line;

class RunPerDay_pv_ab01Controller extends Controller
{
    public static $s_date;
    public static $e_date;
    public static $y;
    public static $m;
    public static $d;
    public static $date_action;


    public static function initialize()
    {
        // TRUNCATE `log_pv_per_day`;
        // TRUNCATE `report_pv_per_day`; 
        // TRUNCATE `log_pv_per_day_ab_balance_all`;
        // TRUNCATE `report_pv_per_day_ab_balance`;


        self::$s_date = Carbon::now()->subDay()->startOfDay();
        self::$e_date = Carbon::now()->subDay()->endOfDay();
        $yesterday = Carbon::now()->subDay();
        self::$y = $yesterday->year;
        self::$m = $yesterday->month;
        self::$d = $yesterday->day;
        self::$date_action = Carbon::create(self::$y, self::$m, self::$d);


        // self::$s_date =  date('Y-08-13 00:00:00');
        // self::$e_date =  date('Y-08-13 23:59:59');

        // $yesterday = Carbon::now()->subDay();
        // self::$y = $yesterday->year;
        // self::$m = '08';
        // self::$d = '13';
        // self::$date_action = Carbon::create(self::$y, self::$m, self::$d);
        // dd(self::$y, self::$m, self::$d);

    }

    //RunbonusPerday2024
    public static function RunbonusPerday()
    {
        RunPerDay_pv_ab01Controller::initialize();

        try {
            DB::beginTransaction();

            $data1 = RunPerDay_pv_ab01Controller::delete_pv();
            dd($data1);

            $bonus_allsale_permounth_03 = RunPerDay_pv_ab01Controller::bonus_allsale_permounth_03();
            if ($bonus_allsale_permounth_03['status'] !== 'success') {
                throw new \Exception($bonus_allsale_permounth_03['message']);
            }
            dd($bonus_allsale_permounth_03);

            $bonus_allsale_permounth_04 = RunPerDay_pv_ab01Controller::bonus_allsale_permounth_04();
            if ($bonus_allsale_permounth_04['status'] !== 'success') {
                throw new \Exception($bonus_allsale_permounth_04['message']);
            }

            DB::commit();

            $ms =
                "การคำนวนคะแนนซ้ายขวา " . self::$date_action . " \n" .
                // $bonus_allsale_permounth_03['message'] . "\n" .
                $bonus_allsale_permounth_04['message'] . "\n";

            Line::send($ms);
            return $ms;
        } catch (\Exception $e) {
            DB::rollBack();
            Line::send($e->getMessage());
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()], 500);
        }
    }

    public static function delete_pv()
    {
        RunPerDay_pv_ab01Controller::initialize();

        $jang_pv = DB::table('jang_pv')
            ->selectRaw('customer_username, code, count(code) as count_code')
            ->whereBetween('created_at', [self::$s_date, self::$e_date])
            ->groupBy('jang_pv.code')
            ->havingRaw('count_code > 1')
            ->get();


        if (count($jang_pv) > 0) {

            foreach ($jang_pv as $value) {
                $jang_pv_code =  DB::table('jang_pv')
                    ->where('code', $value->code)
                    ->where('customer_username', $value->customer_username)
                    ->limit(1)

                    // ->count();
                    ->update(['code' => $value->code . '_1']);
            }

            $jang_pv2 = DB::table('jang_pv')
                ->selectRaw('customer_username, code, count(code) as count_code')
                ->whereBetween('created_at', [self::$s_date, self::$e_date])
                ->groupBy('jang_pv.code')
                ->havingRaw('count_code > 1')
                ->get();
            if (count($jang_pv2) > 0) {
                if ($jang_pv2) {
                    // Line::send("การคำนวนคะแนนซ้ายขวาและขึ้นตำแหน่ง \n" . 'fail มีรายการซ้ำ 03 จากการสมัครสมาชิก ไม่ทำงานในฟังชั่นถัดไป');
                    throw new \Exception('fail มีรายการซ้ำ 03 จากการสมัครสมาชิก ไม่ทำงานในฟังชั่นถัดไป');
                }
            }
        }

        $data =  DB::table('report_pv_per_day')
            ->wheredate('date_action', self::$date_action)
            ->delete();
        $data =  DB::table('log_pv_per_day')
            ->wheredate('date_action', self::$date_action)
            ->delete();
        $pv_count = DB::table('customers')
            ->where('pv_today_downline_total', '>', 0)
            ->count();

        if ($pv_count > 0) {
            $pv_today_downline_total = DB::table('customers')
                ->where('pv_today_downline_total', '>', 0)

                ->update(['pv_today_downline_total' => 0]);
        } else {
            $pv_today_downline_total = 0;
        }

        $a = DB::table('customers')
            ->where('pv_today_downline_a', '>', 0)

            ->update(['pv_today_downline_a' => 0]);

        $b = DB::table('customers')
            ->where('pv_today_downline_b', '>', 0)
            ->update(['pv_today_downline_b' => 0]);

        $pv_today = DB::table('customers')
            ->where('pv_today', '>', 0)
            ->update(['pv_today' => 0]);

        $pending =  DB::table('jang_pv')
            ->where('status_run_pv_upline', 'success')
            ->whereBetween('created_at', [self::$s_date, self::$e_date])
            // ->count();
            ->update(['status_run_pv_upline' => 'pending']);

        DB::commit();

        // if ($pending) {
        //     return 'success';
        // } else {
        //     return 'fail';
        // }

        return $pending;
    }

    public static function bonus_allsale_permounth_01() //รันรายวัน จากออเดอ
    {
        RunPerDay_pv_ab01Controller::initialize();

        try {


            $db_orders = DB::table('db_orders')
                ->selectRaw('customers_user_name, SUM(pv_total) AS pv_type_1234')
                ->whereIn('order_status_id_fk', [4, 5, 6, 7])
                ->where('type_order', 'pv')
                ->whereBetween('created_at', [self::$s_date, self::$e_date])
                ->groupBy('customers_user_name')
                ->get();

            if ($db_orders->isEmpty()) {
                throw new \Exception('ไม่พบรายการ สั่งซื้อ 01 order');
            }

            foreach ($db_orders as $order) { //เอาไปเก็บที่อื่นก่อน

                $dataPrepare = [
                    'user_name' => $order->customers_user_name,
                    'pv' => $order->pv_type_1234,
                    'year' => self::$y,
                    'month' => self::$m,
                    'day' => self::$d,
                ];

                // ตรวจสอบว่ามีข้อมูลซ้ำอยู่แล้วหรือไม่
                $existingOrder = DB::table('order_pvrun_upline_perday')
                    ->where('user_name', $order->customers_user_name)
                    ->where('year', self::$y)
                    ->where('month', self::$m)
                    ->where('day', self::$d)
                    ->first();

                // หากไม่มีข้อมูลซ้ำ ทำการอัพเดทหรืออินเสิร์ต
                if (empty($existingOrder)) {
                    DB::table('order_pvrun_upline_perday')
                        ->updateOrInsert(
                            ['user_name' => $order->customers_user_name, 'year' => self::$y, 'month' => self::$m, 'day' => self::$d],
                            $dataPrepare
                        );
                } elseif ($existingOrder and  $existingOrder->status_run_pv_upline == 'pending') {
                    DB::table('order_pvrun_upline_perday')
                        ->updateOrInsert(
                            ['user_name' => $order->customers_user_name, 'year' => self::$y, 'month' => self::$m, 'day' => self::$d],
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
        RunPerDay_pv_ab01Controller::initialize();
        try {

            $db_orders = DB::table('order_pvrun_upline_perday')
                ->selectRaw('id,user_name,pv AS pv_type_1234')

                ->where('status_run_pv_upline', 'pending')
                ->whereBetween('created_at', [self::$s_date, self::$e_date])
                ->get();

            if ($db_orders->isEmpty()) {
                throw new \Exception('ไม่พบรายการ สั่งซื้อ 02 การเตรียมข้อมูล Order');
            }

            foreach ($db_orders as $order) {
                $customer = DB::table('customers')
                    ->where('user_name', $order->user_name)
                    ->first();

                if ($customer) {
                    $result = self::runbonus_01($customer->upline_id, $order->pv_type_1234, 0, $order->user_name, 'order', $customer->type_upline);
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
        RunPerDay_pv_ab01Controller::initialize();
        try {

            // ดึงข้อมูลคำสั่งซื้อที่เกี่ยวข้องกับ PV 
            $jang_pv = DB::table('jang_pv')
                ->selectRaw('id, customer_username,type, to_customer_username, sum(pv) AS pv_type_1234')
                ->wherein('type', [1, 2, 3, 4])
                ->where('status_run_pv_upline', 'pending')
                ->where('status', 'success')
                ->whereBetween('created_at', [self::$s_date, self::$e_date])
                ->limit(200)
                ->groupby('to_customer_username')
                ->get();
            // dd(count($jang_pv));

            if ($jang_pv->isEmpty()) {
                throw new \Exception('ไม่พบรายการ 03 1.สมัครใหม่ 2.แจงสะสมส่วนตัว 3.ยืนยันสิทธิ์ 4.RE CashBack');
            }

            foreach ($jang_pv as $value) {
                $customer = DB::table('customers')
                    ->select('pv_upgrad', 'user_name', 'introduce_id', 'upline_id', 'status_run_pv_upline', 'type_upline')
                    ->where('user_name', $value->to_customer_username)
                    ->first();

                if ($customer) {
                    $result = self::runbonus_01($customer->upline_id, $value->pv_type_1234, 0, $value->to_customer_username, 'jangpv', $customer->type_upline);
                    if ($result['status'] !== 'success') {
                        throw new \Exception($result['message']);
                    } else {
                        DB::table('customers')
                            ->where('user_name', '=', $value->to_customer_username)
                            ->update(['status_run_pv_upline' => 'pending', 'pv_today' => $value->pv_type_1234]);

                        DB::table('jang_pv')
                            ->where('to_customer_username', '=', $value->to_customer_username)
                            ->whereBetween('created_at', [self::$s_date, self::$e_date])
                            ->update(['status_run_pv_upline' => 'success']);
                    }
                }
            }
            DB::commit();
            $jang_pv = DB::table('jang_pv')
                ->selectRaw('id, customer_username,type, to_customer_username, sum(pv) AS pv_type_1234')
                ->wherein('type', [1, 2, 3, 4])
                ->where('status_run_pv_upline', 'pending')
                ->where('status', 'success')
                ->whereBetween('created_at', [self::$s_date, self::$e_date])
                ->groupby('to_customer_username')
                ->get();

            $pending = count($jang_pv);
            return ['status' => 'success', 'message' => 'การคำนวณโบนัสเสร็จสมบูรณ์ 03 คงเหลือ:' . $pending, 'pending' => $pending];
        } catch (\Exception $e) {
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }

    public static function bonus_allsale_permounth_04()
    {

        RunPerDay_pv_ab01Controller::initialize();

        try {
            $status_run_pv_upline = DB::table('customers')
                // ->where('status_run_pv_upline', '=', 'pending')
                ->where('pv_today_downline_total', '>', 0)
                ->orwhere('pv_today', '>', 0)
                ->count();

            if ($status_run_pv_upline <= 0) {
                throw new \Exception('ไม่พบรายการที่มีการเคลื่อนไหวคะแนน 04');
            }

            $pv_today_downline_total = DB::table('customers')
                ->select('id', 'pv_upgrad', 'user_name', 'introduce_id', 'upline_id', 'pv_today_downline_total', 'pv_today')
                ->where('pv_today_downline_total', '>', 0)
                ->orwhere('pv_today', '>', 0)
                ->get();


            foreach ($pv_today_downline_total as $value) {
                $user_a = DB::table('customers')
                    ->select('pv_upgrad', 'pv_today_downline_total', 'user_name', 'pv_today')
                    ->where('upline_id', $value->user_name)
                    ->where('type_upline', 'A')
                    ->first();

                if ($user_a) {
                    $pv_a =   $user_a->pv_today + $user_a->pv_today_downline_total;
                } else {
                    $pv_a = 0;
                }

                $user_b = DB::table('customers')
                    ->select('pv_upgrad', 'pv_today_downline_total', 'user_name', 'pv_today')
                    ->where('upline_id', $value->user_name)
                    ->where('type_upline', 'B')
                    ->first();


                if ($user_b) {

                    $pv_b =  $user_b->pv_today + $user_b->pv_today_downline_total;
                } else {
                    $pv_b =  0;
                }

                $dataPrepare = [
                    'user_name' => $value->user_name,
                    'customer_id_fk' => $value->id,
                    'pv_upgrad' => $value->pv_upgrad,
                    'pv_today' => $value->pv_today,
                    'pv_a' => $pv_a,
                    'pv_b' => $pv_b,
                    'year' => self::$y,
                    'month' => self::$m,
                    'day' => self::$d,
                    'date_action' => self::$date_action,
                    'status' => 'success',
                ];

                DB::table('report_pv_per_day')
                    ->updateOrInsert(
                        ['user_name' => $value->user_name, 'date_action' => self::$date_action],
                        $dataPrepare
                    );

                DB::table('customers')
                    ->where('user_name', $value->user_name)
                    ->update([
                        'status_run_pv_upline' => 'success',
                        'pv_today_downline_a' => $pv_a,
                        'pv_today_downline_b' => $pv_b,
                    ]);

                // self::$up_lv($value->user_name);
            }
            DB::commit();
            return ['status' => 'success', 'message' => 'การคำนวณโบนัสเสร็จสมบูรณ์ 04', 'pending' => 0];
        } catch (\Exception $e) {
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }

    public static function runbonus_01($customers_user_name, $pv, $i, $userbuy, $type, $type_upline)
    {
        RunPerDay_pv_ab01Controller::initialize();
        // ->where('status_customer','!=', 'normal')
        $user = DB::table('customers')
            ->select('id', 'pv', 'user_name', 'introduce_id', 'upline_id', 'pv_today_downline_total', 'type_upline', 'status_customer')
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

            $dataPrepare = [
                'user_name' => $user->user_name,
                'type_recive' => $type_upline,

                'customer_id_fk' => $user->id,
                'user_name_recive' =>  $userbuy,
                'pv_upline_total' =>  $pv_today_downline_total,
                'pv' =>  $pv,
                'type' => $type,
                'year' => self::$y,
                'month' => self::$m,
                'day' => self::$d,
                'date_action' => self::$date_action,

            ];

            if ($user->status_customer == 'normal') {
                $log_pv_per_day =  DB::table('log_pv_per_day')
                    ->updateOrInsert([
                        'user_name' => $user->user_name,
                        'user_name_recive' => $userbuy,
                        'date_action' => self::$date_action,
                        'type' => $type
                    ], $dataPrepare);
            }
            DB::commit();

            if ($user->upline_id && $user->upline_id !== 'AA') {
                $i++;
                $result = self::runbonus_01($user->upline_id, $pv, $i, $userbuy, $type, $user->type_upline);
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
