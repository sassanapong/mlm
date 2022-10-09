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

    public function index()
    {
        // dd(1);

        $data = DB::table('customers')
        ->orderby('id')
        ->where('password',null)
        // ->where('id','>=',720000)
        // ->where('id','<=',800000)

        // ->offset(0)->limit(50000)
        ->get();

        dd($data);
        $i = 0;
        foreach($data as $value){
            $i++;
             $pass = md5($value->pass_old);
            $affected = DB::table('customers')
              ->where('id', $value->id)
              ->update(['password' => $pass,'regis_doc1_status'=>1,'business_location_id'=>1]);
        }

        dd($i,'success');


        // return view('frontend/jp-clarify');
    }

}
