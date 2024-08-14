<?php

namespace App\Http\Controllers\Frontend\FC2024;

use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Phattarachai\LineNotify\Facade\Line;
use App\eWallet;

class RunPerDay_pv_ab02Controller extends Controller
{
    public static $s_date;
    public static $e_date;
    public static $y;
    public static $m;
    public static $d;
    public static $date_action;

    public static function initialize()
    {

        self::$s_date = Carbon::now()->subDay()->startOfDay();
        self::$e_date = Carbon::now()->subDay()->endOfDay();
        $yesterday = Carbon::now()->subDay();
        self::$y = $yesterday->year;
        self::$m = $yesterday->month;
        self::$d = $yesterday->day;
        self::$date_action = Carbon::create(self::$y, self::$m, self::$d);

        // self::$s_date =  date('Y-08-6 00:00:00');
        // self::$e_date =  date('Y-08-6 23:59:59');

        // $yesterday = Carbon::now()->subDay();
        // self::$y = $yesterday->year;
        // self::$m = '08';
        // self::$d = '6';


        // self::$date_action = Carbon::create(self::$y, self::$m, self::$d);


        // $data =  DB::table('log_pv_per_day_ab_balance_all')
        //     ->where('date_action', self::$date_action)
        //     ->delete();

        // $data =  DB::table('report_pv_per_day_ab_balance')
        //     ->where('date_action', self::$date_action)
        //     ->delete();
        // dd('ddd');


        //  dd(self::$y, self::$m, self::$d);
    }


    public static function Runbonus4Perday()
    {
        RunPerDay_pv_ab02Controller::initialize();
        try {
            DB::beginTransaction();

            $bonus_4_01 = RunPerDay_pv_ab02Controller::bonus_4_01();
            if ($bonus_4_01['status'] !== 'success') {
                throw new \Exception($bonus_4_01['message']);
            }


            $bonus_4_02 = RunPerDay_pv_ab02Controller::bonus_4_02();


            if ($bonus_4_01['status'] !== 'success') {
                throw new \Exception($bonus_4_01['message']);
            }


            //คำนวนชุดนี้สุดท้าย ต้องมี code รัน
            if ($bonus_4_01['status'] == 'success' and $bonus_4_02['status'] == 'success') {
                // $bonus_4_03 = RunPerDay_pv_ab02Controller::bonus_4_03();
                DB::commit();

                $ms = "โบนัสบริหาร team 2 สายงาน(8) " . self::$date_action . " \n" .
                    $bonus_4_01['message'] . "\n" .
                    $bonus_4_02['message'] . "\n";
                // $bonus_4_03['message'] . "\n";

                Line::send($ms);
                return $ms;
            } else {
                DB::commit();

                $ms = "โบนัสบริหาร team 2 สายงาน(4 รอจ่ายเงิน) " . self::$date_action . " \n" .
                    $bonus_4_01['message'] . "\n" .
                    $bonus_4_02['message'] . "\n";

                Line::send($ms);
                return $ms;
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Line::send($e->getMessage());
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()], 500);
        }
    }

    public static function bonus_4_01()
    {
        RunPerDay_pv_ab02Controller::initialize();


        try {
            DB::beginTransaction();
            $pv_today_downline_total = DB::table('customers')
                ->select(
                    'id',
                    'user_name',
                    'name',
                    'last_name',
                    'introduce_id',
                    'pv_today',
                    'upline_id',
                    'expire_date',
                    'expire_date_bonus',
                    'pv_today_downline_total',
                    'qualification_id'
                )

                ->where('status_customer', '!=', 'normal')
                ->where(function ($query) {
                    $query->where('pv_today_downline_total', '>', 0)
                        ->orWhere('pv_today', '>', 0);
                })
                ->get();

            foreach ($pv_today_downline_total as $value) {
                $user_a = DB::table('customers')
                    ->select('pv_today_downline_total', 'pv_today')
                    ->where('upline_id', $value->user_name)
                    ->where('type_upline', 'A')
                    ->where(function ($query) {
                        $query->where('pv_today_downline_total', '>', 0)
                            ->orWhere('pv_today', '>', 0);
                    })

                    ->first();

                if ($user_a) {
                    $pv_a =  $user_a->pv_today + $user_a->pv_today_downline_total;
                } else {
                    $pv_a =  0;
                }



                $user_b = DB::table('customers')
                    ->select('pv_today_downline_total', 'pv_today')
                    ->where('upline_id', $value->user_name)
                    ->where('type_upline', 'B')

                    ->where(function ($query) {
                        $query->where('pv_today_downline_total', '>', 0)
                            ->orWhere('pv_today', '>', 0);
                    })
                    ->first();




                if ($user_b) {
                    $pv_b =  $user_b->pv_today + $user_b->pv_today_downline_total;
                } else {
                    $pv_b =  0;
                }

                $balance_up_old = DB::table('log_pv_per_day_ab_balance_all')
                    ->select('balance', 'balance_type')
                    ->where('user_name', $value->user_name)
                    ->where('status', 'success')
                    ->wheredate('date_action', '!=', self::$date_action)
                    ->latest('id') // หรือคุณสามารถระบุคอลัมน์ที่ใช้ในการจัดเรียงให้ตรงกับโครงสร้างฐานข้อมูลของคุณ
                    ->first(); // ดึงแถวล่าสุดที่ตรงกับเงื่อนไข

                if ($balance_up_old) {
                    $balance_old = $balance_up_old->balance;
                    $balance_type = $balance_up_old->balance_type;

                    if ($balance_type == 'A') {
                        $pv_a_new = $pv_a + $balance_old;
                        $pv_b_new = $pv_b;
                        $pv_a_old = $balance_old;
                        $pv_b_old = 0;
                    }

                    if ($balance_type == 'B') {
                        $pv_b_new = $pv_b + $balance_old;
                        $pv_a_new = $pv_a;
                        $pv_b_old = $balance_old;
                        $pv_a_old = 0;
                    }
                } else {
                    $pv_a_old = 0;
                    $pv_b_old = 0;
                    $pv_a_new = $pv_a;
                    $pv_b_new = $pv_b;

                    $balance_old = 0;
                    $balance_type = null;
                }

                if ($pv_a_new > $pv_b_new) {
                    $balance_type = 'A';
                    $kang = $pv_a_new;
                    $aoon = $pv_b_new;
                } elseif ($pv_a_new < $pv_b_new) {
                    $balance_type = 'B';
                    $aoon = $pv_a_new;
                    $kang = $pv_b_new;
                } else { //เท่ากัน ยก A เป็นขาแข็ง
                    $balance_type = 'A';
                    $kang = $pv_a_new;
                    $aoon = $pv_b_new;
                }

                $balance =  $kang - $aoon;

                if ($kang == 0 || $aoon == 0) {
                    $status = 'success';
                } else {
                    $status = 'pending';
                }


                if ($balance != 0 || $balance_old != 0 || $kang != 0 || $aoon != 0) {

                    $dataPrepare = [
                        'user_name' => $value->user_name,
                        'customer_id_fk' => $value->id,
                        'name' => $value->name,
                        'qualification_id' => $value->qualification_id,
                        'balance' => $balance,
                        'balance_type' => $balance_type,
                        'balance_up_old' => $balance_old,
                        'pv_today' => $value->pv_today,
                        'pv_a_new' => $pv_a_new,
                        'pv_b_new' => $pv_b_new,
                        'pv_a' => $pv_a,
                        'pv_b' => $pv_b,
                        'pv_a_old' => $pv_a_old,
                        'pv_b_old' => $pv_b_old,
                        'kang' => $kang,
                        'aoon' => $aoon,
                        'year' =>  self::$y,
                        'month' =>  self::$m,
                        'day' =>  self::$d,
                        'date_action' =>  self::$date_action,
                        'status' => $status,
                    ];

                    // $existingRecord = DB::table('log_pv_per_day_ab_balance')
                    //     ->where('user_name', '=', $value->user_name)
                    //     ->first();

                    // if ($existingRecord) {
                    //     // อัพเดทข้อมูลที่มีอยู่
                    //     DB::table('log_pv_per_day_ab_balance')
                    //         ->where('user_name', '=', $value->user_name)
                    //         ->update($dataPrepare);
                    // } else {
                    //     // แทรกข้อมูลใหม่ 
                    //     DB::table('log_pv_per_day_ab_balance')
                    //         ->insert($dataPrepare);
                    // }

                    $existingRecordAll = DB::table('log_pv_per_day_ab_balance_all')
                        ->where('user_name', '=', $value->user_name)
                        ->where('date_action', '=', self::$date_action)
                        ->first();

                    if ($existingRecordAll) {

                        // อัพเดทข้อมูลที่มีอยู่
                        DB::table('log_pv_per_day_ab_balance_all')
                            ->where('id', '=', $existingRecordAll->id)

                            ->update($dataPrepare);
                    } else {

                        // แทรกข้อมูลใหม่ 
                        DB::table('log_pv_per_day_ab_balance_all')
                            ->insert($dataPrepare);
                    }
                }
            }
            DB::commit();
            return ['status' => 'success', 'message' => 'การคำนวณโบนัส 8 เสร็จสมบูรณ์ 01'];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }

    public static function bonus_4_02()
    {
        RunPerDay_pv_ab02Controller::initialize();
        try {
            DB::beginTransaction();
            $log_pv_per_day_ab_balance = DB::table('log_pv_per_day_ab_balance_all')
                ->where('status', '=', 'pending')
                ->get();

            foreach ($log_pv_per_day_ab_balance as $value) {

                $customers = DB::table('customers')
                    ->select(
                        'customers.name',
                        'customers.last_name',
                        'customers.user_name',
                        'customers.introduce_id',
                        'customers.qualification_id',
                        'customers.expire_date',
                        'customers.expire_date_bonus',
                        'dataset_qualification.bonus_limit'
                    )
                    ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                    ->whereNotin('qualification_id', ['MB', 'CM'])
                    ->wheredate('customers.expire_date_bonus', '>=',  self::$e_date)
                    ->where('customers.user_name', '=', $value->user_name)
                    ->first();

                if ($customers) {
                    $kang_balance_up_old = $value->kang + $value->balance_up_old;
                    if ($customers->qualification_id == 'MO' || $customers->qualification_id == 'VIP') {
                        $bonus_aoon = $value->aoon * 20 / 100;
                        $rate = 20;
                    } else {
                        $bonus_aoon = $value->aoon * 25 / 100;
                        $rate = 25;
                    }

                    $bonus_kang = 0;
                    $bonus_full =  $bonus_aoon;

                    if ($bonus_full > $customers->bonus_limit) {
                        $bonus_full =  $customers->bonus_limit;
                    };
                    $tax_total = $bonus_full * (3 / 100);
                    $bonus_total_in_tax =  $bonus_full - $tax_total;
                    $dataPrepare = [
                        'user_name' => $value->user_name,
                        'customer_id_fk' => $value->id,
                        'name' =>  $customers->name . ' ' . $customers->last_name,
                        'qualification_id' => $customers->qualification_id,
                        'introduce_id' => $customers->introduce_id,
                        'bonus_limit' => $customers->bonus_limit,
                        'expire_date' => $customers->expire_date_bonus,
                        'balance' => $value->balance,
                        'balance_type' => $value->balance_type,
                        'rate' =>  $rate,
                        'balance_up_old' => $value->balance_up_old,
                        'kang' => $value->kang,
                        'aoon' => $value->aoon,
                        'kang_balance_up_old' => $kang_balance_up_old,
                        'bonus_aoon' =>  $bonus_aoon,
                        'bonus_kang' => $bonus_kang,
                        'bonus_full' => $bonus_full,
                        'tax_total' => $tax_total,
                        'bonus' =>  $bonus_total_in_tax,
                        'year' =>  self::$y,
                        'month' =>  self::$m,
                        'day' =>  self::$d,
                        'date_action' =>  self::$date_action,

                    ];

                    $report_pv_per_day_ab_balance = DB::table('report_pv_per_day_ab_balance')
                        ->where('user_name', '=', $value->user_name)
                        ->where('date_action', '=', self::$date_action)
                        ->first();

                    if ($report_pv_per_day_ab_balance) {
                        // อัพเดทข้อมูลที่มีอยู่

                        DB::table('report_pv_per_day_ab_balance')
                            ->where('id', '=', $report_pv_per_day_ab_balance->id)
                            ->update($dataPrepare);
                    } else {
                        // แทรกข้อมูลใหม่

                        DB::table('report_pv_per_day_ab_balance')
                            ->insert($dataPrepare);
                    }
                }

                DB::table('log_pv_per_day_ab_balance_all')
                    ->where('id', $value->id)
                    ->update([
                        'status' => 'success',
                    ]);
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'การคำนวณโบนัส 8 เสร็จสมบูรณ์ 02'];
        } catch (\Exception $e) {
            DB::rollBack();
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }

    public static function bonus_4_03() //เริ่มการจ่ายเงิน    
    {
        RunPerDay_pv_ab02Controller::initialize();
        $c = DB::table('report_pv_per_day_ab_balance')
            ->select(
                'id',
                'user_name',
                'bonus_full as bonus_full',
                'bonus as el',
                'tax_total',
                'year',
                'month',
                'day',
                'note'
            )
            ->where('status', '=', 'pending')
            ->limit('50')
            // ->wheredate('date_action', '=', '2024-07-04')
            ->get();

        // dd($c);

        $i = 0;
        try {
            DB::BeginTransaction();

            foreach ($c as $value) {
                $customers = DB::table('customers')
                    ->select('id', 'user_name', 'ewallet', 'ewallet_use')
                    ->where('user_name', $value->user_name)
                    ->first();
                // if(empty($customers)){
                //     dd($value->user_name);
                // }


                if (empty($customers->ewallet)) {
                    $ewallet = 0;
                } else {
                    $ewallet = $customers->ewallet;
                }

                if (empty($customers->ewallet_use)) {
                    $ewallet_use = 0;
                } else {
                    $ewallet_use = $customers->ewallet_use;
                }

                $ew_total = $ewallet  + $value->el;
                $ew_use = $ewallet_use + $value->el;

                DB::table('customers')
                    ->where('user_name', $value->user_name)
                    ->update(['ewallet' => $ew_total, 'ewallet_use' => $ew_use]);


                $count_eWallet =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_wallet();

                $dataPrepare = [
                    'transaction_code' => $count_eWallet,
                    'customers_id_fk' => $customers->id,
                    'customer_username' => $value->user_name,
                    'tax_total' => $value->tax_total,
                    'bonus_full' => $value->bonus_full,
                    'amt' => $value->el,
                    'old_balance' => $customers->ewallet,
                    'balance' => $ew_total,
                    'note_orther' => "ข้อ 8 โบนัส บาลานซ์ อ่อน+แข็ง ($value->year/$value->month/$value->day)",
                    'receive_date' => now(),
                    'receive_time' => now(),
                    'type' => 12,
                    'status' => 2,
                ];

                $query =  eWallet::create($dataPrepare);
                DB::table('report_pv_per_day_ab_balance')
                    ->where('id', $value->id)
                    ->update(['status' => 'success']);

                $i++;
                DB::commit();
            }
            $c = DB::table('report_pv_per_day_ab_balance')
                ->select(
                    'id',
                    'user_name',
                    'bonus_full as bonus_full',
                    'bonus as el',
                    'tax_total',
                    'year',
                    'month',
                    'day',
                    'note'
                )
                ->where('status', '=', 'pending')
                ->count();


            return ['status' => 'success', 'message' => 'จ่ายโบนัส สำเร็จ (' . $i . ') รายการ คงเหลือ ' . $c];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'fail', 'message' => $e->getMessage()];
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
                'customers.expire_date_bonus',

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
            $data_user >= 4 and $data_user_upposition->qualification_id_fk == 9 and $data_user_upposition->pv_upgrad >= 6000
        ) { //MD

            $update_position = DB::table('customers')
                ->where('user_name', $data_user_upposition->user_name)
                ->update(['qualification_id' => 'MD']);

            DB::table('log_up_vl')->insert([
                'user_name' => $data_user_upposition->user_name,
                'introduce_id' => $data_user_upposition->introduce_id,
                'bonus_total' => $data_user_upposition->bonus_total,
                'old_lavel' => $data_user->code,
                'new_lavel' => 'MD',
                'status' => 'success'
            ]);

            return 'MD Success';
        }


        $mr =  DB::table('customers')
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
            ->where('dataset_qualification.id', '=', 8)
            ->count();
        if (
            $mr >= 4 and $data_user_upposition->qualification_id_fk == 8 and $data_user_upposition->pv_upgrad >= 6000
        ) {

            $update_position = DB::table('customers')
                ->where('user_name', $data_user_upposition->user_name)
                ->update(['qualification_id' => 'ME']);
            $position =  'ME';
            DB::table('log_up_vl')->insert([
                'user_name' => $data_user_upposition->user_name,
                'introduce_id' => $data_user_upposition->introduce_id,
                'bonus_total' => $data_user_upposition->bonus_total,
                'old_lavel' => $data_user_upposition->qualification_id,
                'new_lavel' => 'ME',
                'status' => 'success'
            ]);

            return 'ME Success';
        }



        $mg =  DB::table('customers')
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
            ->where('dataset_qualification.id', '=', 7)
            ->count();
        if (
            $mg >= 4 and $data_user_upposition->qualification_id_fk == 7 and $data_user_upposition->pv_upgrad >= 6000
        ) {


            $update_position = DB::table('customers')
                ->where('user_name', $data_user_upposition->user_name)
                ->update(['qualification_id' => 'MR']);
            $position =  'MR';
            DB::table('log_up_vl')->insert([
                'user_name' => $data_user_upposition->user_name,
                'introduce_id' => $data_user_upposition->introduce_id,
                'bonus_total' => $data_user_upposition->bonus_total,
                'old_lavel' => $data_user_upposition->qualification_id,
                'new_lavel' => 'MR',
                'status' => 'success'
            ]);
        }



        $svvip =  DB::table('customers')
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
            ->where('dataset_qualification.id', '=', 6)
            ->count();
        if (
            $svvip >= 4 and $data_user_upposition->qualification_id_fk == 6 and $data_user_upposition->pv_upgrad >= 6000
        ) {

            $update_position = DB::table('customers')
                ->where('user_name', $data_user_upposition->user_name)
                ->update(['qualification_id' => 'MG']);
            $position =  'MG';
            DB::table('log_up_vl')->insert([
                'user_name' => $data_user_upposition->user_name,
                'introduce_id' => $data_user_upposition->introduce_id,
                'bonus_total' => $data_user_upposition->bonus_total,
                'old_lavel' => $data_user_upposition->qualification_id,
                'new_lavel' => 'MG',
                'status' => 'success'
            ]);
        }


        $xvvip =  DB::table('customers')
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
            ->where('dataset_qualification.id', '=', 5)
            ->count();

        if (
            $svvip >= 4 and $data_user_upposition->qualification_id_fk == 5 and $data_user_upposition->pv_upgrad >= 3600
        ) {

            $update_position = DB::table('customers')
                ->where('user_name', $data_user_upposition->user_name)
                ->update(['qualification_id' => 'SVVIP']);
            $position =  'SVVIP';


            DB::table('log_up_vl')->insert([
                'user_name' => $data_user_upposition->user_name,
                'introduce_id' => $data_user_upposition->introduce_id,
                'bonus_total' => $data_user_upposition->bonus_total,
                'old_lavel' => $data_user_upposition->qualification_id,
                'new_lavel' => 'SVVIP',
                'status' => 'success'
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
                'user_name' => $data_user_upposition->user_name,
                'introduce_id' => $data_user_upposition->introduce_id,
                'old_lavel' => $data_user_upposition->qualification_id,
                'new_lavel' => 'XVVIP',
                'bonus_total' => $data_user_upposition->bonus_total,
                'status' => 'success'
            ]);

            return 'XVVIP Success';
        }
    }
}
