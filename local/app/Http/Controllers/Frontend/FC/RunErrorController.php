<?php

namespace App\Http\Controllers\Frontend\FC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

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

        // $data = RunErrorController::ex_import();
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

            // dd($i,'success');


        // return view('frontend/jp-clarify');
    }


    public static function ex_import(){
            $c = DB::table('excel_imort')
            ->select('user_name','pv','el')
            ->get();
            $i= 0;

    foreach ($c as $value) {
        DB::table('customers')
      ->where('user_name', $value->user_name)
       ->update(['pv' =>$value->pv,'ewallet'=>$value->el]);
       $i++;
    }

    dd($i,'success');
    }

}
