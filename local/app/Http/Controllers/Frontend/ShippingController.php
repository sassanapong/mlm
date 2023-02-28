<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use PhpParser\Node\Stmt\Return_;

class ShippingController extends Controller
{

    public static function fc_shipping($pv)
    {

        if($pv == 0 ){
            return 0;
        }

        if($pv < 400){
            if($pv>=200){
                return 60;
            }else{
                return 70;
            }
        }else{

            $sipping = $pv%400;

            if($sipping==0){
                return 0;
            }elseif($sipping>=20 and $sipping <= 40){
                return 0;
            }elseif($sipping>=200){
                return 60;
            }else{
                return 70;
            }

        }


    }


    public static function fc_shipping_zip_code($zip_code)
    {

        $zip_code_db = DB::table('dataset_shipping_vicinity')
        ->where('zip_code',$zip_code)
        ->first();
        if($zip_code_db){
            $data = ['status'=>'success','price'=>50,'ms'=>'พื้นที่ห่างไกล'];
        }else{
            $data = ['status'=>'fail','price'=>0,'ms'=>''];
        }

        return $data;
    }

    public static function fc_shipping_zip_code_js(Request $rs)
    {
        $price_shipping_pv = $rs->price_shipping_pv;
        $price_discount = $rs->price_discount;

        $zip_code_db = DB::table('dataset_shipping_vicinity')
        ->where('zip_code',$rs->zip_code)
        ->first();
        if($zip_code_db){
            $total_shipping = 50 +  $rs->price_shipping_pv;
            $price_total =   $price_discount +$total_shipping ;
            $data = ['status'=>'success','price_location'=>50,'ms'=>'พื้นที่ห่างไกล','total_shipping'=>$total_shipping,'price_total'=> $price_total];
        }else{

            $total_shipping =  $rs->price_shipping_pv;
            $price_total =   $price_discount+$total_shipping;
            $data = ['status'=>'fail','price_location'=>0,'ms'=>'','total_shipping'=>$total_shipping,'price_total'=> $price_total];
        }
        // dd($data);

        return $data;
    }



}
