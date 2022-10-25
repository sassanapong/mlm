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

        for($i=1;$i<10;$i++){

        }

    }



}
