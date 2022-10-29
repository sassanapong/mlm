<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customers;
use App\Jang_pv;
use App\Report_bonus_active;
use DB;
use DataTables;
use Auth;
use App\eWallet;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Mpdf\Tag\Em;
use PhpParser\Node\Stmt\Return_;

class BonusActiveController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }

    public static function RunBonusActive($code)
    {

        $jang_pv = DB::table('jang_pv')
        ->where('code','=',$code)
        ->first();

  ;

        if(empty($jang_pv)){
            $data = ['status'=>'fail','ms'=>'ไม่พบข้อมูลที่นำไปประมวลผล'];
            return $data;
        }
        $customer_username = $jang_pv->to_customer_username;

        $data_user_g1 =  DB::table('customers')
        ->select('customers.name','customers.last_name','customers.user_name','customers.upline_id','customers.qualification_id','customers.expire_date')
        // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
        ->where('user_name','=',$customer_username)
        ->first();
        $customer_username = $data_user_g1->upline_id;
        $arr_user = array();
        $report_bonus_cashback = array();
        for($i=1;$i<=10;$i++){
            $x = 'start';
            $data_user =  DB::table('customers')
            ->select('customers.name','customers.last_name','customers.user_name','customers.upline_id','customers.qualification_id','customers.expire_date')
            // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
            ->where('user_name','=',$customer_username)
            ->first();
            if($i==1){
                $name_g1 = $data_user->name.' '.$data_user->last_name;
            }
            // dd($customer_username);

            if(empty($data_user)){
                $rs = Report_bonus_active::insert($report_bonus_cashback);
                return $rs;
            }


            while($x = 'start') {
                if(empty($data_user->expire_date) || empty($data_user->name) || (strtotime($data_user->expire_date) < strtotime(date('Ymd'))) ){
                    $customer_username = $data_user->upline_id;
                    $data_user =  DB::table('customers')
                    ->select('customers.name','customers.last_name','customers.user_name','customers.upline_id','customers.qualification_id','customers.expire_date')
                    // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                    ->where('user_name','=',$customer_username)
                    ->first();

                }else{
                    if($data_user->qualification_id == '' || $data_user->qualification_id == null || $data_user->qualification_id == '-'){
                        $qualification_id = 'MB';
                    }else{
                        $qualification_id = $data_user->qualification_id;
                    }

                    $report_bonus_cashback[$i]['user_name'] =$jang_pv->to_customer_username;
                    $report_bonus_cashback[$i]['name'] =$name_g1;
                    $report_bonus_cashback[$i]['user_name_g'] =$data_user->user_name;
                    $report_bonus_cashback[$i]['name_g'] =$data_user->name.' '.$data_user->last_name;
                    $report_bonus_cashback[$i]['code'] =$jang_pv->code;
                    $report_bonus_cashback[$i]['qualification'] = $qualification_id;
                    $report_bonus_cashback[$i]['g'] = $i;
                    $report_bonus_cashback[$i]['pv'] = $jang_pv->pv;

                    $y = date('Y') + 543;
                    $y = substr($y, -2);
                    $code_bonus =  IdGenerator::generate([
                        'table' => 'report_bonus_cashback',
                        'field' => 'code_bonus',
                        'length' => 15,
                        'prefix' => 'B6' . $y . '' . date("m") . '-',
                        // 'reset_on_prefix_change' => true
                    ]);

                    $report_bonus_cashback[$i]['code_bonus'] = $code_bonus;

                    $arr_user[$i]['user_name']=$data_user->user_name;
                    $arr_user[$i]['lv']=[$i];
                    if($i<= 5){
                        $report_bonus_cashback[$i]['percen'] = 20;
                        $arr_user[$i]['bonus_percen'] = 20;
                        $arr_user[$i]['pv'] = $jang_pv->pv;
                        $arr_user[$i]['position'] = $qualification_id;
                        $wallet_total=$jang_pv->pv * 20/100;
                        $arr_user[$i]['bonus'] = $wallet_total;
                        $report_bonus_cashback[$i]['bonus'] = $wallet_total;

                    }else{
                        $report_bonus_cashback[$i]['percen'] = 20;
                        $arr_user[$i]['bonus_percen'] = 20;
                        $arr_user[$i]['pv'] = $jang_pv->pv;
                        $arr_user[$i]['position'] = $qualification_id;

                        if($i == 6 and $qualification_id == 'MB' ){
                            $report_bonus_cashback[$i]['bonus'] = 0;
                            $arr_user[$i]['bonus'] = 0;
                        }elseif($i == 7 and ($qualification_id == 'MB' || $qualification_id == 'MO')){
                            $report_bonus_cashback[$i]['bonus'] = 0;
                            $arr_user[$i]['bonus'] = 0;

                        }elseif($i == 8 and ($qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP')){
                            $report_bonus_cashback[$i]['bonus'] = 0;
                            $arr_user[$i]['bonus'] = 0;

                        }elseif(($i == 9 || $i == 10) and ($qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP' || $qualification_id == 'VVIP')){
                            $report_bonus_cashback[$i]['bonus'] = 0;
                            $arr_user[$i]['bonus'] = 0;

                        }else{
                            $wallet_total=$jang_pv->pv * 20/100;
                            $arr_user[$i]['bonus'] = $wallet_total;
                            $report_bonus_cashback[$i]['bonus'] = $wallet_total;

                        }


                    }

                    $customer_username = $data_user->upline_id;
                    $x = 'stop';
                    break;
                }


            }

        }
        //  dd($report_bonus_cashback);

         $rs = Report_bonus_active::insert($report_bonus_cashback);

         //$data = ['status'=>'success','ms'=>'success','arr_user'=>$arr_user,'report_bonus_cashback'=>$report_bonus_cashback];
         return $rs;
    }



}
