<?php

namespace App\Http\Controllers\Frontend\FC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\eWallet;
use Carbon\Carbon;

class RunPerDayPerMonthController extends Controller
{
    public $arr = array();
    public static function expire_180()
    {
        $fields = [
            'user_name',
            'pass_old',
            'password',
            'upline_id',
            'type_upline',
            'introduce_id',
            'ewallet',
            'ewallet_use',
            'ewallet_tranfer',
            'pv_all',
            'bonus_total',
            'pv',
            'pv_upgrad',
            'expire_date',
            'expire_date_bonus',
            'expire_insurance_date',
            'remain_date_num',
            'prefix_name',
            'name',
            'last_name',
            'gender',
            'business_name',
            'family_status',
            'id_card',
            'passport_no',
            'nation_id',
            'birth_day',
            'phone',
            'email',
            'line_id',
            'facebook',
            'profile_img',
            'qualification_id',
            'business_location_id',
            'regis_doc1_status',
            'regis_doc2_status',
            'regis_doc3_status',
            'regis_doc4_status',
            'vvip_register_type',
            'vvip_status_runbonus',
            'pv_upgrad_vvip',
            'status_runbonus_not_thai',
            'status_customer',
            'cancel_status_date',
            'status_runbonus_allsale_1',
            'created_at',
            'updated_at',
            'deleted_at',
            'status_runbonus_check_all',
            'pv_allsale_permouth',
            'type_app',
            'status_check_runupline',
            'status_run_pv_upline',
            'pv_today_downline_b',
            'pv_today_downline_a',
            'pv_today_downline_total',
            'pv_today',
            'terms_accepted',
            'terms_accepted_date'
        ];

        $results = DB::table('customers')
            ->select(array_merge($fields, ['id as customer_id_fk']))
            ->where('customers.expire_date', '<', Carbon::now()->subDays(90))
            ->where(function ($query) {
                $query->where('customers.name', '!=', '')
                    ->orWhereNotNull('customers.name');
            })
            ->where('customers.status_customer', '!=', 'cancel')
            ->where(function ($query) {
                $query->where('customers.expire_date_bonus', '<', Carbon::now()->subDays(90))
                    ->orWhereNull('customers.expire_date_bonus');
            })
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('db_orders')
                    ->whereRaw('db_orders.customers_id_fk = customers.id')
                    ->where('db_orders.created_at', '>=', Carbon::now()->subDays(90));
            })
            ->get();



        foreach ($results as $customer) {
            // แปลง stdClass เป็น array
            $customerData = (array) $customer;

            // เพิ่ม timestamp ให้ customers_expire_180
            $customerData['created_at'] = now();
            $customerData['updated_at'] = now();

            DB::table('customers_expire_180')->insert($customerData);

            // อัปเดตสถานะ และลบข้อมูลส่วนตัวของลูกค้า
            DB::table('customers')
                ->where('id', $customer->customer_id_fk)
                ->update([
                    'name' => null,
                    'last_name' => null,
                    'id_card' => null,
                    'password' => null,
                    'status_customer' => 'cancel',
                    'updated_at' => now(),
                ]);

            // ลบข้อมูลที่อยู่และอื่นๆ ที่เกี่ยวข้อง
            DB::table('customers_address_card')->where('customers_id', $customer->customer_id_fk)->delete();
            DB::table('customers_address_delivery')->where('customers_id', $customer->customer_id_fk)->delete();
            DB::table('customers_bank')->where('customers_id', $customer->customer_id_fk)->delete();
            DB::table('customers_benefit')->where('customers_id', $customer->customer_id_fk)->delete();
        }

        return 'success';
    }

    public static function run_report_pv_ewallet()
    {
        $report_pv_ewallet = DB::table('report_pv_ewallet')
            ->whereDate('created_at', '=', date('Y-m-d'))
            ->first();
        if (empty($report_pv_ewallet)) {
            $customers = DB::table('customers')
                ->selectRaw('sum(ewallet) as total_ewallet,sum(pv) as pv_total')
                ->where('user_name', '!=', '0534768')
                ->where('status_customer', '!=', 'cancel')
                ->first();

            $dataPrepare = [
                'ewallet' =>  $customers->total_ewallet,
                'pv' => $customers->pv_total,
            ];
            // dd($dataPrepare);
            $update =  DB::table('report_pv_ewallet')
                ->Insert($dataPrepare);
            return  $update;
        } else {
            return 'success';
        }
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
            ->where('user_name', '!=', '0534768')
            ->where('introduce_id', '!=', '0534768')
            ->first();

        $data_ewallet_total =  $ewallet_total->ewallet_total;
        dd($data_ewallet_total);
    }
}
