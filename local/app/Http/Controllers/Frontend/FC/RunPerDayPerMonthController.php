<?php

namespace App\Http\Controllers\Frontend\FC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\eWallet;

class RunPerDayPerMonthController extends Controller
{
    public static function expire_180()
    {
        // $results = DB::select('SELECT id, user_name, expire_date, name, last_name FROM customers WHERE  (expire_date < DATE_SUB( NOW(), INTERVAL 180 DAY ) || ISNULL(expire_date) )
        // AND ( NAME != "" OR last_name != "" )
        // AND status_customer != "cancle" ORDER BY expire_date DESC');
        // dd($results);

        //รันทุกเดือน
        //ชื่อ//นามสกุล//id_card//รหัสเข้าระบบ//ลบที่อยู่ตามบัตรประชาชน//ที่อยู่ขนส่ง//bank//ภาพ
        //เรื่องเซิฟเวอขอใบเสนอราคา

        // foreach($results as $value){

        //           DB::table('customers')
        //               ->where('id', $value->id)
        //               ->update(['name' => null,
        //               'last_name' => null,
        //               'id_card'=>null,
        //               'password'=>null,
        //               'status_customer'=>'cancel',
        //             ]);

        //             DB::table('customers_address_card')
        //             ->where('customers_id', $value->id)
        //             ->delete();

        //             DB::table('customers_address_delivery')
        //             ->where('customers_id', $value->id)
        //             ->delete();

        //             DB::table('customers_bank')
        //             ->where('customers_id', $value->id)
        //             ->delete();

        //             DB::table('customers_benefit')
        //             ->where('customers_id', $value->id)
        //             ->delete();

        //        }
        return 'success';
    }

    public static function RunbonusPerday()
    {
        $current_time = date('H:i'); // รับค่าเวลาปัจจุบันในรูปแบบ HH:MM

        $date = now();
        $date = date("Y-m-d", strtotime("-1 day", strtotime($date)));


        if ($current_time >= '00:00' && $current_time <= '06:00') {
            // เงื่อนไขที่เวลาอยู่ระหว่าง 00:00 ถึง 06:00

            $log = DB::table('log_run_bonus')
                ->orderby('id', 'desc')
                ->first();

            if ($log->status == 'next') {
                if ($log->type == 'bonus_active_1') {
                    $data = \App\Http\Controllers\Frontend\BonusCopyrightController::RunBonus_copyright_2();
                } elseif ($log->type == 'bonus_active_2') {
                    $data = \App\Http\Controllers\Frontend\BonusCopyrightController::RunBonus_copyright_3();
                } elseif ($log->type == 'bonus_active_3') {
                    if ($date ==  $log->date_run) {
                        return $data = 'success full';
                    } else {
                        $data = \App\Http\Controllers\Frontend\BonusCopyrightController::RunBonus_copyright_1($date);
                    }
                } else {
                    return $data = 'success full';
                }
                return $data;
            } else {
                if ($log->type == 'bonus_active_1') {
                    $data = \App\Http\Controllers\Frontend\BonusCopyrightController::RunBonus_copyright_1($date);
                } elseif ($log->type == 'bonus_active_2' and $log->status == 'success') {
                    $data = \App\Http\Controllers\Frontend\BonusCopyrightController::RunBonus_copyright_2();
                } elseif ($log->type == 'bonus_active_3' and $log->status == 'success') {
                    $data = \App\Http\Controllers\Frontend\BonusCopyrightController::RunBonus_copyright_3();
                } else {
                    return $data = 'success full';
                }
                return $data;
            }
        } else {
            // เงื่อนไขที่เวลาไม่อยู่ระหว่าง 00:00 ถึง 06:00
            // $log = DB::table('log_run_bonus')
            //     ->orderby('id', 'desc')
            //     ->first();

            // if ($log->status == 'next') {
            //     if ($log->type == 'bonus_active_1') {
            //         $data = \App\Http\Controllers\Frontend\BonusCopyrightController::RunBonus_copyright_2();
            //     } elseif ($log->type == 'bonus_active_2') {
            //         $data = \App\Http\Controllers\Frontend\BonusCopyrightController::RunBonus_copyright_3();
            //     } elseif ($log->type == 'bonus_active_3') {
            //         if ($date ==  $log->date_run) {
            //             return $data = 'success full';
            //         } else {
            //             $data = \App\Http\Controllers\Frontend\BonusCopyrightController::RunBonus_copyright_1($date);
            //         }
            //     } else {
            //         return $data = 'success full';
            //     }
            //     return $data;
            // } else {
            //     if ($log->type == 'bonus_active_1') {
            //         $data = \App\Http\Controllers\Frontend\BonusCopyrightController::RunBonus_copyright_1($date);
            //     } elseif ($log->type == 'bonus_active_2' and $log->status == 'success') {
            //         $data = \App\Http\Controllers\Frontend\BonusCopyrightController::RunBonus_copyright_2();
            //     } elseif ($log->type == 'bonus_active_3' and $log->status == 'success') {
            //         $data = \App\Http\Controllers\Frontend\BonusCopyrightController::RunBonus_copyright_3();
            //     } else {
            //         return $data = 'success full';
            //     }
            //     return $data;
            // }

            return 'fail ';
        }
    }


    public static function Runbonus_all_ewallet()
    {
        $current_time = date('H:i'); // รับค่าเวลาปัจจุบันในรูปแบบ HH:MM

        $date = now();
        $date = date("Y-m-d", strtotime("-1 day", strtotime($date)));

        // EWที่สามารถใช้ได้ ทั้งระบบ

        $ewallet_total = DB::table('customers')
                ->selectRaw('SUM(ewallet) as ewallet_total')
                ->where('user_name','!=','0534768')
                ->where('introduce_id','!=','0534768')
                ->first();

        $data_ewallet_total =  $ewallet_total->ewallet_total;
        dd($data_ewallet_total);
    }


}
