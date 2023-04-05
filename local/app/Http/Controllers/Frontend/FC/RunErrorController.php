<?php

namespace App\Http\Controllers\Frontend\FC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\eWallet;

class RunErrorController extends Controller
{

    // public function __construct()
    // {
    //     $this->middleware('customer');
    // }



    public static function index()
    {

    //     $group = DB::table('log_up_vl')
    //     ->selectRaw('id,user_name,introduce_id')
    //     ->get();

    //     $i = 0;
    //    foreach($group as $value){
    //     $i++;
    //      $c=  DB::table('customers')
    //      ->select('introduce_id')
    //      ->where('user_name', $value->user_name)
    //      ->first();


    //     DB::table('log_up_vl')
    //           ->where('id','=',$value->id)
    //           ->update(['introduce_id' => @$c->introduce_id]);
    //         //   ->update(['pv_all' => $value->pv_total]);
    //    }
    //      dd($i,'success');


    //      $group = DB::table('report_bonus_register')
    //     ->selectRaw('id,regis_user_name')
    //     ->get();

    //     $i = 0;
    //    foreach($group as $value){
    //     $i++;
    //      $c=  DB::table('customers')
    //      ->select('introduce_id')
    //      ->where('user_name', $value->regis_user_name)
    //      ->first();


    //     DB::table('report_bonus_register')
    //           ->where('id','=',$value->id)
    //           ->update(['regis_user_introduce_id' => @$c->introduce_id]);
    //         //   ->update(['pv_all' => $value->pv_total]);
    //    }
    //      dd($i,'success');


        //  $group = DB::table('report_bonus_register_xvvip')
        //  ->selectRaw('id,user_name')
        //  ->get();

        //  $i = 0;
        // foreach($group as $value){
        //  $i++;
        //   $c=  DB::table('customers')
        //   ->select('introduce_id')
        //   ->where('user_name', $value->user_name)
        //   ->first();


        //  DB::table('report_bonus_register_xvvip')
        //        ->where('id','=',$value->id)
        //        ->update(['introduce_id' => @$c->introduce_id]);
        //      //   ->update(['pv_all' => $value->pv_total]);
        // }
        //   dd($i,'success');
    //     $group = DB::table('report_bonus_active')
    //     ->selectRaw('id,user_name')
    //     ->where('user_name','!=',null)
    //     ->where('g','=',1)
    //     ->get();


    //     $i = 0;
    //    foreach($group as $value){
    //     $i++;
    //      $c=  DB::table('customers')
    //      ->select('introduce_id')
    //      ->where('user_name', $value->user_name)
    //      ->first();

    //     DB::table('report_bonus_active')
    //           ->where('id','=',$value->id)
    //           ->update(['introduce_id' => @$c->introduce_id]);
    //         //   ->update(['pv_all' => $value->pv_total]);
    //    }
    //      dd($i,'success');





        // dd(1);

        // $data = DB::table('customers')
        // ->orderby('id')
        // ->where('password',null)
        // ->where('id','>=',50000)
        // ->where('id','<=',70000)

        // // ->offset(0)->limit(50000)
        // ->get();

        // //dd($data);
        // $i = 0;
        // foreach($data as $value){
        //     $i++;
        //      $pass = md5($value->pass_old);
        //     $affected = DB::table('customers')
        //       ->where('id', $value->id)
        //       ->update(['password' => $pass,'regis_doc1_status'=>1,'business_location_id'=>1]);
        // }

        // dd($i,'success');

        // $data = RunErrorController::import_ewallet();
        // dd($data);

        // $data = RunErrorController::import_ewallet_delete();
        // dd($data);



        // $data = RunErrorController::run_createdate();
        // dd($data);

        // $data = RunErrorController::run_mtdate();
        // dd($data);

        //  $data = RunErrorController::run_edit_upline();
        // dd($data);

        //    $data = RunErrorController::import_bonus_total();
        // dd($data);


        // $data = RunErrorController::run_bonus_total();
        // dd($data);
        // $data = RunErrorController::update_position();
        // dd($data);

        // $group = DB::table('customers')
        // ->select('qualification_id')
        // ->groupby('qualification_id')
        // ->get();

        // dd($group);

        // $c = DB::table('customers')
        // ->select('user_name','qualification_id')
        // ->where('qualification_id','-')
        // ->get();

        // dd($c);
        // $i = 0;
        // foreach ($c as $value) {
        //     DB::table('customers')
        //   ->where('user_name', $value->user_name)
        //    ->update(['qualification_id' =>'MO']);
        //    $i++;
        // }
        // $data = \App\Http\Controllers\Frontend\eWalletController::checkcustomer_upline();
        // dd($data);
        // dd($i,'success');

        // $data = RunErrorController::bonus_register();
        // dd($data);

        // $data = \App\Http\Controllers\Frontend\BonusCopyrightController::RunBonus_copyright_3();
        // dd($data);
        // dd($i,'success');

    //     $c = DB::table('customers')
    //     ->where('pv_upgrad', '=', null)
    //     ->limit(10000)
    //     ->get();

    // $i = 0;
    // foreach ($c as $value) {
    //     if($value->qualification_id == 'MB'){
    //         $pv = 20;
    //     }elseif($value->qualification_id == 'MO'){
    //         $pv = 400;
    //     }elseif($value->qualification_id == 'VIP'){
    //         $pv = 800;
    //     }elseif($value->qualification_id == 'VVIP'){
    //         $pv = 1200;
    //     }else{
    //         $pv = 0;
    //     }

    //     DB::table('customers')
    //         ->where('user_name', $value->user_name)
    //         ->update(['pv_upgrad' => $pv]);
    //     $i++;
    // }

    // dd($i, 'success');


        // return view('frontend/jp-clarify');
    }

    ////////////// เก็บไว้ใช้
        //     $group = DB::table('db_orders')
    //     ->selectRaw('customers_id_fk,customers_user_name,sum(pv_total) as pv_total')
    //     ->groupby('customers_id_fk')
    //     ->get();
    //     $i = 0;
    //    foreach($group as $value){
    //     $i++;
    //       DB::table('customers')
    //           ->where('id', $value->customers_id_fk)
    //           ->update(['pv_all' => $value->pv_total]);
    //    }
    //      dd($i,'success');

    //////////////
    public static function run_bonus_total()
    {



        ///////////////////// 3 ////////////////////////ลิขสิท รัน

        // $report_bonus_cashback =  DB::table('report_bonus_copyright') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        //     ->selectRaw('customer_user,sum(total_bonus) as total_bonus')
        //     ->where('status', '=', 'success')
        //     ->groupby('customer_user')
        //     ->get();

        // $i = 0;
        // foreach ($report_bonus_cashback as $value) {
        //     $i++;
        //     $wallet_g = DB::table('customers')
        //         ->select('bonus_total', 'user_name')
        //         ->where('user_name', $value->customer_user)
        //         ->first();
        //    if ($wallet_g->bonus_total == '' || empty($wallet_g->bonus_total)) {
        //             $bonus_total = 0;
        //         } else {

        //             $bonus_total = $wallet_g->bonus_total;
        //         }
        //     $bonus_total = $bonus_total + $value->total_bonus;
        //     DB::table('customers')
        //         ->where('user_name', $value->customer_user)
        //         ->update(['bonus_total' => $bonus_total]);
        // }
        // dd('success ' . $i . ' Total' . count($report_bonus_cashback));

        /////////////////////// 3 ////////////////////////สนับสนุนสินค้า ได้รับ

        // $jang_pv =  DB::table('jang_pv') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        //     ->selectRaw('customer_username as customer_user,sum(wallet) as total_bonus')
        //     ->where('type', '=', '1')
        //     ->where('status', '=', 'success')
        //     ->groupby('customer_username')
        //     ->get();
        //     // dd(count($jang_pv));

        // $i = 0;
        // foreach ($jang_pv as $value) {
        //     $i++;
        //     $wallet_g = DB::table('customers')
        //         ->select('bonus_total', 'user_name')
        //         ->where('user_name', $value->customer_user)
        //         ->first();
        //         if ($wallet_g->bonus_total == '' || empty($wallet_g->bonus_total)) {
        //             $bonus_total = 0;
        //         } else {

        //             $bonus_total = $wallet_g->bonus_total;
        //         }
        //     $bonus_total = $bonus_total + $value->total_bonus;
        //     DB::table('customers')
        //         ->where('user_name', $value->customer_user)
        //         ->update(['bonus_total' => $bonus_total]);
        // }
        // dd('success ' . $i . ' Total' . count($jang_pv));

        /////////////////////// 5 ////////////////////////

        // $db_orders =  DB::table('db_orders') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        //     ->selectRaw('customers_user_name as customer_user,sum(discount) as total_bonus')
        //     //  ->where('type', '=', '1')
        //     //  ->where('status', '=', 'success')
        //     ->groupby('customers_user_name')
        //     ->get();
        // // dd(count($jang_pv));

        // $i = 0;
        // foreach ($db_orders as $value) {
        //     $i++;
        //     $wallet_g = DB::table('customers')
        //         ->select('bonus_total', 'user_name')
        //         ->where('user_name', $value->customer_user)
        //         ->first();
        //     if ($wallet_g->bonus_total == '' || empty($wallet_g->bonus_total)) {
        //         $bonus_total = 0;
        //     } else {

        //         $bonus_total = $wallet_g->bonus_total;
        //     }

        //     $bonus_total = $bonus_total + $value->total_bonus;
        //     DB::table('customers')
        //         ->where('user_name', $value->customer_user)
        //         ->update(['bonus_total' => $bonus_total]);
        // }
        // dd('success ' . $i . ' Total' . count($db_orders));

        $data_user_1 =  DB::table('customers')
            ->select('customers.name', 'customers.last_name', 'bonus_total', 'customers.user_name', 'customers.upline_id',
             'customers.qualification_id', 'customers.expire_date','dataset_qualification.id as qualification_id_fk')
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            // ->where('user_name', '=', '1206695')
             ->where('dataset_qualification.id', '=', 4)// 4 - 7
            ->get();
            // $data_user =  DB::table('customers')
            //     ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            //     ->where('customers.introduce_id', '=', '1384810')
            //     ->where('dataset_qualification.id', '=', 4)
            //     ->count();//

            // dd($data_user_1,$data_user);


        $i = 0;
        $k = 0;
        // dd($data_user_1);
        //ขึ้น XVVIP แนะนำ 2 VVIP คะแนน 0ว
        foreach ($data_user_1 as $value) {
            $i++;
            $data_user =  DB::table('customers')
                ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                ->where('customers.introduce_id', '=', $value->user_name)
                ->where('dataset_qualification.id', '>=', 4)
                ->count();//

            // dd($data_user,$value->qualification_id,$value->qualification_id_fk);
            //$data_user >= 2 and $value->qualification_id != 'XVVIP' and  $value->qualification_id_fk< 5
            if ($data_user >= 200 and $value->qualification_id_fk== 9 ) { //MD
                $data_svvip =  DB::table('customers')
                    ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                    ->where('customers.introduce_id', '=', $value->user_name)
                    ->where('dataset_qualification.id', '=', 6)
                    ->count();
                if ($data_svvip >= 21 and $value->bonus_total >= 3000000) {

                        $k++;
                        // DB::table('customers')
                        //     ->where('user_name', $value->user_name)
                        //     ->update(['qualification_id' => 'MD']);
                        DB::table('log_up_vl')->insert(['user_name' => $value->user_name, 'bonus_total' => $value->bonus_total,
                        'old_lavel' => $data_user->code, 'new_lavel' => 'MD','vvip' =>$data_user,'svvip' =>$data_svvip]);

                }
            }

            if ($data_user >= 150 and  $value->qualification_id_fk== 8) {
                $data_svvip =  DB::table('customers')
                    ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                    ->where('customers.introduce_id', '=', $value->user_name)
                    ->where('dataset_qualification.id', '=', 6)
                    ->count();
                if ($data_svvip >= 13 and $value->bonus_total >= 2000000) {

                        $k++;
                        // DB::table('customers')
                        //     ->where('user_name', $value->user_name)
                        //     ->update(['qualification_id' => 'ME']);
                        DB::table('log_up_vl')->insert(['user_name' => $value->user_name,'bonus_total' => $value->bonus_total,
                         'old_lavel' => $value->qualification_id, 'new_lavel' => 'ME','vvip' =>$data_user,'svvip' =>$data_svvip]);

                }
            }



            if ($data_user >= 100 and  $value->qualification_id_fk== 7) {
                $data_svvip =  DB::table('customers')
                    ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                    ->where('customers.introduce_id', '=', $value->user_name)
                    ->where('dataset_qualification.id', '=', 6)
                    ->count();
                if ($data_svvip >= 7 and $value->bonus_total >= 1000000) {

                        $k++;
                        // DB::table('customers')
                        //     ->where('user_name', $value->user_name)
                        //     ->update(['qualification_id' => 'MR']);
                        DB::table('log_up_vl')->insert(['user_name' => $value->user_name, 'bonus_total' => $value->bonus_total,
                         'old_lavel' => $value->qualification_id, 'new_lavel' => 'MR','vvip' =>$data_user,'svvip' =>$data_svvip]);

                }
            }

            if ($data_user >= 60 and  $value->qualification_id_fk == 6) {
                $data_svvip =  DB::table('customers')
                    ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                    ->where('customers.introduce_id', '=', $value->user_name)
                    ->where('dataset_qualification.id', '=', 6)
                    ->count();
                if ($data_svvip >= 3 and $value->bonus_total >= 100000) {

                        $k++;
                        // DB::table('customers')
                        //     ->where('user_name', $value->user_name)
                        //     ->update(['qualification_id' => 'MG']);
                        DB::table('log_up_vl')->insert(['user_name' => $value->user_name, 'bonus_total' => $value->bonus_total,
                         'old_lavel' => $value->qualification_id, 'new_lavel' => 'MG','vvip' =>$data_user,'svvip' =>$data_svvip]);

                }
            }

            if ($data_user >= 40 and  $value->qualification_id_fk == 5 and $value->bonus_total >= 100000) {


                // DB::table('customers')
                //     ->where('user_name', $value->user_name)
                //     ->update(['qualification_id' => 'SVVIP']);

                    $k++;
                DB::table('log_up_vl')->insert(['user_name' => $value->user_name,'bonus_total' => $value->bonus_total,
                'old_lavel' => $value->qualification_id, 'new_lavel' => 'SVVIP','vvip' =>$data_user]);

            }
            $data_user_xvvip =  DB::table('customers')
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('customers.introduce_id', '=', $value->user_name)
            ->where('dataset_qualification.id', '>=', 4)
            ->count();
            if ($data_user_xvvip >= 2 and  $value->qualification_id_fk == 4) {

                $k++;
                // DB::table('customers')
                //     ->where('user_name', $value->user_name)
                //     ->update(['qualification_id' => 'XVVIP']);
                DB::table('log_up_vl')->insert(['user_name' => $value->user_name, 'old_lavel' => $value->qualification_id,
                 'new_lavel' => 'XVVIP', 'bonus_total' => $value->bonus_total,'vvip' =>$data_user]);
            }
        }
        dd('success ' . $i . ' Total' . count($data_user_1) . 'ปรับขึ้นตำแหน่ง' . $k);
    }

    public static function run_edit_upline()
    {
        $c = DB::table('excel_imort_edit_upline')

            ->get();
        $i = 0;

        foreach ($c as $value) {
            DB::table('customers')
                ->where('user_name', $value->user_name)
                ->update(['upline_id' => $value->upline, 'type_upline' => $value->type]);
            $i++;
        }

        dd($i, 'success');
    }
    public static function run_createdate()
    {
        $c = DB::table('excel_imort_create_date')
            ->select('user_name', 'create_date')
            ->get();
        $i = 0;

        foreach ($c as $value) {
            DB::table('customers')
                ->where('user_name', $value->user_name)
                ->update(['created_at' => $value->create_date]);
            $i++;
        }

        dd($i, 'success');
    }

    public static function run_mtdate()
    {
        $c = DB::table('customers')
            ->select('user_name', 'remain_date_num')
            ->where('remain_date_num', '>', 0)
            ->get();
        $i = 0;
        foreach ($c as $value) {
            $data = date('Y-m-d', strtotime("+ $value->remain_date_num days"));

            DB::table('customers')
                ->where('user_name', $value->user_name)
                ->update(['expire_date' => $data]);
            $i++;
        }

        dd($i, 'success');
    }


    public static function ex_import()
    {
        $c = DB::table('excel_imort')
            ->select('user_name', 'pv', 'el')
            ->get();
        $i = 0;

        foreach ($c as $value) {
            DB::table('customers')
                ->where('user_name', $value->user_name)
                ->update(['pv' => $value->pv, 'ewallet' => $value->el]);
            $i++;
        }

        dd($i, 'success');
    }

    public static function import_ewallet()
    {
        $c = DB::table('excel_imort_ewallet')
            ->select('id','user_name', 'el', 'note')
            ->where('status','=','panding')
            // ->where('note','=','Easy โปรโมชั่น รอบ 21ธ.ค.65 - 5 ม.ค.66')
            ->get();
        $i = 0;
        try {
            DB::BeginTransaction();

            // foreach ($c as $value) {
            //     $customers = DB::table('customers')
            //         ->select('id', 'user_name', 'ewallet','ewallet_use')
            //         ->where('user_name', $value->user_name)
            //         ->first();
            //         if(empty($customers)){
            //             dd($value->user_name,'Not Success');
            //         }
            // }

            foreach ($c as $value) {
                $customers = DB::table('customers')
                    ->select('id', 'user_name', 'ewallet','ewallet_use')
                    ->where('user_name', $value->user_name)
                    ->first();
                    // if(empty($customers)){
                    //     dd($value->user_name);
                    // }


                    if(empty($customers->ewallet)){
                        $ewallet = 0;
                    }else{
                        $ewallet = $customers->ewallet;
                    }

                    if(empty($customers->ewallet_use)){
                        $ewallet_use = 0;
                    }else{
                        $ewallet_use = $customers->ewallet_use;
                    }

                $ew_total = $ewallet  + $value->el;
                $ew_use = $ewallet_use + $value->el;
                DB::table('customers')
                    ->where('user_name', $value->user_name)
                    ->update(['ewallet' => $ew_total,'ewallet_use'=>$ew_use]);


                $count_eWallet =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_wallet();


                $dataPrepare = [
                    'transaction_code' => $count_eWallet,
                    'customers_id_fk' => $customers->id,
                    'customer_username' => $value->user_name,
                    'tax_total' => 0,
                    'bonus_full' => $value->el,
                    'amt' => $value->el,
                    'old_balance' => $customers->ewallet,
                    'balance' => $ew_total,
                    'note_orther' => $value->note,
                    'receive_date' => now(),
                    'receive_time' => now(),
                    'type' => 1,
                    'status' => 2,
                ];

                $query =  eWallet::create($dataPrepare);
                DB::table('excel_imort_ewallet')
                    ->where('id', $value->id)
                    ->update(['status' => 'success']);

                $i++;
            }
            // dd('success');
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return 'fail';
        }


        dd($i, 'success');
    }


    public static function import_ewallet_delete()
    {

        $ewallet = DB::table('ewallet')

        ->selectRaw('transaction_code,count(transaction_code) as count_code')
        ->havingRaw('count(count_code) > 1 ')
        ->where('note_orther','=','EASY เงินทองเที่ยวรถบ้าน(21ก.พ.-5มี.ค.66)')
        ->groupby('transaction_code')
        ->get();
 dd($ewallet);
        $i =0;
        // foreach($ewallet as $value){
        //     $i++;

        //     $limit =  DB::table('ewallet') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        //     ->where('transaction_code', '=', $value->transaction_code)
        //     ->first();


        //     $deleted = DB::table('ewallet')
        //         ->where('transaction_code', '=', $value->transaction_code)
        //         ->where('id', '=', $limit->id)->delete();


        // }

        // dd('success',$i);


        // $c = DB::table('excel_imort_ewallet_delete')
        //     ->select('id','user_name', 'el', 'note')
        //     ->where('status','=','success')
        //     ->where('note','=','All Sale ผู้นำบริหาร เดือน ธ.ค.65')
        //     ->get();

        // $i = 0;
        // $arr = array();
        // foreach ($c as $value) {
        //     $arr[]= $value->user_name;
        // }
        // $customers = DB::table('customers')
        //         ->select('id', 'user_name', 'ewallet')
        //         ->wherein('user_name',$arr)
        //         ->get();

        // foreach($customers as $value_c){
        //     $i++;
        //     $ewallet = DB::table('ewallet')
        //     ->select('*')
        //     ->where('customers_id_fk','=',$value_c->id)
        //     ->where('note_orther','=','All Sale ผู้นำบริหาร เดือน ธ.ค.65')
        //     ->orderby('balance','DESC')
        //     ->first();

        //     DB::table('ewallet')->where('id','=', $ewallet->id)->delete();

        // }
        // dd('success',$i);


        // foreach ($c as $value) {
        //     $customers = DB::table('customers')
        //         ->select('id', 'user_name', 'ewallet')
        //         ->where('user_name', $value->user_name)
        //         ->first();

        //     $ew_total = $customers->ewallet - $value->el;

        //     if($ew_total< 0){
        //         DB::table('excel_imort_ewallet_delete')
        //         ->where('id', $value->id)
        //         ->update(['status' => 'fail']);
        //     }else{
        //         DB::table('customers')
        //         ->where('user_name', $value->user_name)
        //         ->update(['ewallet' => $ew_total]);

        //         DB::table('excel_imort_ewallet_delete')
        //         ->where('id', $value->id)
        //         ->update(['status' => 'success']);

        //     }

        //     $i++;
        // }



        dd($i, 'success');
    }
    public static function update_position()
    {
        $c = DB::table('log_up_vl')
            ->select('id','user_name', 'new_lavel','pv_upgrad')
            ->where('status','=','panding')
            ->get();
            $i =0;
            foreach($c as $value){
                 DB::table('customers')
                    ->where('user_name', $value->user_name)
                    ->update(['qualification_id' =>  $value->new_lavel,'pv_upgrad' =>$value->pv_upgrad]);

                    DB::table('log_up_vl')
                    ->where('id', $value->id)
                    ->update(['status' => 'success']);


            $i++;
            }
            dd('success ' . $i . ' Total' . count($c) . 'ปรับขึ้นตำแหน่ง' . $i);

    }

    public static function import_bonus_total()
    {

        // $report_bonus_active1 =  DB::table('excel_imort_bonus_total') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        // ->selectRaw('user_name,count(user_name) as count_user_name')
        // ->havingRaw('count(count_user_name) > 1 ')

        // ->groupby('user_name')
        // ->get();

        // dd($report_bonus_active1);

        $c = DB::table('excel_imort_bonus_total')
            ->select('id', 'user_name', 'bonus_total')
            ->where('bonus_total', '>', 0)
            ->where('status', '=', 'panding')
            ->limit(50000)
            ->get();

        if (count($c) == 0) {
            dd('หมดทุกรายการเเล้ว');
        }

        $i = 0;

        foreach ($c as $value) {
            $customers = DB::table('customers')
                ->select('id', 'user_name', 'bonus_total')
                ->where('user_name', $value->user_name)
                ->first();

            DB::table('customers')
                ->where('user_name', $value->user_name)
                ->update(['bonus_total' => $value->bonus_total]);

            DB::table('excel_imort_bonus_total')
                ->where('id', $value->id)
                ->update(['status' => 'success']);

            $i++;
        }

        dd($i, 'success');
    }

    public static function bonus_register()
    {

        // $count_user =  DB::table('customers') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        // ->selectRaw('introduce_id,user_name,count(introduce_id) as count_introduce_id')

        // // ->wheredate('date_active', '=', $date)

        // ->where('vvip_register_type', '=', 'register')
        // ->where('vvip_status_runbonus', '=', 'panding')
        // ->havingRaw('count(count_introduce_id) > 1 ')
        // ->groupby('introduce_id')
        // ->get();

        // dd($count_user);


            $customer_username = 'A843930';
            $data_user =  DB::table('customers')
                    ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
                    // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                    ->where('user_name', '=', $customer_username)
                    ->first();
            $arr_user = array();
            $report_bonus_register = array();

            try {
                DB::BeginTransaction();



                        $data_user_bonus_4 =  DB::table('customers')
                            ->select(
                                'customers.id',
                                'customers.name',
                                'customers.last_name',
                                'bonus_total',
                                'customers.user_name',
                                'customers.upline_id',
                                'customers.introduce_id',
                                'customers.ewallet_use',
                                'customers.qualification_id',
                                'dataset_qualification.id as qualification_id_fk'
                            )
                            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                            ->where('customers.introduce_id', '=',  $customer_username)
                            ->where('dataset_qualification.id', '=', 4)
                            ->where('customers.vvip_register_type', '=', 'register')
                            ->where('customers.vvip_status_runbonus', '=', 'panding')
                            ->limit(2)
                            ->get();
                           dd($data_user_bonus_4);

                        if (count($data_user_bonus_4) >= 2 and ( $data_user->qualification_id == 'XVVIP' ||  $data_user->qualification_id == 'SVVIP'
                            ||  $data_user->qualification_id == 'MG' ||  $data_user->qualification_id == 'MR' ||  $data_user->qualification_id == 'ME' ||  $data_user->qualification_id == 'MD')) {

                            $f = 0;
                            foreach ($data_user_bonus_4 as $value_bonus_4) {
                                $user_runbonus[] = $value_bonus_4->user_name;
                                $f++;
                                DB::table('customers')
                                ->where('user_name', $value_bonus_4->user_name)
                                ->update(['vvip_status_runbonus' => 'success']);
                                if ($f == 2) {

                                    $code_b4 =    $code_bonus = \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_bonus(4);


                                    $data_user_bonus4 = DB::table('customers')
                                        ->select('id', 'upline_id', 'user_name', 'introduce_id', 'name', 'last_name', 'qualification_id')
                                        ->where('user_name', '=',$data_user->introduce_id)
                                        ->first();
                                    if (
                                        $data_user_bonus4->qualification_id == 'XVVIP' || $data_user_bonus4->qualification_id == 'SVVIP' || $data_user_bonus4->qualification_id == 'MG'
                                        || $data_user_bonus4->qualification_id == 'MR' || $data_user_bonus4->qualification_id == 'ME' || $data_user_bonus4->qualification_id == 'MD'
                                    ) {
                                        $report_bonus_register_b4['user_name'] =  $customer_username;
                                        $introduce_id =  DB::table('customers')
                                        ->select('introduce_id')
                                        ->where('user_name', '=', $customer_username)
                                        ->first();

                                        $report_bonus_register_b4['name'] =  $data_user->name . ' ' . $data_user->last_name;
                                        $report_bonus_register_b4['introduce_id'] = $introduce_id->introduce_id;
                                        $report_bonus_register_b4['regis_user_name'] = $value_bonus_4->user_name;
                                        $report_bonus_register_b4['regis_user_introduce_id'] =  $customer_username;
                                        $report_bonus_register_b4['regis_name'] = $value_bonus_4->name . ' ' . $value_bonus_4->last_name;
                                        $report_bonus_register_b4['user_upgrad'] =$customer_username;
                                        $report_bonus_register_b4['user_name_recive_bonus'] = $data_user_bonus4->user_name;
                                        $report_bonus_register_b4['name_recive_bonus'] =  $data_user_bonus4->name . ' ' . $data_user_bonus4->last_name;
                                        $report_bonus_register_b4['old_position'] = '';
                                        $report_bonus_register_b4['new_position'] = '';
                                        $report_bonus_register_b4['code_bonus'] = $code_b4;
                                        $report_bonus_register_b4['type'] = 'register';
                                        $report_bonus_register_b4['user_name_vvip_1'] = $user_runbonus[0];
                                        $report_bonus_register_b4['user_name_vvip_2'] = $user_runbonus[1];
                                        $report_bonus_register_b4['tax_total'] =  2000 * 3 / 100;
                                        $report_bonus_register_b4['bonus_full'] = 2000;
                                        $report_bonus_register_b4['bonus'] =  2000 - (2000 * 3 / 100);
                                        $report_bonus_register_b4['pv_vvip_1'] =  '1200';
                                        $report_bonus_register_b4['pv_vvip_2'] =  '1200';


                                        DB::table('report_bonus_register_xvvip')
                                            ->updateOrInsert(
                                                ['regis_user_name' =>  $value_bonus_4->user_name, 'user_name' =>  $customer_username],
                                                $report_bonus_register_b4
                                            );

                                        $report_bonus_register_xvvip = DB::table('report_bonus_register_xvvip')
                                            ->where('status', '=', 'panding')
                                            ->where('bonus', '>', 0)
                                            ->where('code_bonus', '=', $code_b4)
                                            ->where('regis_user_name', '=', $value_bonus_4->user_name)
                                            ->first();





                                        $wallet_b4 = DB::table('customers')
                                            ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'bonus_total')
                                            ->where('user_name', $data_user_bonus4->user_name)
                                            ->first();

                                        if ($wallet_b4->ewallet == '' || empty($wallet_b4->ewallet)) {
                                            $wallet_b4_user = 0;
                                        } else {

                                            $wallet_b4_user = $wallet_b4->ewallet;
                                        }

                                        if ($wallet_b4->bonus_total == '' || empty($wallet_b4->bonus_total)) {
                                            $bonus_total_b4 = 0 + $report_bonus_register_xvvip->bonus;
                                        } else {

                                            $bonus_total_b4 = $wallet_b4->bonus_total + $report_bonus_register_xvvip->bonus;
                                        }

                                        if ($wallet_b4->ewallet_use == '' || empty($wallet_b4->ewallet_use)) {
                                            $ewallet_use_b4 = 0;
                                        } else {

                                            $ewallet_use_b4 = $wallet_b4->ewallet_use;
                                        }
                                        $eWallet_register_b4 = new eWallet();
                                        $wallet_total_b4 = $wallet_b4_user +  $report_bonus_register_xvvip->bonus;
                                        $ewallet_use_total_b4 =  $ewallet_use_b4 + $report_bonus_register_xvvip->bonus;

                                        $eWallet_register_b4->transaction_code =  $report_bonus_register_xvvip->code_bonus;
                                        $eWallet_register_b4->customers_id_fk = $data_user_bonus4->id;
                                        $eWallet_register_b4->customer_username = $report_bonus_register_xvvip->user_name_recive_bonus;
                                        // $eWallet_register_b4->customers_id_receive = $user->id;
                                        // $eWallet_register_b4->customers_name_receive = $user->user_name;
                                        $eWallet_register_b4->tax_total = $report_bonus_register_xvvip->tax_total;
                                        $eWallet_register_b4->bonus_full = $report_bonus_register_xvvip->bonus_full;
                                        $eWallet_register_b4->amt = $report_bonus_register_xvvip->bonus;
                                        $eWallet_register_b4->old_balance = $wallet_b4_user;
                                        $eWallet_register_b4->balance = $wallet_total_b4;
                                        $eWallet_register_b4->type = 11;
                                        $eWallet_register_b4->note_orther = 'โบนัสสร้างทีม รหัส ' . $user_runbonus[0] . ' และรหัส ' . $user_runbonus[1] . ' สมัครสมาชิก VVIP';
                                        $eWallet_register_b4->receive_date = now();
                                        $eWallet_register_b4->receive_time = now();
                                        $eWallet_register_b4->status = 2;

                                        DB::table('customers')
                                            ->where('user_name', $data_user_bonus4->user_name)
                                            ->update(['ewallet' => $wallet_total_b4, 'ewallet_use' => $ewallet_use_total_b4, 'bonus_total' => $bonus_total_b4]);

                                        DB::table('report_bonus_register_xvvip')
                                            ->where('code_bonus', '=', $report_bonus_register_xvvip->code_bonus)
                                            ->where('regis_user_name', '=', $report_bonus_register_xvvip->regis_user_name)
                                            ->update(['status' => 'success']);

                                        $eWallet_register_b4->save();

                                        DB::table('report_bonus_register_xvvip')
                                            ->where('code_bonus', '=', $report_bonus_register_xvvip->code_bonus)
                                            ->where('regis_user_name', '=', $report_bonus_register_xvvip->regis_user_name)
                                            ->update(['status' => 'success']);

                                        $eWallet_register_b4->save();
                                    }
                                }
                            }

                        }else{
                            dd('ตำแหน่งลูกค้าผิด');
                        }


                    //คำนวนตำแหน่งไหม่


                    DB::commit();
                   dd('success');

            } catch (Exception $e) {
                DB::rollback();
                // dd( $validator->errors());
                dd('fail');
            }

    }
}
