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

        // $data = RunErrorController::run_createdate();
        // dd($data);

        // $data = RunErrorController::run_mtdate();
        // dd($data);

        //  $data = RunErrorController::run_edit_upline();
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


        $data = \App\Http\Controllers\Frontend\BonusCopyrightController::RunBonus_copyright();
        dd($data);
        // dd($i,'success');




        // return view('frontend/jp-clarify');
    }

    public static function run_edit_upline()
    {
        $c = DB::table('excel_imort_edit_upline')

            ->get();
        $i = 0;

        foreach ($c as $value) {
            DB::table('customers')
                ->where('user_name', $value->user_name)
                ->update(['upline_id' => $value->upline,'type_upline'=> $value->type]);
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
            ->where('remain_date_num','>',0)
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
            ->select('user_name', 'el', 'note')
            ->get();
        $i = 0;

        foreach ($c as $value) {
            $customers = DB::table('customers')
                ->select('id', 'user_name', 'ewallet')
                ->where('user_name', $value->user_name)
                ->first();

            $ew_total = $customers->ewallet + $value->el;
            DB::table('customers')
                ->where('user_name', $value->user_name)
                ->update(['ewallet' => $ew_total]);

            $y = date('Y') + 543;
            $y = substr($y, -2);
            $count_eWallet =  IdGenerator::generate([
                'table' => 'ewallet',
                'field' => 'transaction_code',
                'length' => 15,
                'prefix' => 'EW' . $y . '' . date("m") . '-',
                'reset_on_prefix_change' => true
            ]);

            $dataPrepare = [
                'transaction_code' => $count_eWallet,
                'customers_id_fk' => $customers->id,
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

            $i++;
        }

        dd($i, 'success');
    }
}
