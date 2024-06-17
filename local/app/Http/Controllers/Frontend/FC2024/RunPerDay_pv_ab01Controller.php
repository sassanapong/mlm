<?php

namespace App\Http\Controllers\Frontend\FC2024;

use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;

class RunPerDay_pv_ab01Controller extends Controller
{
    public $arr = array();
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

        // $bonus_allsale_permounth_00 =  RunPerDay_pv_ab01Controller::bonus_allsale_permounth_00();
        // dd($bonus_allsale_permounth_00);

        // $bonus_allsale_permounth_000 =  RunPerDay_pv_ab01Controller::bonus_allsale_permounth_000();
        // dd($bonus_allsale_permounth_000);



        $bonus_allsale_permounth_01 =  RunPerDay_pv_ab01Controller::bonus_allsale_permounth_01();
        dd($bonus_allsale_permounth_01);


        $bonus_allsale_permounth_02 =  RunPerDay_pv_ab01Controller::bonus_allsale_permounth_02();
        dd($bonus_allsale_permounth_02);
    }

    public static function bonus_allsale_permounth_00() //รันครั้งแรกของทุกรหัส
    {

        // $pv_count = DB::table('customers')
        //     ->where('pv_today_downline_total', '>', 0)
        //     ->count();

        // if ($pv_count > 0) {
        //     DB::table('customers')
        //         ->where('pv_today_downline_total', '>', 0)
        //         ->update(['pv_today_downline_total' => 0]);
        // }

        // $status_run_pv_upline =  DB::table('customers')
        //     ->where('status_run_pv_upline', '=', 'success')
        //     // ->update(['status_run_pv_upline' => 'pending']);
        //     ->count();

        // if ($status_run_pv_upline > 0) {
        //     $status_run_pv_upline =  DB::table('customers')
        //         ->where('status_run_pv_upline', '=', 'success')
        //         ->update(['status_run_pv_upline' => 'pending']);
        // }

        // ดึงข้อมูลคำสั่งซื้อที่เกี่ยวข้องกับ PV

        $pv_today_downline_total =  DB::table('customers')
            ->select('pv_upgrad', 'user_name', 'introduce_id', 'upline_id', 'pv_today_downline_total')
            ->where('status_run_pv_upline', '=', 'pending')
            ->limit('1')
            ->OrderByDesc('id')
            ->get();


        if (count($pv_today_downline_total) <= 0) {
            return 'รันครบเเล้ว';
        }
        $i = 0;
        foreach ($pv_today_downline_total as $value) {
            $i++;
            $result = self::runbonus($value->upline_id, $value->pv_upgrad, 0, $value->user_name);
            if ($result['status'] !== 'success') {
                return $result;
            } else {
                DB::table('customers')
                    ->where('user_name', '=', $value->user_name)
                    ->update(['status_run_pv_upline' => 'success']);
            }
        }
        $customers_pending =  DB::table('customers')
            ->select('pv_upgrad', 'user_name', 'introduce_id', 'upline_id', 'pv_today_downline_total')
            ->where('status_run_pv_upline', '=', 'pending')
            ->OrderByDesc('id')
            ->count();

        return ['status' => 'success', 'จำนวน:' . $i, 'คงเหลือ:' . $customers_pending, 'message' => 'การคำนวณโบนัสเสร็จสมบูรณ์'];
    }

    public static function bonus_allsale_permounth_000() //สำหรับรันครั้งแรกของระบบ
    {

        $y = date('Y');
        $m = date('m');
        $d = date('d');


        // $status_run_pv_upline =  DB::table('customers')
        //     ->where('status_run_pv_upline', '=', 'success')
        //     ->count();

        // if ($status_run_pv_upline > 0) {
        //     $status_run_pv_upline =  DB::table('customers')
        //         ->where('status_run_pv_upline', '=', 'success')
        //         ->update(['status_run_pv_upline' => 'pending']);
        // }



        $pv_today_downline_total =  DB::table('customers')
            ->select('pv_upgrad', 'user_name', 'introduce_id', 'upline_id', 'pv_today_downline_total')
            ->where('status_run_pv_upline', '=', 'pending')
            ->OrderByDesc('id')
            ->limit('50000')
            ->get();

        $i = 0;
        foreach ($pv_today_downline_total as $value) {
            $i++;
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

            DB::table('customers')
                ->where('user_name', '=', $value->user_name)
                ->update(['status_run_pv_upline' => 'success', 'pv_today_downline_a' => $pv_a, 'pv_today_downline_b' => $pv_b]);
        }

        $customers_pending =  DB::table('customers')
            ->select('pv_upgrad', 'user_name', 'introduce_id', 'upline_id', 'pv_today_downline_total')
            ->where('status_run_pv_upline', '=', 'pending')
            ->OrderByDesc('id')
            ->count();

        return ['status' => 'success', 'จำนวน:' . $i, 'คงเหลือ:' . $customers_pending, 'message' => 'การคำนวณโบนัสเสร็จสมบูรณ์'];
    }

    public static function runbonus($customers_user_name, $pv, $i, $userbuy)
    {
        $user = DB::table('customers')
            ->select('pv', 'user_name', 'introduce_id', 'upline_id', 'pv_today_downline_total')
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
                ->update(['pv_today_downline_total' => $pv_today_downline_total]);

            DB::commit();

            if ($user->upline_id && $user->upline_id !== 'AA') {
                $i++;

                $result = self::runbonus($user->upline_id, $pv, $i, $userbuy);
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

    public static function bonus_allsale_permounth_01() //รันรายวัน
    {

        $s_date = Carbon::now()->subDay()->startOfDay(); // ลบหนึ่งวันและกำหนดเวลาเริ่มต้นของวัน
        $e_date = Carbon::now()->subDay()->endOfDay(); // ลบหนึ่งวันและกำหนดเวลาสิ้นสุดของวัน


        // $s_date = Carbon::now()->startOfDay();
        // $e_date = Carbon::now()->endOfDay();

        // ตรวจสอบคำสั่งซื้อที่ซ้ำกัน
        // $db_orders = DB::table('db_orders')
        //     ->selectRaw('db_orders.customers_user_name, code_order, COUNT(code_order) AS count_code')
        //     ->leftJoin('customers', 'db_orders.customers_user_name', '=', 'customers.user_name')
        //     ->whereBetween('db_orders.created_at', [$s_date, $e_date])
        //     ->havingRaw('count_code > 1')
        //     ->get();

        $db_orders = DB::table('db_orders')
            ->selectRaw('db_orders.customers_user_name,code_order,count(code_order) as count_code')
            ->leftJoin('customers', 'db_orders.customers_user_name', '=', 'customers.user_name')

            ->groupBy('db_orders.code_order')
            ->havingRaw('count_code > 1')
            ->get();



        if ($db_orders->isNotEmpty()) {
            return 'fail';
        }

        // ดึงข้อมูลคำสั่งซื้อที่เกี่ยวข้องกับ PV
        $db_orders = DB::table('db_orders')
            ->selectRaw('customers_user_name, SUM(pv_total) AS pv_type_1234')
            ->whereIn('order_status_id_fk', [4, 5, 6, 7])
            ->where('type_order', 'pv')
            ->whereBetween('created_at', [$s_date, $e_date])
            ->groupBy('customers_user_name')
            ->get();

        if ($db_orders->isEmpty()) {
            return 'ไม่พบรายการ';
        }

        foreach ($db_orders as $order) {
            $customer = DB::table('customers')
                ->select('pv_upgrad', 'user_name', 'introduce_id', 'upline_id', 'status_run_pv_upline')
                ->where('user_name', $order->customers_user_name)
                ->first();

            if ($customer) {

                $result = self::runbonus_01($customer->upline_id, $order->pv_type_1234, 0, $order->customers_user_name);
                if ($result['status'] !== 'success') {
                    return $result;
                } else {

                    DB::table('customers')
                        ->where('user_name', '=', $order->customers_user_name)
                        ->update(['status_run_pv_upline' => 'pending']);
                }
            }
        }

        return ['status' => 'success', 'message' => 'การคำนวณโบนัสเสร็จสมบูรณ์'];
    }


    public static function bonus_allsale_permounth_02() //สำหรับรันครั้งแรกของระบบ
    {

        $y = date('Y');
        $m = date('m');
        $d = date('d');

        $status_run_pv_upline =  DB::table('customers')
            ->where('status_run_pv_upline', '=', 'pending')
            ->count();

        if ($status_run_pv_upline <= 0) {
            return 'ไม่พบรายการที่มีการเคลื่อนไหวคะแนน';
        }

        $pv_today_downline_total =  DB::table('customers')
            ->select('id', 'pv_upgrad', 'user_name', 'introduce_id', 'upline_id', 'pv_today_downline_total')
            ->where('pv_today_downline_total', '>', 0)
            ->where('status_run_pv_upline', '=', 'pending')
            // ->limit('10000')
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
                'customer_id_fk' =>  $value->id,
                'pv_upgrad' => $value->pv_upgrad,
                'pv_a' =>  $pv_a,
                'pv_b' => $pv_b,
                'year' => $y,
                'month' => $m,
                'day' => $d,
                'status' => 'success',
            ];

            $report_bonus_all_sale_permouth =  DB::table('report_pv_per_day')
                ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y, 'month' => $m, 'day' => $d], $dataPrepare);

            DB::table('customers')
                ->where('user_name', '=',)
                ->update(['status_run_pv_upline' => 'success', 'pv_today_downline_a' => $pv_a, 'pv_today_downline_b' => $pv_b]);

            self::up_lv($value->user_name);
        }

        return ['status' => 'success', 'message' => 'การคำนวณโบนัสเสร็จสมบูรณ์'];
    }



    public static function runbonus_01($customers_user_name, $pv, $i, $userbuy)
    {
        $user = DB::table('customers')
            ->select('pv', 'user_name', 'introduce_id', 'upline_id', 'pv_today_downline_total')
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

            DB::commit();

            if ($user->upline_id && $user->upline_id !== 'AA') {
                $i++;

                $result = self::runbonus_01($user->upline_id, $pv, $i, $userbuy);
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



    public static function up_lv($customers_user_name)
    {

        $data_user_upposition =  DB::table('customers')
            ->select(
                'customers.name',
                'customers.last_name',
                'bonus_total',
                'customers.user_name',
                'customers.upline_id',
                'customers.introduce_id',
                'customers.qualification_id',
                'customers.expire_date',
                'dataset_qualification.id as qualification_id_fk',

                'pv_upgrad',
                'qualification_id',
                'pv_today_downline_a',
                'pv_today_downline_b'
            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', $customers_user_name)

            ->first();


        $data_user =  DB::table('customers')
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
            ->where('dataset_qualification.id', '=', 9)
            ->count();

        if (
            $data_user >= 4 and $data_user_upposition->qualification_id_fk == 9
            and $data_user_upposition->pv_today_downline_a >= 30720000
            and $data_user_upposition->pv_today_downline_b >= 30720000
            and $data_user_upposition->pv_upgrad >= 6000
        ) { //MD

            $update_position = DB::table('customers')
                ->where('user_name', $data_user_upposition->user_name)
                ->update(['qualification_id' => 'MD']);

            DB::table('log_up_vl')->insert([
                'user_name' => $data_user_upposition->user_name, 'introduce_id' => $data_user_upposition->introduce_id, 'bonus_total' => $data_user_upposition->bonus_total,
                'old_lavel' => $data_user->code, 'new_lavel' => 'MD', 'status' => 'success'
            ]);

            return 'MD Success';
        }


        $mr =  DB::table('customers')
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
            ->where('dataset_qualification.id', '=', 8)
            ->count();
        if (
            $mr >= 4 and $data_user_upposition->qualification_id_fk == 8
            and $data_user_upposition->pv_today_downline_a >= 7680000
            and $data_user_upposition->pv_today_downline_b >= 7680000
            and $data_user_upposition->pv_upgrad >= 6000
        ) {

            $update_position = DB::table('customers')
                ->where('user_name', $data_user_upposition->user_name)
                ->update(['qualification_id' => 'ME']);
            $position =  'ME';
            DB::table('log_up_vl')->insert([
                'user_name' => $data_user_upposition->user_name, 'introduce_id' => $data_user_upposition->introduce_id, 'bonus_total' => $data_user_upposition->bonus_total,
                'old_lavel' => $data_user_upposition->qualification_id, 'new_lavel' => 'ME', 'status' => 'success'
            ]);

            return 'ME Success';
        }



        $mg =  DB::table('customers')
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
            ->where('dataset_qualification.id', '=', 7)
            ->count();
        if (
            $mg >= 4 and $data_user_upposition->qualification_id_fk == 7
            and $data_user_upposition->pv_today_downline_a >= 192000
            and $data_user_upposition->pv_today_downline_b >= 192000
            and $data_user_upposition->pv_upgrad >= 6000
        ) {


            $update_position = DB::table('customers')
                ->where('user_name', $data_user_upposition->user_name)
                ->update(['qualification_id' => 'MR']);
            $position =  'MR';
            DB::table('log_up_vl')->insert([
                'user_name' => $data_user_upposition->user_name, 'introduce_id' => $data_user_upposition->introduce_id, 'bonus_total' => $data_user_upposition->bonus_total,
                'old_lavel' => $data_user_upposition->qualification_id, 'new_lavel' => 'MR', 'status' => 'success'
            ]);
        }



        $svvip =  DB::table('customers')
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
            ->where('dataset_qualification.id', '=', 6)
            ->count();
        if (
            $svvip >= 4 and $data_user_upposition->qualification_id_fk == 6
            and $data_user_upposition->pv_today_downline_a >= 480000
            and $data_user_upposition->pv_today_downline_b >= 480000
            and $data_user_upposition->pv_upgrad >= 6000
        ) {

            $update_position = DB::table('customers')
                ->where('user_name', $data_user_upposition->user_name)
                ->update(['qualification_id' => 'MG']);
            $position =  'MG';
            DB::table('log_up_vl')->insert([
                'user_name' => $data_user_upposition->user_name, 'introduce_id' => $data_user_upposition->introduce_id, 'bonus_total' => $data_user_upposition->bonus_total,
                'old_lavel' => $data_user_upposition->qualification_id, 'new_lavel' => 'MG', 'status' => 'success'
            ]);
        }


        $xvvip =  DB::table('customers')
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
            ->where('dataset_qualification.id', '=', 5)
            ->count();

        if (
            $svvip >= 4 and $data_user_upposition->qualification_id_fk == 5
            and $data_user_upposition->pv_today_downline_a >= 12000
            and $data_user_upposition->pv_today_downline_b >= 12000
            and $data_user_upposition->pv_upgrad >= 3600
        ) {

            $update_position = DB::table('customers')
                ->where('user_name', $data_user_upposition->user_name)
                ->update(['qualification_id' => 'SVVIP']);
            $position =  'SVVIP';


            DB::table('log_up_vl')->insert([
                'user_name' => $data_user_upposition->user_name, 'introduce_id' => $data_user_upposition->introduce_id, 'bonus_total' => $data_user_upposition->bonus_total,
                'old_lavel' => $data_user_upposition->qualification_id, 'new_lavel' => 'SVVIP', 'status' => 'success'
            ]);
        }



        if (
            $data_user_upposition->qualification_id_fk == 4
            and $data_user_upposition->pv_today_downline_a >= 30000
            and $data_user_upposition->pv_today_downline_b >= 30000
            and $data_user_upposition->pv_upgrad >= 2400
        ) {

            $update_position = DB::table('customers')
                ->where('user_name', $data_user_upposition->user_name)
                ->update(['qualification_id' => 'XVVIP']);
            $position =  'XVVIP';
            DB::table('log_up_vl')->insert([
                'user_name' => $data_user_upposition->user_name, 'introduce_id' => $data_user_upposition->introduce_id, 'old_lavel' => $data_user_upposition->qualification_id,
                'new_lavel' => 'XVVIP', 'bonus_total' => $data_user_upposition->bonus_total, 'status' => 'success'
            ]);

            return 'XVVIP Success';
        }
    }

    // public static function bonus_allsale_permounth_01()
    // {

    //     // กำหนดวันเวลาปัจจุบัน
    //     // $s_date = Carbon::now()->subDay()->startOfDay(); // ลบหนึ่งวันและกำหนดเวลาเริ่มต้นของวัน
    //     // $e_date = Carbon::now()->subDay()->endOfDay(); // ลบหนึ่งวันและกำหนดเวลาสิ้นสุดของวัน
    //     $s_date = Carbon::now()->startOfDay(); // ลบหนึ่งวันและกำหนดเวลาเริ่มต้นของวัน
    //     $e_date = Carbon::now()->endOfDay(); // ลบหนึ่งวันและกำหนดเวลาสิ้นสุดของวัน


    //     $db_orders = DB::table('db_orders')
    //         ->selectRaw('db_orders.customers_user_name,code_order,count(code_order) as count_code')
    //         ->leftJoin('customers', 'db_orders.customers_user_name', '=', 'customers.user_name')
    //         ->where(function ($query) use ($s_date, $e_date) {
    //             $query->when($s_date != '' && $e_date == '', function ($q) use ($s_date) {
    //                 $q->whereDate('db_orders.created_at', $s_date);
    //             })
    //                 ->when($s_date != '' && $e_date != '', function ($q) use ($s_date, $e_date) {
    //                     $q->whereDate('db_orders.created_at', '>=', $s_date)
    //                         ->whereDate('db_orders.created_at', '<=', $e_date);
    //                 })
    //                 ->when($s_date == '' && $e_date != '', function ($q) use ($e_date) {
    //                     $q->whereDate('db_orders.created_at', $e_date);
    //                 });
    //         })
    //         ->groupBy('db_orders.code_order')
    //         ->havingRaw('count_code > 1')
    //         ->get();

    //     if (count($db_orders) > 0) {
    //         return 'fail';
    //     }


    //     $pv_today_downline_total =  DB::table('customers')
    //         ->where('pv_today_downline_total', '>', 0)
    //         // ->update(['pv_today_downline_total' => '0']);
    //         ->count();

    //     if ($pv_today_downline_total > 0) {
    //         $pv_today_downline_total =  DB::table('customers')
    //             ->where('pv_today_downline_total', '>', 0)
    //             ->update(['pv_today_downline_total' => '0']);
    //     }

    //     // $status_run_pv_upline =  DB::table('customers')
    //     //     ->where('status_run_pv_upline', '=', 'success')
    //     //     // ->update(['status_run_pv_upline' => 'pending']);
    //     //     ->count();

    //     // if ($status_run_pv_upline > 0) {
    //     //     $status_run_pv_upline =  DB::table('customers')
    //     //         ->where('status_run_pv_upline', '=', 'success')
    //     //         ->update(['status_run_pv_upline' => 'pending']);
    //     // }


    //     $db_orders = DB::table('db_orders')
    //         ->selectRaw('customers_user_name,sum(pv_total) as pv_type_1234')
    //         ->wherein('order_status_id_fk', [4, 5, 6, 7])
    //         ->where('type_order', 'pv')
    //         // ->whereNotIn('customers_user_name', ['A530461'])

    //         ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(created_at) >= '{$s_date}' and date(created_at) <= '{$e_date}'else 1 END"))
    //         ->groupby('customers_user_name')
    //         // ->limit(10)
    //         ->get();

    //     if (count($db_orders) <= 0) {
    //         return 'ไม่พบรายการ';
    //     }

    //     foreach ($db_orders as $value) {

    //         $customer = DB::table('customers')->select('pv', 'user_name', 'introduce_id', 'upline_id', 'status_run_pv_upline')
    //             ->where('user_name', '=', $value->customers_user_name)
    //             ->first();

    //         if ($customer->status_run_pv_upline == 'pending') {
    //             $data = RunPerDay_pv_ab01Controller::runbonus($customer->upline_id, $value->pv_type_1234, $i = 0, $value->customers_user_name);
    //             // dd($this->arr,$data);

    //             if ($data['status'] == 'success') {

    //                 // DB::table('customers')
    //                 //     ->where('user_name', '=', $value->customers_user_name)
    //                 //     ->update(['status_run_pv_upline' => 'success']);
    //                 // $resule = ['status' => 'success', 'message' => 'ไม่มี User นี้ในระบบ'];
    //                 // return  $resule;

    //             } else {

    //                 return $data;
    //             }
    //         }
    //     }

    //     return $data;
    // }

    // public static function runbonus($customers_user_name, $pv, $i, $userbuy)
    // {

    //     $user = DB::table('customers') //อัพ Pv ของตัวเอง
    //         ->select('pv', 'user_name', 'introduce_id', 'upline_id', 'pv_today_downline_total')
    //         ->where('user_name', '=', $customers_user_name)
    //         ->first();



    //     try {
    //         DB::BeginTransaction();

    //         if ($user) {

    //             if ($user->pv_today_downline_total) {
    //                 $pv_today_downline_total = $user->pv_today_downline_total + $pv;
    //             } else {
    //                 $pv_today_downline_total = 0 + $pv;
    //             }


    //             $customers_uppv =  DB::table('customers')
    //                 ->select('pv', 'user_name', 'introduce_id', 'upline_id', 'pv_today_downline_total')
    //                 ->where('user_name', '=', $user->user_name)
    //                 ->update(['pv_today_downline_total' => $pv_today_downline_total]);
    //             // ->get();

    //             // }
    //             //DB::rollback();
    //             if ($user->upline_id and $user->upline_id != 'AA') {

    //                 $i++;
    //                 // $this->arr[$i] = $user->introduce_id;
    //                 $data = RunPerDay_pv_ab01Controller::runbonus($user->user_name, $pv, $i, $userbuy);
    //                 if ($data['status'] == 'success') {
    //                     DB::commit();
    //                     $resule = ['status' => 'success', 'message' => 'สิ้นสุด'];
    //                     return $resule;
    //                 } else {
    //                     RunPerDay_pv_ab01Controller::runbonus($user->upline_id, $pv, $i, $userbuy);
    //                 }
    //             } else {
    //                 DB::commit();
    //                 $resule = ['status' => 'success', 'message' => 'สิ้นสุด'];
    //                 return $resule;
    //             }
    //         } else {
    //             DB::commit();
    //             $resule = ['status' => 'success', 'message' => 'สิ้นสุด'];
    //             return $resule;
    //         }
    //     } catch (Exception $e) {
    //         //DB::rollback();

    //         $resule = [
    //             'status' => 'fail',
    //             'message' => 'Update PvPayment Fail',
    //         ];
    //         return $resule;
    //     }
    // }



}
