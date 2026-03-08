<?php

namespace App\Http\Controllers\Frontend\FC2024;

use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Phattarachai\LineNotify\Facade\Line;
use App\eWallet;

class CheckUplevelController extends Controller
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

        // self::$s_date =  date('Y-08-23 00:00:00');
        // self::$e_date =  date('Y-08-23 23:59:59');
        // $yesterday = Carbon::now()->subDay();
        // self::$y = $yesterday->year;
        // self::$m = '08';
        // self::$d = '23';

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



    public static function run_uplevel()
    {
        // ดึงข้อมูลทั้งหมดใน query เดียว รวมถึงข้อมูลเกี่ยวกับ qualification ที่ต้องการ
        $data_user_upposition = DB::table('customers')
            ->select(
                'customers.name',
                'customers.last_name',
                'customers.bonus_total',
                'customers.user_name',
                'customers.id_card',
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
            ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('customers.status_customer', '=', 'normal')
            // ->where('customers.user_name', '=', '0767667')
            ->whereIn('dataset_qualification.id', [4, 5, 6, 7, 8, 9])
            ->get();

        // ->groupBy('id_card');  // จัดกลุ่มตาม id_card

        $i = 0;

        // ลูปแต่ละกลุ่มของ id_card
        foreach ($data_user_upposition as $id_card_group) {
            // หากมีมากกว่า 1 record สำหรับ id_card เดียวกัน เลือกผู้ใช้ที่มี qualification สูงสุด
            // if (count($id_card_group) > 1) {
            //     $user = collect($id_card_group)->sortByDesc('qualification_id_fk')->first();
            //     $user_name = $user->user_name;
            // } else {
            //     $user_name = $id_card_group[0]->user_name;
            // }

            // เรียกเช็คการอัพเลเวล
            $CheckUplevel = CheckUplevelController::up_lv($id_card_group->user_name);

            if ($CheckUplevel['status'] == 'success') {
                $i++;
            }
        }

        $ms = 'รันเช็คตำแหน่งสำเร็จ มีรายการอัพตำแหน่งทั้งหมด ' . $i . ' รายการ จากทั้งหมด ' . count($data_user_upposition) . ' รายการ';
        // @app(\App\Http\Controllers\Frontend\FC\LineController::class)->sendText($ms);
        return  $ms;
    }

    public static function up_lv($customers_user_name)
    {

        // ดึงข้อมูลลูกค้า
        $data_user = DB::table('customers')
            ->select(
                'customers.name',
                'customers.last_name',
                'customers.bonus_total',
                'customers.user_name',
                'customers.upline_id',
                'customers.introduce_id',
                'customers.qualification_id',
                'customers.expire_date',
                'customers.expire_date_bonus',
                'dataset_qualification.id as qualification_id_fk',
                'dataset_qualification.code as qualification_code',
                'dataset_qualification.business_qualifications',
                'customers.pv_upgrad',
                'customers.pv_today_downline_a',
                'customers.pv_today_downline_b'
            )
            ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('customers.user_name', $customers_user_name)
            ->first();

        if (!$data_user) {
            return ['status' => 'fail', 'message' => 'ไม่พบผู้ใช้งาน'];
        }

        // ดึง bonus แค่ครั้งเดียว
        $bonus = DB::table('report_pv_per_day_ab_balance_bonus9')
            ->where('recive_user_name', $data_user->user_name)
            ->sum('bonus');

        // นับลูกทีมทุก level ทีเดียว (ใช้ group by)
        // $downlineCounts = DB::table('customers')
        //     ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
        //     ->select(DB::raw('MAX(dataset_qualification.id) as max_qual_id'), DB::raw('COUNT(*) as cnt'))
        //     ->where('customers.introduce_id', $data_user->user_name)
        //     ->where('customers.status_customer', '=', 'normal')
        //     ->first();

        $downlineUsers = DB::table('customers')
            ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('customers.introduce_id', $data_user->user_name)
            ->where('dataset_qualification.id', '>=', $data_user->qualification_id_fk)
            ->where('customers.status_customer', '=', 'normal')
            ->count();



        $downlineCount = $downlineUsers;

        // config เงื่อนไขเลื่อนตำแหน่ง
        $levels = [
            [
                'need_downline' => 4,
                'from' => 9,
                'pv' => 3600,
                'bonus' => 30000,
                'to' => 'MD',
                'msg' => 'MCD Success'
            ],
            [
                'need_downline' => 4,
                'from' => 8,
                'pv' => 3200,
                'bonus' => 20000,
                'to' => 'ME',
                'msg' => 'MDD Success'
            ],
            [
                'need_downline' => 4,
                'from' => 7,
                'pv' => 2800,
                'bonus' => 15000,
                'to' => 'MR',
                'msg' => 'MD Success'
            ],
            [
                'need_downline' => 4,
                'from' => 6,
                'pv' => 2400,
                'bonus' => 10000,
                'to' => 'MG',
                'msg' => 'MR Success'
            ],
            [
                'need_downline' => 4,
                'from' => 5,
                'pv' => 2000,
                'bonus' => 3000,
                'to' => 'SVVIP',
                'msg' => 'ME Success'
            ],
            [
                'need_downline' => 5,
                'from' => 4,
                'pv' => 1600,
                'bonus' => 0,
                'to' => 'XVVIP',
                'msg' => 'MG Success'
            ],
        ];

        foreach ($levels as $level) {
            if (
                $downlineCount >= $level['need_downline'] &&
                $data_user->qualification_id_fk == $level['from'] &&
                $data_user->pv_upgrad >= $level['pv'] &&
                $bonus >= $level['bonus']
            ) {
                DB::transaction(function () use ($data_user, $level) {
                    DB::table('customers')
                        ->where('user_name', $data_user->user_name)
                        ->update(['qualification_id' => $level['to']]);

                    DB::table('log_up_vl')->insert([
                        'user_name' => $data_user->user_name,
                        'introduce_id' => $data_user->introduce_id,
                        'bonus_total' => $data_user->bonus_total,
                        'old_lavel' => $data_user->qualification_code,
                        'new_lavel' => $level['to'],
                        'status' => 'success',
                    ]);
                });

                return ['status' => 'success', 'message' => $level['msg']];
            }
        }

        return ['status' => 'fail', 'message' => 'ไม่มีการอัพตำแหน่ง'];
    }

    public static function up_lv_step_1()
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
                'dataset_qualification.code as qualification_code',
                'dataset_qualification.business_qualifications as business_qualifications',
                'customers.pv_upgrad',
                'customers.pv_today_downline_a',
                'customers.pv_today_downline_b'
            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->whereIn('dataset_qualification.id', [4, 5, 6, 7, 8, 9])
            ->where('customers.status_customer', '=', 'normal')
            ->get();
    }



    // public static function up_lv($customers_user_name)
    // {
    //     $data_user_upposition =  DB::table('customers')
    //         ->select(
    //             'customers.name',
    //             'customers.last_name',
    //             'bonus_total',
    //             'customers.user_name',
    //             'customers.upline_id',
    //             'customers.introduce_id',
    //             'customers.qualification_id',
    //             'customers.expire_date',
    //             'customers.expire_date_bonus',
    //             'dataset_qualification.id as qualification_id_fk',
    //             'dataset_qualification.code as qualification_code',
    //             'dataset_qualification.business_qualifications as business_qualifications',
    //             'customers.pv_upgrad',
    //             'customers.pv_today_downline_a',
    //             'customers.pv_today_downline_b'
    //         )
    //         ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
    //         ->where('user_name', '=', $customers_user_name)
    //         ->first();


    //     $data_user =  DB::table('customers')
    //         ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
    //         ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
    //         ->wherein('dataset_qualification.id', ['9', '10'])
    //         ->where('customers.status_customer', '=', 'normal')
    //         ->count();
    //     if (
    //         $data_user >= 4 and $data_user_upposition->qualification_id_fk == 9 and $data_user_upposition->pv_upgrad >= 3600
    //     ) { //MD


    //         $bonus = DB::table('report_pv_per_day_ab_balance_bonus9')
    //             ->where('recive_user_name', $data_user_upposition->user_name)
    //             ->sum('bonus');

    //         if ($bonus >= 30000) {

    //             $update_position = DB::table('customers')
    //                 ->where('user_name', $data_user_upposition->user_name)
    //                 ->update(['qualification_id' => 'MD']);

    //             DB::table('log_up_vl')->insert([
    //                 'user_name' => $data_user_upposition->user_name,
    //                 'introduce_id' => $data_user_upposition->introduce_id,
    //                 'bonus_total' => $data_user_upposition->bonus_total,
    //                 'old_lavel' => $data_user_upposition->qualification_code,
    //                 'new_lavel' => 'MD',
    //                 'status' => 'success'
    //             ]);
    //             $ms = $data_user_upposition->user_name . ' อัพตำแหน่งจาก ' . $data_user_upposition->business_qualifications . ' เป็น MCD. สําเร็จ';
    //             // Line::send($ms);
    //             // @app(\App\Http\Controllers\Frontend\FC\LineController::class)->sendText($ms);
    //             return ['status' => 'success', 'message' => 'MCD Success'];
    //             // return 'MD Success';
    //         }
    //     }


    //     $mr =  DB::table('customers')
    //         ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
    //         ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
    //         ->wherein('dataset_qualification.id', ['8', '9', '10'])
    //         ->where('customers.status_customer', '=', 'normal')
    //         ->count();
    //     if (
    //         $mr >= 4 and $data_user_upposition->qualification_id_fk == 8  and $data_user_upposition->pv_upgrad >= 3200
    //     ) {
    //         $bonus = DB::table('report_pv_per_day_ab_balance_bonus9')
    //             ->where('recive_user_name', $data_user_upposition->user_name)
    //             ->sum('bonus');



    //         if ($bonus >= 20000) {
    //             $update_position = DB::table('customers')
    //                 ->where('user_name', $data_user_upposition->user_name)
    //                 ->update(['qualification_id' => 'ME']);

    //             DB::table('log_up_vl')->insert([
    //                 'user_name' => $data_user_upposition->user_name,
    //                 'introduce_id' => $data_user_upposition->introduce_id,
    //                 'bonus_total' => $data_user_upposition->bonus_total,
    //                 'old_lavel' => $data_user_upposition->qualification_code,
    //                 'new_lavel' => 'ME',
    //                 'status' => 'success'
    //             ]);

    //             $ms = $data_user_upposition->user_name . ' อัพตำแหน่งจาก ' . $data_user_upposition->business_qualifications . ' เป็น ME(MDD.) สําเร็จ';
    //             // @app(\App\Http\Controllers\Frontend\FC\LineController::class)->sendText($ms);
    //             return ['status' => 'success', 'message' => 'MDD Success'];
    //         }
    //     }



    //     $mg =  DB::table('customers')
    //         ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
    //         ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
    //         ->wherein('dataset_qualification.id', ['7', '8', '9', '10'])
    //         ->where('customers.status_customer', '=', 'normal')
    //         ->count();
    //     if (
    //         $mg >= 4 and $data_user_upposition->qualification_id_fk == 7 and $data_user_upposition->pv_upgrad >= 2800
    //     ) {


    //         $bonus = DB::table('report_pv_per_day_ab_balance_bonus9')
    //             ->where('recive_user_name', $data_user_upposition->user_name)
    //             ->sum('bonus');

    //         if ($bonus >= 15000) {
    //             $update_position = DB::table('customers')
    //                 ->where('user_name', $data_user_upposition->user_name)
    //                 ->update(['qualification_id' => 'MR']);
    //             $position =  'MR';
    //             DB::table('log_up_vl')->insert([
    //                 'user_name' => $data_user_upposition->user_name,
    //                 'introduce_id' => $data_user_upposition->introduce_id,
    //                 'bonus_total' => $data_user_upposition->bonus_total,
    //                 'old_lavel' => $data_user_upposition->qualification_code,
    //                 'new_lavel' => 'MR',
    //                 'status' => 'success'
    //             ]);


    //             $ms = $data_user_upposition->user_name . ' อัพตำแหน่งจาก ' . $data_user_upposition->business_qualifications . ' เป็น MD';
    //             // Line::send($ms);
    //             // @app(\App\Http\Controllers\Frontend\FC\LineController::class)->sendText($ms);


    //             return ['status' => 'success', 'message' => 'MD Success'];
    //         }
    //     }


    //     $svvip =  DB::table('customers')
    //         ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
    //         ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
    //         ->wherein('dataset_qualification.id', ['6', '7', '8', '9', '10'])
    //         ->where('customers.status_customer', '=', 'normal')
    //         ->count();
    //     if (
    //         $svvip >= 4 and $data_user_upposition->qualification_id_fk == 6 and $data_user_upposition->pv_upgrad >= 2400
    //     ) {


    //         $bonus = DB::table('report_pv_per_day_ab_balance_bonus9')
    //             ->where('recive_user_name', $data_user_upposition->user_name)
    //             ->sum('bonus');

    //         if ($bonus >= 10000) {
    //             $update_position = DB::table('customers')
    //                 ->where('user_name', $data_user_upposition->user_name)
    //                 ->update(['qualification_id' => 'MG']);
    //             $position =  'MG';
    //             DB::table('log_up_vl')->insert([
    //                 'user_name' => $data_user_upposition->user_name,
    //                 'introduce_id' => $data_user_upposition->introduce_id,
    //                 'bonus_total' => $data_user_upposition->bonus_total,
    //                 'old_lavel' => $data_user_upposition->qualification_code,
    //                 'new_lavel' => 'MG',
    //                 'status' => 'success'
    //             ]);
    //             $ms = $data_user_upposition->user_name
    //                 . ' อัพตำแหน่งจาก ' . $data_user_upposition->business_qualifications . ' เป็น MR';
    //             // Line::send($ms);
    //             // @app(\App\Http\Controllers\Frontend\FC\LineController::class)->sendText($ms);
    //             return ['status' => 'success', 'message' => 'MR Success'];
    //         }
    //     }


    //     $xvvip =  DB::table('customers')
    //         ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
    //         ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
    //         ->wherein('dataset_qualification.id', ['5', '6', '7', '8', '9', '10'])
    //         ->where('customers.status_customer', '=', 'normal')
    //         ->count();

    //     if (
    //         $xvvip >= 4 and $data_user_upposition->qualification_id_fk == 5 and $data_user_upposition->pv_upgrad >= 2000
    //     ) {


    //         $bonus = DB::table('report_pv_per_day_ab_balance_bonus9')
    //             ->where('recive_user_name', $data_user_upposition->user_name)
    //             ->sum('bonus');

    //         if ($bonus >= 3000) {
    //             $update_position = DB::table('customers')
    //                 ->where('user_name', $data_user_upposition->user_name)
    //                 ->update(['qualification_id' => 'SVVIP']);


    //             DB::table('log_up_vl')->insert([
    //                 'user_name' => $data_user_upposition->user_name,
    //                 'introduce_id' => $data_user_upposition->introduce_id,
    //                 'bonus_total' => $data_user_upposition->bonus_total,
    //                 'old_lavel' => $data_user_upposition->qualification_code,
    //                 'new_lavel' => 'SVVIP',
    //                 'status' => 'success'
    //             ]);
    //             $ms = $data_user_upposition->user_name
    //                 . ' อัพตำแหน่งจาก ' . $data_user_upposition->business_qualifications . ' เป็น ME';
    //             // Line::send($ms);
    //             // @app(\App\Http\Controllers\Frontend\FC\LineController::class)->sendText($ms);
    //             return ['status' => 'success', 'message' => 'ME Success'];
    //         }
    //     }



    //     $VVIP =  DB::table('customers')
    //         ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
    //         ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
    //         ->wherein('dataset_qualification.id', ['4', '5', '6', '7', '8', '9', '10'])
    //         ->where('customers.status_customer', '=', 'normal')
    //         ->count();

    //     if (
    //         $VVIP >= 5 and $data_user_upposition->qualification_id_fk == 4 and $data_user_upposition->pv_upgrad >= 1600
    //     ) {


    //         $update_position = DB::table('customers')
    //             ->where('user_name', $data_user_upposition->user_name)
    //             ->update(['qualification_id' => 'XVVIP']);



    //         DB::table('log_up_vl')->insert([
    //             'user_name' => $data_user_upposition->user_name,
    //             'introduce_id' => $data_user_upposition->introduce_id,
    //             'bonus_total' => $data_user_upposition->bonus_total,
    //             'old_lavel' => $data_user_upposition->qualification_code,
    //             'new_lavel' => 'XVVIP',
    //             'status' => 'success'
    //         ]);
    //         $ms = $data_user_upposition->user_name
    //             . ' อัพตำแหน่งจาก ' . $data_user_upposition->business_qualifications . ' เป็น MG';

    //         // @app(\App\Http\Controllers\Frontend\FC\LineController::class)->sendText($ms);
    //         return ['status' => 'success', 'message' => 'MG Success'];
    //     }

    //     return ['status' => 'fail', 'message' => 'ไม่มีการอัพตำแหน่ง'];
    // }


    // public static function up_lv($customers_user_name)
    // {
    //     $data_user_upposition =  DB::table('customers')
    //         ->select(
    //             'customers.name',
    //             'customers.last_name',
    //             'bonus_total',
    //             'customers.user_name',
    //             'customers.upline_id',
    //             'customers.introduce_id',
    //             'customers.qualification_id',
    //             'customers.expire_date',
    //             'customers.expire_date_bonus',

    //             'dataset_qualification.id as qualification_id_fk',

    //             'pv_upgrad',
    //             'qualification_id',
    //             'pv_today_downline_a',
    //             'pv_today_downline_b'
    //         )
    //         ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
    //         ->where('user_name', '=', $customers_user_name)
    //         ->first();


    //     $data_user =  DB::table('customers')
    //         ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
    //         ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
    //         ->where('dataset_qualification.id', '=', 9)
    //         ->count();

    //     if (
    //         $data_user >= 4 and $data_user_upposition->qualification_id_fk == 9 and $data_user_upposition->pv_upgrad >= 6000
    //     ) { //MD

    //         $update_position = DB::table('customers')
    //             ->where('user_name', $data_user_upposition->user_name)
    //             ->update(['qualification_id' => 'MD']);

    //         DB::table('log_up_vl')->insert([
    //             'user_name' => $data_user_upposition->user_name,
    //             'introduce_id' => $data_user_upposition->introduce_id,
    //             'bonus_total' => $data_user_upposition->bonus_total,
    //             'old_lavel' => $data_user->code,
    //             'new_lavel' => 'MD',
    //             'status' => 'success'
    //         ]);
    //         $ms = $data_user_upposition->user_name . ' อัพตำแหน่งจาก ' . $data_user_upposition->qualification_id . ' เป็น MD';
    //         // Line::send($ms);
    //         @app(\App\Http\Controllers\Frontend\FC\LineController::class)->sendText($ms);
    //         return ['status' => 'success', 'message' => 'MD Success'];
    //         // return 'MD Success';
    //     }


    //     $mr =  DB::table('customers')
    //         ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
    //         ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
    //         ->where('dataset_qualification.id', '=', 8)
    //         ->count();
    //     if (
    //         $mr >= 4 and $data_user_upposition->qualification_id_fk == 8 and $data_user_upposition->pv_upgrad >= 6000
    //     ) {

    //         $update_position = DB::table('customers')
    //             ->where('user_name', $data_user_upposition->user_name)
    //             ->update(['qualification_id' => 'ME']);
    //         $position =  'ME';
    //         DB::table('log_up_vl')->insert([
    //             'user_name' => $data_user_upposition->user_name,
    //             'introduce_id' => $data_user_upposition->introduce_id,
    //             'bonus_total' => $data_user_upposition->bonus_total,
    //             'old_lavel' => $data_user_upposition->qualification_id,
    //             'new_lavel' => 'ME',
    //             'status' => 'success'
    //         ]);

    //         $ms = $data_user_upposition->user_name . ' อัพตำแหน่งจาก ' . $data_user_upposition->qualification_id . ' เป็น ME';
    //         @app(\App\Http\Controllers\Frontend\FC\LineController::class)->sendText($ms);
    //         return ['status' => 'success', 'message' => 'ME Success'];
    //     }



    //     $mg =  DB::table('customers')
    //         ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
    //         ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
    //         ->where('dataset_qualification.id', '=', 7)
    //         ->count();
    //     if (
    //         $mg >= 4 and $data_user_upposition->qualification_id_fk == 7 and $data_user_upposition->pv_upgrad >= 6000
    //     ) {


    //         $update_position = DB::table('customers')
    //             ->where('user_name', $data_user_upposition->user_name)
    //             ->update(['qualification_id' => 'MR']);
    //         $position =  'MR';
    //         DB::table('log_up_vl')->insert([
    //             'user_name' => $data_user_upposition->user_name,
    //             'introduce_id' => $data_user_upposition->introduce_id,
    //             'bonus_total' => $data_user_upposition->bonus_total,
    //             'old_lavel' => $data_user_upposition->qualification_id,
    //             'new_lavel' => 'MR',
    //             'status' => 'success'
    //         ]);


    //         $ms = $data_user_upposition->user_name . ' อัพตำแหน่งจาก ' . $data_user_upposition->qualification_id . ' เป็น MR';
    //         // Line::send($ms);
    //         @app(\App\Http\Controllers\Frontend\FC\LineController::class)->sendText($ms);


    //         return ['status' => 'success', 'message' => 'MR Success'];
    //     }



    //     $svvip =  DB::table('customers')
    //         ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
    //         ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
    //         ->where('dataset_qualification.id', '=', 6)
    //         ->count();
    //     if (
    //         $svvip >= 4 and $data_user_upposition->qualification_id_fk == 6 and $data_user_upposition->pv_upgrad >= 6000
    //     ) {

    //         $update_position = DB::table('customers')
    //             ->where('user_name', $data_user_upposition->user_name)
    //             ->update(['qualification_id' => 'MG']);
    //         $position =  'MG';
    //         DB::table('log_up_vl')->insert([
    //             'user_name' => $data_user_upposition->user_name,
    //             'introduce_id' => $data_user_upposition->introduce_id,
    //             'bonus_total' => $data_user_upposition->bonus_total,
    //             'old_lavel' => $data_user_upposition->qualification_id,
    //             'new_lavel' => 'MG',
    //             'status' => 'success'
    //         ]);
    //         $ms = $data_user_upposition->user_name
    //             . ' อัพตำแหน่งจาก ' . $data_user_upposition->qualification_id . ' เป็น MG';
    //         // Line::send($ms);
    //         @app(\App\Http\Controllers\Frontend\FC\LineController::class)->sendText($ms);
    //         return ['status' => 'success', 'message' => 'MG Success'];
    //     }


    //     $xvvip =  DB::table('customers')
    //         ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
    //         ->where('customers.introduce_id', '=', $data_user_upposition->user_name)
    //         ->where('dataset_qualification.id', '=', 5)
    //         ->count();

    //     if (
    //         $xvvip >= 4 and $data_user_upposition->qualification_id_fk == 5 and $data_user_upposition->pv_upgrad >= 3600
    //     ) {

    //         $update_position = DB::table('customers')
    //             ->where('user_name', $data_user_upposition->user_name)
    //             ->update(['qualification_id' => 'SVVIP']);
    //         $position =  'SVVIP';


    //         DB::table('log_up_vl')->insert([
    //             'user_name' => $data_user_upposition->user_name,
    //             'introduce_id' => $data_user_upposition->introduce_id,
    //             'bonus_total' => $data_user_upposition->bonus_total,
    //             'old_lavel' => $data_user_upposition->qualification_id,
    //             'new_lavel' => 'SVVIP',
    //             'status' => 'success'
    //         ]);
    //         $ms = $data_user_upposition->user_name
    //             . ' อัพตำแหน่งจาก ' . $data_user_upposition->qualification_id . ' เป็น SVVIP';
    //         // Line::send($ms);
    //         @app(\App\Http\Controllers\Frontend\FC\LineController::class)->sendText($ms);
    //         return ['status' => 'success', 'message' => 'SVVIP Success'];
    //     }

    //     return ['status' => 'fail', 'message' => 'ไม่มีการอัพตำแหน่ง'];
    // }
}
