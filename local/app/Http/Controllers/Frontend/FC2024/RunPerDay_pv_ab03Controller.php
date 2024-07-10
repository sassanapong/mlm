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
        // self::$m = $yesterday->month;
        // self::$d = $yesterday->day;
        self::$m = 6;
        self::$d = 27;
        self::$date_action = Carbon::create(self::$y, self::$m, self::$d);

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
            // ->where('user_name', '4310586')
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
                ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
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
                            ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
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
                        }

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


                            if ($qualification_id == 'CM' || $qualification_id == 'MB' || $qualification_id == 'VIP') {

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


            $report_pv_per_day_ab_balance = DB::table('report_pv_per_day_ab_balance')
                ->where('id', $value->id)
                ->update([
                    'status_bonus9' => 'success',
                ]);

            $k++;
        }


        try {
            DB::BeginTransaction();

            // foreach ($report_bonus_register as $key => $date_action) {


            //     DB::table('report_pv_per_day_ab_balance_bonus9')
            //         ->updateOrInsert(
            //             ['user_name' => $value['user_name'], 'recive_user_name' => $value['recive_user_name'], 'g' => $value['g'], 'date_action' => $value['date_action']],
            //             $value
            //         );
            // }

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
            return ['status' => 'success', 'message' => 'เตรียมจ่ายโบนัส สำเร็จ (' . $k . ') รายการ คงเหลือ:' . $panding];
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
                        ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
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

                    $report_bonus_register[$i]['user_name'] = $run_data_user->user_name;
                    $report_bonus_register[$i]['name'] = $run_data_user->name;
                    $report_bonus_register[$i]['introduce_id'] = $run_data_user->introduce_id;
                    $report_bonus_register[$i]['qualification'] = $run_data_user->qualification_id;
                    $report_bonus_register[$i]['expire_date'] = $run_data_user->expire_date;
                    $report_bonus_register[$i]['g'] = $i;
                    $i++;
                    $customer_username = $run_data_user->introduce_id;
                }
            }
        }

        return  $report_bonus_register;
    }
}
