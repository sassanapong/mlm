<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customers;
use App\Jang_pv;
use DB;
use DataTables;
use Auth;
use App\eWallet;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Mpdf\Tag\Em;

class ฺBonusCashBack extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }
    public function ฺRunBonusCashBack($code)
    {
        $jang_pv = DB::table('jang_pv')
        ->where('code','=',$code)
        ->first();

        if(empty($jang_pv)){
            $data = ['status'=>'fail','ms'=>'ไม่พบข้อมูลที่นำไปประมวลผล'];
            return $data;
        }
        $customer_username = $jang_pv->customer_username;
        $arr_user = array();
        for($i=1;$i<=10;$i++){
            $x = 'start';
            $data_user =  DB::table('customers')
            ->select('customers.name','customers.user_name','customers.introduce_id','customers.qualification_id','customers.expire_date')
            // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
            ->where('user_name','=',$customer_username)
            ->first();

            if(empty($data_user)){
                exit;
            }

            while($x = 'start') {
                if(empty($data_user->expire_date) || empty($data_user->name) || (strtotime($data_user->expire_date) < strtotime(date('Ymd'))) ){
                    $customer_username = $data_user->introduce_id;
                    $data_user =  DB::table('customers')
                    ->select('customers.name','customers.user_name','customers.introduce_id','customers.qualification_id','customers.expire_date')
                    // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                    ->where('user_name','=',$customer_username)
                    ->first();
                }else{
                    $arr_user[$i]=[$data_user->user_name];
                    $customer_username = $data_user->introduce_id;
                    break;
                }

            }
            dd($arr_user);
        }

    }



}
