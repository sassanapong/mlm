<?php

namespace App\Http\Controllers\Frontend\FC2024;

use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Phattarachai\LineNotify\Facade\Line;
use App\eWallet;

class RunPerDay_pv_ab04Controller extends Controller
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
    }


    public static function bonus_7_01($jang_pv_id_fk)
    {
        RunPerDay_pv_ab04Controller::initialize();

        $report_pv_per_day_ab_balance = DB::table('jang_pv')
            ->where('id', '=', $jang_pv_id_fk)
            ->where('status_run_bonus7', '=', 'pending')
            ->get();


        $k = 0;
        $report_bonus_register = array();
        foreach ($report_pv_per_day_ab_balance as $value) {


            $upline_id =  DB::table('customers')
                ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.upline_id', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
                // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                ->where('user_name', '=', $value->to_customer_username)
                ->first();

            $customer_username = $value->upline_id;
            $arr_user = array();
            $i = 1;

            $x = 'start';
            $run_data_user =  DB::table('customers')
                ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.upline_id', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
                // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                ->where('user_name', '=', $customer_username)
                ->first();


            if (empty($run_data_user)) {
                // break;
            } else {

                while ($x = 'start') {
                    if (empty($run_data_user->name)) {

                        $customer_username = $run_data_user->upline_id;

                        $run_data_user =  DB::table('customers')
                            ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.upline_id', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
                            // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                            ->where('user_name', '=', $customer_username)
                            ->first();
                    } else {

                        $run_data_user =  DB::table('customers')
                            ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
                            // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                            ->where('user_name', '=', $customer_username)
                            ->first();

                        if (empty($run_data_user)) {
                            $x = 'stop';
                            break;
                        }

                        if ($run_data_user->qualification_id == '' || $run_data_user->qualification_id == null || $run_data_user->qualification_id == '-') {
                            $qualification_id = 'CM';
                        } else {
                            $qualification_id = $run_data_user->qualification_id;
                        }

                        if (strtotime($run_data_user->expire_date) < strtotime(self::$date_action) || $qualification_id == 'CM' || $qualification_id == 'MB') {
                            $i = $i;
                            $customer_username = $run_data_user->upline_id;
                        } else {


                            $report_bonus_register[$value->user_name][$value->date_action][$i]['user_name'] = $value->user_name;
                            $report_bonus_register[$value->user_name][$value->date_action][$i]['qualification'] = $value->qualification_id;
                            $report_bonus_register[$value->user_name][$value->date_action][$i]['introduce_id'] = $value->introduce_id;
                            $report_bonus_register[$value->user_name][$value->date_action][$i]['rate'] = $value->rate;

                            $report_bonus_register[$value->user_name][$value->date_action][$i]['recive_user_name'] = $run_data_user->user_name;
                            $report_bonus_register[$value->user_name][$value->date_action][$i]['recive_introduce_id'] = $run_data_user->introduce_id;
                            $report_bonus_register[$value->user_name][$value->date_action][$i]['recive_qualification'] = $run_data_user->qualification_id;
                            $report_bonus_register[$value->user_name][$value->date_action][$i]['recive_expire_date'] = $run_data_user->expire_date;

                            $report_bonus_register[$value->user_name][$value->date_action][$i]['date_action'] = $value->date_action;
                            $report_bonus_register[$value->user_name][$value->date_action][$i]['g'] = $i;
                            $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus_full_7'] = $value->bonus_full;

                            if ($i == 1) {
                                $report_bonus_register[$value->user_name][$value->date_action][$i]['percen'] = 4;

                                if ($qualification_id == 'CM') {
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['status'] = 'success';
                                    $i = $i;
                                } else {

                                    $wallet_total = ($value->bonus_full) * 4 / 100;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['tax_total'] = $wallet_total * 3 / 100;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus_full'] = $wallet_total;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                                    $i++;
                                }
                            }

                            if ($i == 24) {
                                $x = 'stop';
                                break;
                            } else {
                                $customer_username = $run_data_user->upline_id;
                            }
                        }
                    }
                }
            }


            $report_pv_per_day_ab_balance = DB::table('report_pv_per_day_ab_balance')
                ->where('id', $value->id)
                ->update([
                    'status_bonus9' => 'success',
                ]);

            $k++;
        }


        try {
            DB::BeginTransaction();



            foreach ($report_bonus_register as $user_name => $dates) {
                foreach ($dates as $date_action => $records) {
                    foreach ($records as $value) {
                        DB::table('report_pv_per_day_ab_balance_bonus9')
                            ->updateOrInsert(
                                ['user_name' => $value['user_name'], 'recive_user_name' => $value['recive_user_name'], 'g' => $value['g'], 'date_action' => $value['date_action']],
                                $value
                            );
                    }
                }
            }

            $panding = DB::table('report_pv_per_day_ab_balance')
                ->where('status_bonus9', '=', 'pending')
                ->count();

            DB::commit();
            return ['status' => 'success', 'message' => 'เตรียมจ่ายโบนัส สำเร็จ (' . $k . ') รายการ คงเหลือ:' . $panding . ' วันที่:' . self::$date_action];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }


    public static function bonus_7_ewallet() //เริ่มการจ่ายเงิน    
    {
        RunPerDay_pv_ab04Controller::initialize();
        $action_date = self::$date_action;
        // dd(self::$date_action);
        $c = DB::table('report_pv_per_day_ab_balance_bonus9')
            ->select(
                'id',
                'recive_user_name',
                'user_name',
                'bonus_full as bonus_full',
                'bonus as el',
                'tax_total',
                'date_action'
            )
            ->where('status', '=', 'pending')
            // ->where('recive_user_name', '1169186')
            ->limit('500')
            ->wheredate('date_action', '=', $action_date)
            ->get();

        $i = 0;
        try {
            DB::BeginTransaction();

            foreach ($c as $value) {
                $customers = DB::table('customers')
                    ->select('id', 'user_name', 'ewallet', 'ewallet_use')
                    ->where('user_name', $value->recive_user_name)
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
                    ->where('user_name', $value->recive_user_name)
                    ->update(['ewallet' => $ew_total, 'ewallet_use' => $ew_use]);


                $count_eWallet =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_wallet();

                $dataPrepare = [
                    'transaction_code' => $count_eWallet,
                    'customers_id_fk' => $customers->id,
                    'customer_username' => $value->recive_user_name,
                    'tax_total' => $value->tax_total,
                    'bonus_full' => $value->bonus_full,
                    'amt' => $value->el,
                    'old_balance' => $customers->ewallet,
                    'balance' => $ew_total,
                    'note_orther' => "โบนัส MATCHING ($action_date) จากรหัส $value->user_name ",
                    'receive_date' => now(),
                    'receive_time' => now(),
                    'type' => 13,
                    'status' => 2,
                ];

                $query =  eWallet::create($dataPrepare);
                DB::table('report_pv_per_day_ab_balance_bonus9')
                    ->where('id', $value->id)
                    ->update(['status' => 'success']);

                $i++;
                DB::commit();
            }
            $c = DB::table('report_pv_per_day_ab_balance_bonus9')

                ->where('status', '=', 'pending')
                ->wheredate('date_action', '=', $action_date)
                ->count();

            return ['status' => 'success', 'message' => 'จ่ายโบนัส สำเร็จ (' . $i . ') รายการ คงเหลือ ' . $c];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }
}
