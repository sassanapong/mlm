<?php

namespace App\Http\Controllers\Frontend\FC2024;

use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Phattarachai\LineNotify\Facade\Line;
use App\eWallet;

class RunPerDay_pv_ab03Controller extends Controller
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

        // self::$s_date =  date('Y-08-22 00:00:00');
        // self::$e_date =  date('Y-08-22 23:59:59');
        // $yesterday = Carbon::now()->subDay();
        // self::$y = $yesterday->year;
        // self::$m = '08';
        // self::$d = '22';

        // self::$date_action = Carbon::create(self::$y, self::$m, self::$d);

        // $data =  DB::table('report_pv_per_day_ab_balance_bonus9')
        //     ->where('date_action', self::$date_action)
        //     ->delete();
        // dd($data);
        // dd('ddsdsd'); 
        // dd(self::$y, self::$m, self::$d, self::$date_action);
    }

    public static function Runbonus9Perday()
    {

        RunPerDay_pv_ab03Controller::initialize();

        try {
            DB::beginTransaction();

            $pending =  DB::table('report_pv_per_day_ab_balance')
                ->where('status_bonus9', 'success')
                ->wheredate('date_action', self::$date_action)
                ->update(['status_bonus9' => 'pending']);


            $bonus_9_01 = RunPerDay_pv_ab03Controller::bonus_9_01();
            if ($bonus_9_01['status'] !== 'success') {
                throw new \Exception($bonus_9_01['message']);
            }

            dd($bonus_9_01);

            // $bonus_4_02 = RunPerDay_pv_ab02Controller::bonus_4_02();
            // if ($bonus_4_01['status'] !== 'success') {
            //     throw new \Exception($bonus_4_01['message']);
            // }


            // //คำนวนชุดนี้สุดท้าย ต้องมี code รัน
            // if ($bonus_4_01['status'] == 'success' and $bonus_4_02['status'] == 'success') {
            //     $bonus_4_03 = RunPerDay_pv_ab02Controller::bonus_4_03();
            //     DB::commit();

            //     $ms = "โบนัสบริหาร team 2 สายงาน(4)  \n" .
            //         $bonus_4_01['message'] . "\n" .
            //         $bonus_4_02['message'] . "\n" .
            //         $bonus_4_03['message'] . "\n";

            //     Line::send($ms);
            //     return $ms;
            // } else {
            //     DB::commit();

            //     $ms = "โบนัสบริหาร team 2 สายงาน(4 รอจ่ายเงิน)  \n" .
            //         $bonus_4_01['message'] . "\n" .
            //         $bonus_4_02['message'] . "\n";

            //     Line::send($ms);
            //     return $ms;
            // }
        } catch (\Exception $e) {
            DB::rollBack();
            Line::send($e->getMessage());
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()], 500);
        }
    }

    public static function bonus_9_01()
    {
        RunPerDay_pv_ab03Controller::initialize();

        $report_pv_per_day_ab_balance = DB::table('report_pv_per_day_ab_balance')
            ->where('status_bonus9', '=', 'pending')
            ->wheredate('date_action', self::$date_action)
            // ->where('user_name', '3199015')
            // ->limit(500)
            ->get();


        $k = 0;
        $report_bonus_register = array();
        foreach ($report_pv_per_day_ab_balance as $value) {


            $customer_username = $value->introduce_id;
            $arr_user = array();


            $i = 1;

            $x = 'start';
            $run_data_user =  DB::table('customers')
                ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date', 'customers.expire_date_bonus')
                // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                ->where('user_name', '=', $customer_username)
                ->first();


            if (empty($run_data_user)) {
                // break;
            } else {

                while ($x = 'start') {
                    if (empty($run_data_user->name)) {

                        $customer_username = $run_data_user->introduce_id;

                        $run_data_user =  DB::table('customers')
                            ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date', 'customers.expire_date_bonus')
                            // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                            ->where('user_name', '=', $customer_username)
                            ->first();
                    } else {

                        $run_data_user =  DB::table('customers')
                            ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date', 'customers.expire_date_bonus')
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


                        $expire_date_2 = $run_data_user->expire_date_bonus;

                        $expire_date = $expire_date_2;


                        if (strtotime($expire_date) < strtotime(self::$date_action) || $qualification_id == 'CM' || $qualification_id == 'MB') {
                            $i = $i;
                            $customer_username = $run_data_user->introduce_id;
                        } else {


                            $report_bonus_register[$value->user_name][$value->date_action][$i]['user_name'] = $value->user_name;
                            $report_bonus_register[$value->user_name][$value->date_action][$i]['qualification'] = $value->qualification_id;
                            $report_bonus_register[$value->user_name][$value->date_action][$i]['introduce_id'] = $value->introduce_id;
                            $report_bonus_register[$value->user_name][$value->date_action][$i]['rate'] = $value->rate;

                            $report_bonus_register[$value->user_name][$value->date_action][$i]['recive_user_name'] = $run_data_user->user_name;
                            $report_bonus_register[$value->user_name][$value->date_action][$i]['recive_introduce_id'] = $run_data_user->introduce_id;
                            $report_bonus_register[$value->user_name][$value->date_action][$i]['recive_qualification'] = $run_data_user->qualification_id;
                            $report_bonus_register[$value->user_name][$value->date_action][$i]['recive_expire_date'] = $expire_date;

                            $report_bonus_register[$value->user_name][$value->date_action][$i]['date_action'] = $value->date_action;
                            $report_bonus_register[$value->user_name][$value->date_action][$i]['g'] = $i;
                            $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus_full_8'] = $value->bonus_full;

                            if ($i == 1) {
                                $report_bonus_register[$value->user_name][$value->date_action][$i]['percen'] = 100;

                                if ($qualification_id == 'CM' || $qualification_id == 'MB') {
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['status'] = 'success';
                                    $i = $i;
                                } else {

                                    $wallet_total = ($value->bonus_full * 5 / $value->rate) * 100 / 100;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['tax_total'] = $wallet_total * 3 / 100;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus_full'] = $wallet_total;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                                    $i++;
                                }
                            } elseif ($i == 2) {
                                $report_bonus_register[$value->user_name][$value->date_action][$i]['percen'] = 60;


                                if ($qualification_id == 'CM' || $qualification_id == 'MO'  || $qualification_id == 'MB' || $qualification_id == 'VIP') {

                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['tax_total'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus_full'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['status'] = 'success';
                                    $i = $i;
                                } else {

                                    $wallet_total = ($value->bonus_full * 5 / $value->rate) * 60 / 100;

                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['tax_total'] = $wallet_total * 3 / 100;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus_full'] = $wallet_total;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                                    $i++;
                                }
                            } elseif ($i == 3) {
                                $report_bonus_register[$value->user_name][$value->date_action][$i]['percen'] = 50;


                                if ($qualification_id == 'CM' || $qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP') {
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['tax_total'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus_full'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['status'] = 'success';
                                    $i = $i;
                                } else {

                                    $wallet_total = ($value->bonus_full * 5 / $value->rate) * 50 / 100;

                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['tax_total'] = $wallet_total * 3 / 100;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus_full'] = $wallet_total;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                                    $i++;
                                }
                            } elseif ($i == 4) {
                                $report_bonus_register[$value->user_name][$value->date_action][$i]['percen'] = 20;



                                if ($qualification_id == 'CM' || $qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP') {
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['tax_total'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus_full'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['status'] = 'success';
                                    $i = $i;
                                } else {

                                    $wallet_total = ($value->bonus_full * 5 / $value->rate) * 20 / 100;

                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['tax_total'] = $wallet_total * 3 / 100;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus_full'] = $wallet_total;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                                    $i++;
                                }
                            } elseif ($i == 5) {
                                $report_bonus_register[$value->user_name][$value->date_action][$i]['percen'] = 20;



                                if ($qualification_id == 'CM' || $qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP') {
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['tax_total'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus_full'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['status'] = 'success';
                                    $i = $i;
                                } else {
                                    $wallet_total = ($value->bonus_full * 5 / $value->rate) * 20 / 100;

                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['tax_total'] = $wallet_total * 3 / 100;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus_full'] = $wallet_total;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                                    $i++;
                                }
                            } elseif ($i == 6) {
                                $report_bonus_register[$value->user_name][$value->date_action][$i]['percen'] = 20;



                                if ($qualification_id == 'CM' || $qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP') {
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['tax_total'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus_full'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['status'] = 'success';
                                    $i = $i;
                                } else {
                                    $wallet_total = ($value->bonus_full * 5 / $value->rate) * 20 / 100;

                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['tax_total'] = $wallet_total * 3 / 100;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus_full'] = $wallet_total;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                                    $i++;
                                }
                            } elseif ($i >= 7 || $i <= 8) {
                                $report_bonus_register[$value->user_name][$value->date_action][$i]['percen'] = 10;

                                if ($qualification_id == 'CM' || $qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP') {
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['tax_total'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus_full'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus'] = 0;
                                    // $report_bonus_register[$value->user_name][$value->date_action][$i]['status'] = 'success';
                                    $i = $i;
                                } else {
                                    $wallet_total = ($value->bonus_full * 5 / $value->rate) * 10 / 100;

                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['tax_total'] = $wallet_total * 3 / 100;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus_full'] = $wallet_total;
                                    $report_bonus_register[$value->user_name][$value->date_action][$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                                    $i++;
                                }
                            }

                            if ($i == 9) {
                                $x = 'stop';
                                break;
                            } else {
                                $customer_username = $run_data_user->introduce_id;
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


    public static function check_introduce_id($username)
    {

        $customer_username = $username;
        $report_bonus_register = array();
        $i = 1;

        $x = 'start';
        $run_data_user =  DB::table('customers')
            ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
            // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
            ->where('user_name', '=', $customer_username)
            ->first();

        if (empty($run_data_user)) {
            return $report_bonus_register;
        } else {

            while ($x = 'start') {
                if (empty($run_data_user->name)) {

                    $customer_username = $run_data_user->introduce_id;

                    $run_data_user =  DB::table('customers')
                        ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date', 'customers.expire_date_bonus')
                        // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                        ->where('user_name', '=', $customer_username)
                        ->first();
                } else {

                    $run_data_user =  DB::table('customers')
                        ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date', 'customers.expire_date_bonus')
                        // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                        ->where('user_name', '=', $customer_username)
                        ->first();

                    if (empty($run_data_user)) {
                        $x = 'stop';
                        break;
                    }

                    $report_bonus_register[$i]['user_name'] = $run_data_user->user_name;
                    $report_bonus_register[$i]['name'] = $run_data_user->name;
                    $report_bonus_register[$i]['introduce_id'] = $run_data_user->introduce_id;
                    $report_bonus_register[$i]['qualification'] = $run_data_user->qualification_id;
                    $report_bonus_register[$i]['expire_date'] = $run_data_user->expire_date_bonus;
                    $report_bonus_register[$i]['g'] = $i;
                    $i++;
                    $customer_username = $run_data_user->introduce_id;
                }
            }
        }

        return  $report_bonus_register;
    }


    public static function bonus_9_ewallet() //เริ่มการจ่ายเงิน    
    {
        RunPerDay_pv_ab03Controller::initialize();
        $action_date = self::$date_action;
        $date = date('Y/m/d', strtotime($action_date));
        // dd(self::$date_action);
        $c = DB::table('report_pv_per_day_ab_balance_bonus9')
            ->select(
                'recive_user_name',
                DB::raw('SUM(bonus_full) as bonus_full'),
                DB::raw('SUM(bonus) as el'),
                DB::raw('SUM(tax_total) as tax_total'),
                'date_action'
            )
            ->where('status', '=', 'pending')
            // ->where('recive_user_name', '1169186')
            ->limit('500')
            ->wheredate('date_action', '=', $action_date)
            ->groupby('recive_user_name', 'date_action')
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
                    'note_orther' => "โบนัส MATCHING ($date)",
                    'receive_date' => now(),
                    'receive_time' => now(),

                    'type' => 13,
                    'status' => 2,
                ];

                $query =  eWallet::create($dataPrepare);
                DB::table('report_pv_per_day_ab_balance_bonus9')
                    ->where('recive_user_name', $value->recive_user_name)
                    ->wheredate('date_action', '=', $action_date)
                    ->update(['status' => 'success']);

                $i++;


                $id_card =  DB::table('customers')
                    ->where('customers.id_card', '=', $value->recive_user_name)
                    ->count();

                if ($id_card > 1) {
                    $id_card =  DB::table('customers')
                        ->select(
                            'customers.user_name',
                        )
                        ->where('customers.id_card', '=', $value->id_card)
                        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        ->orderByDesc('dataset_qualification.id')
                        ->first();
                    $user_name = $id_card->user_name;
                } else {
                    $user_name = $value->user_name;
                }

                $up_lv = RunPerDay_pv_ab03Controller::up_lv($user_name);
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


        $bonus_full = DB::table('report_pv_per_day_ab_balance_bonus9')
            ->where('status', 'success')
            ->where('recive_user_name', $data_user_upposition->user_name)
            ->sum('bonus_full');
        if (
            $data_user_upposition->qualification_id_fk == 4 and $data_user_upposition->pv_upgrad >= 2400 and $bonus_full >= 12000
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
            $ms = $data_user_upposition->user_name . ' อัพตำแหน่งจาก ' . $data_user_upposition->qualification_id . ' เป็น XVVIP';
            Line::send($ms);
            return ['status' => 'success', 'message' => 'XVVIP Success'];
        }

        return ['status' => 'fail', 'message' => 'ไม่มีการอัพตำแหน่ง'];
    }
}
