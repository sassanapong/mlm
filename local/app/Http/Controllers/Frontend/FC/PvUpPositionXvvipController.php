<?php

namespace App\Http\Controllers\Frontend\FC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class PvUpPositionXvvipController extends Controller
{


    public static function get_pv_upgrade($user_name)
    {

        $pv_xvvip =  DB::table('report_bonus_register_xvvip') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        ->selectRaw('report_bonus_register_xvvip.introduce_id,sum(pv_vvip_1) as pv_1,sum(pv_vvip_2) as pv_2')
        ->where('introduce_id','=',$user_name)
        ->groupby('introduce_id')
        ->first();
        if($pv_xvvip){
            $pv = $pv_xvvip->pv_1 + $pv_xvvip->pv_2;
            return $pv;
        }else{
            return 0;
        }



    }


}
