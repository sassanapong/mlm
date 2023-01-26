<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customers;
use App\Jang_pv;
use App\Report_bonus_cashback;
use DB;
use DataTables;
use Auth;
use App\eWallet;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Mpdf\Tag\Em;
use PhpParser\Node\Stmt\Return_;

class BonusCashBackController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }

    public static function RunBonusCashBack($code)
    {

        $jang_pv = DB::table('jang_pv')
            ->where('code', '=', $code)
            ->first();

        if (empty($jang_pv)) {
            $data = ['status' => 'fail', 'ms' => 'ไม่พบข้อมูลที่นำไปประมวลผล'];
            return $data;
        }
        $customer_username = $jang_pv->to_customer_username;
        $arr_user = array();
        $report_bonus_cashback = array();
        for ($i = 1; $i <= 10; $i++) {
            $x = 'start';
            $data_user =  DB::table('customers')
                ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
                // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                ->where('user_name', '=', $customer_username)
                ->first();
            if ($i == 1) {
                $name_g1 = $data_user->name . ' ' . $data_user->last_name;
            }
            // dd($customer_username);

            if (empty($data_user)) {
                $rs = Report_bonus_cashback::insert($report_bonus_cashback);
                return $rs;
            }


            while ($x = 'start') {
                if (empty($data_user->expire_date) || empty($data_user->name) || (strtotime($data_user->expire_date) < strtotime(date('Ymd')))) {
                    $customer_username = $data_user->introduce_id;
                    $data_user =  DB::table('customers')
                        ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
                        // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                        ->where('user_name', '=', $customer_username)
                        ->first();
                } else {
                    if ($data_user->qualification_id == '' || $data_user->qualification_id == null || $data_user->qualification_id == '-') {
                        $qualification_id = 'MB';
                    } else {
                        $qualification_id = $data_user->qualification_id;
                    }

                    $report_bonus_cashback[$i]['user_name'] = $jang_pv->to_customer_username;
                    $report_bonus_cashback[$i]['name'] = $name_g1;
                    $report_bonus_cashback[$i]['user_name_g'] = $data_user->user_name;
                    $report_bonus_cashback[$i]['name_g'] = $data_user->name . ' ' . $data_user->last_name;
                    $report_bonus_cashback[$i]['code'] = $jang_pv->code;
                    $report_bonus_cashback[$i]['qualification'] = $qualification_id;
                    $report_bonus_cashback[$i]['g'] = $i;
                    $report_bonus_cashback[$i]['pv'] = $jang_pv->pv;

                    $code_bonus =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_bonus(9);

                    $report_bonus_cashback[$i]['code_bonus'] = $code_bonus;

                    $arr_user[$i]['user_name'] = $data_user->user_name;
                    $arr_user[$i]['lv'] = [$i];
                    if ($i <= 3) {
                        $report_bonus_cashback[$i]['percen'] = 30;
                        $arr_user[$i]['bonus_percen'] = 30;
                        $arr_user[$i]['pv'] = $jang_pv->pv;
                        $arr_user[$i]['position'] = $qualification_id;
                        $wallet_total = $jang_pv->pv * 30 / 100;
                        $arr_user[$i]['bonus'] = $wallet_total;
                        $report_bonus_cashback[$i]['tax_total'] = $wallet_total * 3 / 100;
                        $report_bonus_cashback[$i]['bonus_full'] = $wallet_total;
                        $report_bonus_cashback[$i]['bonus'] = $wallet_total - ($wallet_total * 3 / 100);
                    } elseif ($i >= 4 and $i <= 5) {
                        $report_bonus_cashback[$i]['percen'] = 30;
                        $arr_user[$i]['bonus_percen'] = 30;
                        $arr_user[$i]['pv'] = $jang_pv->pv;
                        $arr_user[$i]['position'] = $qualification_id;

                        if ($i == 4 and $qualification_id == 'MB') {
                            $report_bonus_cashback[$i]['tax_total'] = 0;
                            $report_bonus_cashback[$i]['bonus_full'] = 0;
                            $report_bonus_cashback[$i]['bonus'] = 0;

                            $arr_user[$i]['bonus'] = 0;
                        } elseif ($i == 5 and ($qualification_id == 'MB' || $qualification_id == 'MO')) {
                            $report_bonus_cashback[$i]['tax_total'] = 0;
                            $report_bonus_cashback[$i]['bonus_full'] = 0;
                            $report_bonus_cashback[$i]['bonus'] = 0;
                            $arr_user[$i]['bonus'] = 0;
                        } else {
                            $wallet_total = $jang_pv->pv * 30 / 100;
                            $arr_user[$i]['bonus'] = $wallet_total;
                            $report_bonus_cashback[$i]['tax_total'] = $wallet_total * 3 / 100;
                            $report_bonus_cashback[$i]['bonus_full'] = $wallet_total;
                            $report_bonus_cashback[$i]['bonus'] = $wallet_total - ($wallet_total * 3 / 100);
                        }
                    } else {
                        $report_bonus_cashback[$i]['percen'] = 30;
                        $arr_user[$i]['bonus_percen'] = 20;
                        $arr_user[$i]['pv'] = $jang_pv->pv;
                        $arr_user[$i]['position'] = $qualification_id;
                        if ($i == 6  and ($qualification_id == 'MB' || $qualification_id == 'MO')) {
                            $arr_user[$i]['bonus'] = 0;
                            $report_bonus_cashback[$i]['tax_total'] = 0;
                            $report_bonus_cashback[$i]['bonus_full'] = 0;
                            $report_bonus_cashback[$i]['bonus'] = 0;
                        } elseif (($i == 7 || $i == 8)  and ($qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP')) {
                            $arr_user[$i]['bonus'] = 0;
                            $report_bonus_cashback[$i]['tax_total'] = 0;
                            $report_bonus_cashback[$i]['bonus_full'] = 0;
                            $report_bonus_cashback[$i]['bonus'] = 0;
                        } elseif (($i == 9 || $i == 10)  and ($qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP' || $data_user->qualification_id == 'VVIP')) {
                            $arr_user[$i]['bonus'] = 0;
                            $report_bonus_cashback[$i]['tax_total'] = 0;
                            $report_bonus_cashback[$i]['bonus_full'] = 0;
                            $report_bonus_cashback[$i]['bonus'] = 0;
                        } else {
                            $wallet_total = $jang_pv->pv * 20 / 100;
                            $arr_user[$i]['bonus'] = $wallet_total;
                            $report_bonus_cashback[$i]['tax_total'] = $wallet_total * 3 / 100;
                            $report_bonus_cashback[$i]['bonus_full'] = $wallet_total;
                            $report_bonus_cashback[$i]['bonus'] = $wallet_total - ($wallet_total * 3 / 100);
                        }
                    }

                    $customer_username = $data_user->introduce_id;
                    $x = 'stop';
                    break;
                }
            }
        }
        // dd($report_bonus_cashback);

        $rs = Report_bonus_cashback::insert($report_bonus_cashback);

        //$data = ['status'=>'success','ms'=>'success','arr_user'=>$arr_user,'report_bonus_cashback'=>$report_bonus_cashback];
        return $rs;
    }
}
