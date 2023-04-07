<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customers;
use App\Jang_pv;
use App\Report_bonus_active;
use App\Report_bonus_copyright;
use DB;
use DataTables;
use Auth;
use App\eWallet;
use Illuminate\Support\Arr;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class BonusCopyrightController extends Controller
{
    public $arr = array();

    public static function RunBonus_copyright_1() //โบนัสเจ้าขอลิขสิท
    {
        $date = now();
        $date = date("Y-m-d", strtotime("-1 day", strtotime($date)));
        // dd();
        $report_bonus_active1 =  DB::table('report_bonus_active') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
            ->selectRaw('user_name,code,count(code) as count_code')
            ->havingRaw('count(g) > 1 ')
            // ->wheredate('date_active', '=', $date)
            ->wheredate('date_active', '=', $date)
            ->where('g', '=', 1)
            ->where('status_copyright', '=', 'panding')
            ->groupby('code', 'g')
            ->get();

        // $report_bonus_active =  DB::table('report_bonus_active') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        // ->selectRaw('*')
        // ->where('code', '=', 'PV6511-00006578')
        // ->get();

        if ($report_bonus_active1) {
            foreach ($report_bonus_active1 as $value) {
                $check =  DB::table('report_bonus_active') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
                    ->selectRaw('count(user_name) as count_name,code')
                    ->where('code', '=', $value->code)
                    ->where('g', '=', 1)
                    ->where('status_copyright', '=', 'panding')
                    ->wheredate('date_active', '=', $date)
                    ->groupby('user_name')
                    ->first();
                if ($check->count_name >= 2) {

                    $count_all =  DB::table('report_bonus_active') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
                        ->where('code', '=', $value->code)
                        ->count();


                    $g = $count_all / $value->count_code;

                    $limit =  DB::table('report_bonus_active') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
                        ->selectRaw('id,code,g')
                        ->where('code', '=', $value->code)
                        ->orderby('id')
                        ->limit($g)
                        ->get();

                    foreach ($limit as $value_limit) {
                        $deleted = DB::table('report_bonus_active')
                            ->where('code', '=', $value->code)
                            ->where('id', '=', $value_limit->id)->delete();
                    }
                }
            }
        }
        // dd('success');


        $report_bonus_active =  DB::table('report_bonus_active') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
            ->selectRaw('user_name_g,sum(bonus) as total_bonus,date_active')
            ->where('status', '=', 'success')
            // ->where('user_name_g', '=', '1299201')
            // ->wheredate('date_active', '=',$date)
            ->wheredate('date_active', '=', $date)
            ->where('status_copyright', '=', 'panding')
            ->groupby('user_name_g')
            ->get();
        //dd($report_bonus_active);

        if (count($report_bonus_active) <= 0) {
            return 'success ทั้งหมดแล้ว';
        }
        $rs_array = array();
        $upline_arr = array();
        $k = 0;
        foreach ($report_bonus_active as $value) {

            $date_bonus_active = $value->date_active;
            $k++;


            $introduce = DB::table('customers')->select(
                'customers.introduce_id'
            )
                ->where('user_name', '=', $value->user_name_g)
                ->first();

            $user_name = @$introduce->introduce_id;

            if ($introduce and $introduce->introduce_id != 'AA') {
                $i = 1;
                $j = 1;
                while ($i <= 10) { //ค้นหาด้านบน 6 ชั้น
                    $up = DB::table('customers')->select(
                        'customers.pv',
                        'customers.id',
                        'customers.name',
                        'customers.last_name',
                        'customers.user_name',
                        'customers.qualification_id',
                        'customers.expire_date',
                        'dataset_qualification.code',
                        'customers.introduce_id'
                    )
                        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        ->where('user_name', '=', $user_name)
                        ->first();




                    if (empty($up)) {
                        $status = 'fail';
                        $rs = '';
                        $i = 6;
                        break;
                    }

                    if (empty($up->expire_date) || strtotime($up->expire_date) < strtotime(date('Ymd'))) {
                        $status = 'fail';
                        $user_name = $up->introduce_id;
                    } elseif (empty($up->name) || $up->name == '') {
                        $status = 'fail';
                        $user_name = $up->introduce_id;
                    } else {

                        if ($up->code == '' || $up->code == null || $up->code == '-') {
                            $qualification_id = 'MB';
                        } else {
                            $qualification_id = $up->code;
                        }


                        $name = $up->name . ' ' . $up->last_name;
                        if ($j == 1) {
                            $percen = 20;

                            if ($qualification_id == 'MB') {
                                $bonus_copyright = 0;
                            } else {
                                $bonus_copyright = $value->total_bonus * 20 / 100;
                            }
                        } elseif ($j == 2) {
                            $percen = 15;
                            if ($qualification_id == 'MB' || $qualification_id == 'MO') {
                                $bonus_copyright = 0;
                            } else {
                                $bonus_copyright = $value->total_bonus * 15 / 100;
                            }
                        } elseif ($j == 3) {
                            $percen = 5;
                            if ($qualification_id == 'MB' || $qualification_id == 'MO') {
                                $bonus_copyright = 0;
                            } else {
                                $bonus_copyright = $value->total_bonus * 5 / 100;
                            }
                        } elseif ($j == 4) {


                            $percen = 4;
                            if ($qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP') {
                                $bonus_copyright = 0;
                            } else {
                                $bonus_copyright = $value->total_bonus * 4 / 100;
                            }
                        } elseif ($j >= 5 and $j <= 6) {
                            $percen = 3;
                            if ($qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP') {
                                $bonus_copyright = 0;
                            } else {
                                $bonus_copyright = $value->total_bonus * 3 / 100;
                            }
                        } elseif ($j >= 7 and $j <= 8) {
                            $percen = 3;
                            if ($qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP' || $qualification_id == 'VVIP') {
                                $bonus_copyright = 0;
                            } else {
                                $bonus_copyright = $value->total_bonus * 3 / 100;
                            }
                        } elseif ($j >= 8 and $j <= 10) {
                            $percen = 2;
                            if ($qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP' || $qualification_id == 'VVIP') {
                                $bonus_copyright = 0;
                            } else {
                                $bonus_copyright = $value->total_bonus * 2 / 100;
                            }
                        }

                        $user_name = $up->introduce_id;
                        $upline_arr[$value->user_name_g][] = [
                            'user_name' => $up->user_name, 'name' => $name, 'postion' => $up->qualification_id,
                            'g' => $j, 'percen' => $percen, 'bonus' => $bonus_copyright, 'date_active' => $date_bonus_active
                        ];
                        $i++;
                        $j++;
                        $status = 'success';
                    }
                }
                if ($upline_arr) {
                    // if($k == 3){
                    //     dd($date_bonus_active);
                    // }

                    $rs_array[] = ['user_name' => $value->user_name_g, 'bonus' => $value->total_bonus, 'date_active' => $date_bonus_active, 'sponser_all' => $upline_arr[$value->user_name_g]];
                } else {
                    $rs_array[] = ['user_name' => $value->user_name_g, 'bonus' => $value->total_bonus, 'date_active' => $date_bonus_active, 'sponser_all' => null];
                }
            } else {

                $rs_array[] = ['user_name' => $value->user_name_g, 'bonus' => $value->total_bonus, 'date_active' => $date_bonus_active, 'sponser_all' => null];
            }
        }
        //dd($rs_array);



        try {
            DB::BeginTransaction();

            foreach ($rs_array as $value) {
                if ($value['sponser_all']) {
                    foreach ($value['sponser_all'] as $sponser_all) {

                        $dataPrepare = [
                            'user_name_bonus_active' => $value['user_name'],
                            'bonus' => $value['bonus_full'],
                            'user_name_g' => $sponser_all['user_name'],
                            'name_g' =>  $sponser_all['name'],
                            'postion_g' => $sponser_all['postion'],
                            'g' => $sponser_all['g'],
                            'percen_g' => $sponser_all['percen'],
                            'bonus_g' => $sponser_all['bonus'],
                            'date' => $sponser_all['date_active'],
                        ];

                        // DB::table('run_warning_copyright')
                        // ->Insert([$dataPrepare]);

                        DB::table('run_warning_copyright')
                            ->updateOrInsert(
                                ['user_name_bonus_active' => $value['user_name'], 'user_name_g' => $sponser_all['user_name'], 'date' => $sponser_all['date_active']],
                                $dataPrepare
                            );
                    }
                    DB::table('report_bonus_active')
                        ->where('user_name_g', '=', $value['user_name'])
                        ->wheredate('date_active', '=', $sponser_all['date_active'])
                        ->update(['status_copyright' => 'process', 'date_run_copyright' => date('Y-m-d')]);
                } else {
                    DB::table('report_bonus_active')
                        ->where('user_name_g', $value['user_name'])
                        ->wheredate('date_active', '=', $value['date_active'])
                        ->update(['status_copyright' => 'success', 'date_run_copyright' => date('Y-m-d')]);
                }
            }

            DB::table('log_run_bonus')
                ->Insert(['date_run' => $date_bonus_active, 'status' => 'success', 'type' => 'bonus_active_1']);

            DB::commit();
            // return 'success';
            $data = ['success', 'total' => count($report_bonus_active), 'process' => $k, 'date' => $date_bonus_active];

            return  $data;
        } catch (Exception $e) {
            DB::rollback();
            return 'fail';
        }
    }

    public static function RunBonus_copyright_2()
    {

        $report_bonus_active =  DB::table('run_warning_copyright') //รายชื่อคนที่มีรายการแจงโบนัสข้อ 6
            ->selectRaw('user_name_g,sum(bonus_g) as total_bonus,date')
            // ->where('total_bonus', '>', 0)
            ->where('status', '=', 'panding')
            ->groupby('user_name_g', 'date')
            ->get();
        $i = 0;
        try {
            DB::BeginTransaction();
            foreach ($report_bonus_active as $value) {
                $dataPrepare = [
                    'customer_user' => $value->user_name_g,
                    'tax_total' =>  $value->total_bonus * 3 / 100,
                    'bonus_full' =>  $value->total_bonus,
                    'total_bonus' => $value->total_bonus - $value->total_bonus * 3 / 100,
                    'date_active' => $value->date,
                ];
                // dd($dataPrepare);

                DB::table('report_bonus_copyright')
                    ->updateOrInsert(
                        ['customer_user' => $value->user_name_g, 'date_active' => $value->date],
                        $dataPrepare
                    );
                $i++;
            }
            DB::table('log_run_bonus')
                ->Insert(['date_run' => $value->date, 'status' => 'success', 'type' => 'bonus_active_2']);
            DB::commit();
            $data = ['success', 'total' => count($report_bonus_active), 'process' => $i];
            // dd($data);
            return  $data;
        } catch (Exception $e) {
            DB::rollback();
            return $rs = false;
        }
    }

    public static function RunBonus_copyright_3() //โบนัสเจ้าขอลิขสิท
    {

        $report_bonus_copyright = DB::table('report_bonus_copyright')
            ->selectRaw('customer_user,sum(total_bonus) as total_bonus,date_active')
            ->where('status', '=', 'panding')
            // ->where('total_bonus', '>', 0)
            ->groupby('customer_user', 'date_active')
            ->get();



        $i = 0;
        $j = 0;


        foreach ($report_bonus_copyright as $value) {

            $code_bonus = \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_bonus(7);

            if ($value->total_bonus > 0) {
                $i++;
                $bonus_tax = $value->total_bonus - $value->total_bonus * 3 / 100;
                $wallet_g = DB::table('customers')
                    ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'bonus_total')
                    ->where('user_name', $value->customer_user)
                    ->first();

                if ($wallet_g->ewallet == '' || empty($wallet_g->ewallet)) {
                    $wallet_g_user = 0;
                } else {

                    $wallet_g_user = $wallet_g->ewallet;
                }

                if ($wallet_g->bonus_total == '' || empty($wallet_g->bonus_total)) {
                    $bonus_total = 0 + $value->total_bonus;
                } else {

                    $bonus_total = $wallet_g->bonus_total + $bonus_tax;
                }

                if ($wallet_g->ewallet_use == '' || empty($wallet_g->ewallet_use)) {
                    $ewallet_use = 0;
                } else {

                    $ewallet_use = $wallet_g->ewallet_use;
                }
                $eWallet_copyright = new eWallet();
                $wallet_g_total = $wallet_g_user +  $bonus_tax;
                $ewallet_use_total =  $ewallet_use + $bonus_tax;

                $eWallet_copyright->transaction_code = $code;
                $eWallet_copyright->customers_id_fk = $wallet_g->id;
                $eWallet_copyright->customer_username = $value->customer_user;
                // $eWallet_copyright->customers_id_receive = $user->id;
                // $eWallet_copyright->customers_name_receive = $user->user_name;
                $eWallet_copyright->tax_total = $value->total_bonus * 3 / 100;
                $eWallet_copyright->bonus_full = $value->total_bonus;
                $eWallet_copyright->amt = $bonus_tax;
                $eWallet_copyright->old_balance = $wallet_g_user;
                $eWallet_copyright->balance = $wallet_g_total;
                $eWallet_copyright->type = 9;
                $eWallet_copyright->note_orther = 'โบนัสเจ้าของลิขสิทธิ์ วันที่ ' . date('Y/m/d', strtotime($value->date_active));
                $eWallet_copyright->receive_date = now();
                $eWallet_copyright->receive_time = now();
                $eWallet_copyright->status = 2;


                try {
                    DB::BeginTransaction();
                    $eWallet_copyright->save();

                    DB::table('customers')
                        ->where('user_name', $value->customer_user)
                        ->update(['ewallet' => $wallet_g_total, 'ewallet_use' => $ewallet_use_total, 'bonus_total' => $bonus_total]);

                    DB::table('report_bonus_copyright')
                        ->where('customer_user',  $value->customer_user)
                        ->wheredate('date_active', $value->date_active)
                        ->update(['status' => 'success']);

                    DB::table('run_warning_copyright')
                        ->where('user_name_g',  $value->customer_user)
                        ->wheredate('date', $value->date_active)
                        ->update(['status' => 'success']);

                    DB::table('report_bonus_active')
                        ->where('user_name_g', $value->customer_user)
                        ->wheredate('date_active', $value->date_active)
                        ->update(['status_copyright' => 'success']);
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollback();
                    return 'fail';
                }
            } else {
                try {
                    DB::BeginTransaction();

                    $j++;
                    DB::table('report_bonus_copyright')
                        ->where('customer_user',  $value->customer_user)
                        ->wheredate('date_active', $value->date_active)
                        ->update(['status' => 'success']);

                    DB::table('run_warning_copyright')
                        ->where('user_name_g',  $value->customer_user)
                        ->wheredate('date', $value->date_active)
                        ->update(['status' => 'success']);

                    DB::table('report_bonus_active')
                        ->where('user_name_g', $value->customer_user)
                        ->wheredate('date_active', $value->date_active)
                        ->update(['status_copyright' => 'success']);
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollback();
                    return 'fail';
                }
            }
        }

        DB::table('log_run_bonus')
            ->Insert(['date_run' => $value->date_active, 'status' => 'success', 'type' => 'bonus_active_3']);

        $data = ['success', 'total' => count($report_bonus_copyright), 'process > 0' => $i, 'process = 0' => $j];
        return $data;
    }
}
