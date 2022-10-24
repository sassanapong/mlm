<?php

namespace App\Http\Controllers\Frontend\FC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class AllFunctionController extends Controller
{

    public static function get_upline($user_name)
    {
        // dd(1);

        $data = DB::table('customers')
        ->where('user_name',$user_name)
        ->first();
        //dd($data);
        return  $data;
    }

    public static function get_bonus_position($user_name)
    {
        // dd(1);

        $data = DB::table('customers')
        ->where('user_name',$user_name)
        ->first();
        //dd($data);
        return  $data;
    }


}
