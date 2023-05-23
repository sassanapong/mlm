<?php

namespace App\Http\Controllers\Frontend\FC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\eWallet;

class RunPerDayPerMonthController extends Controller
{

    public static function expire_180(){
        $results = DB::select('SELECT id, user_name, expire_date, name, last_name FROM customers WHERE expire_date < DATE_SUB(NOW(), INTERVAL 180 DAY) AND (name != "" OR last_name != "") ORDER BY expire_date DESC ');
        // dd($results);


        //รันทุกเดือน
        //ชื่อ//นามสกุล//id_card//รหัสเข้าระบบ//ลบที่อยู่ตามบัตรประชาชน//ที่อยู่ขนส่ง//bank//ภาพ
        //เรื่องเซิฟเวอขอใบเสนอราคา

        foreach($results as $value){

                  DB::table('customers')
                      ->where('id', $value->id)
                      ->update(['name' => null,
                      'last_name' => null,
                      'id_card'=>null,
                      'password'=>null,
                      'status_customer'=>'cancel',
                    ]);

                    DB::table('customers_address_card')
                    ->where('customers_id', $value->id)
                    ->delete();

                    DB::table('customers_address_delivery')
                    ->where('customers_id', $value->id)
                    ->delete();

                    DB::table('customers_bank')
                    ->where('customers_id', $value->id)
                    ->delete();

                    DB::table('customers_benefit')
                    ->where('customers_id', $value->id)
                    ->delete();


               }
               return 'success';
    }

}
