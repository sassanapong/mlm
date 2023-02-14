<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class ShippingController extends Controller
{

    public static function fc_shipping($pv)
    {
        if($pv < 400){
            if($pv>=200){
                return 60;
            }else{
                return 70;
            }
        }else{
            return 0;
        }


    }



}
