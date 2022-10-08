<?php

namespace App\Http\Controllers\Frontend\FC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class RunErrorController extends Controller
{

    public function __construct()
    {
        $this->middleware('customer');
    }

    public function index()
    {

        $data = DB::table('customers')
        ->orderby('id')
        // ->limit(0,100)
        ->get();

        dd($data);
        $i = 0;
        foreach($data as $value){
            $i++;
             $pass = md5($value->pass_old);
            $affected = DB::table('customers')
              ->where('id', $value->id)
              ->update(['password' => $pass]);
        }

        dd($i,'success');


        // return view('frontend/jp-clarify');
    }

}
