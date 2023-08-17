<?php

namespace App\Http\Controllers\Frontend\FC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\eWallet;

class RunPerDayPerMonth_orsale_03Controller extends Controller
{
    public $arr = array();

    public function bonus_allsale_permounth_03()
    {

        // $pv_allsale_permouth =  DB::table('customers')
        //     ->where('pv_allsale_permouth', '>', 0)
        //     ->update(['pv_allsale_permouth' => '0']);

        // $status_runbonus_allsale_1 =  DB::table('customers')
        //     // ->where('user_name', '=',$value->customers_user_name)
        //     ->where('status_runbonus_allsale_1', '=', 'success')
        //     ->update(['status_runbonus_allsale_1' => 'pending']);

        // dd($pv_allsale_permouth, $status_runbonus_allsale_1);

        $introduce_id = self::tree()->flatten();

        dd($introduce_id,$this->arr);

        foreach ($report_bonus_all_sale_permouth as $value) {

            // $customer = DB::table('customers')->select('id', 'pv', 'user_name', 'introduce_id', 'status_runbonus_allsale_1','qualification_id')
            //     ->where('status_customer', '!=', 'cancle')
            //     ->where('user_name', '=', $value->user_name)
            //     ->first();


            $data = \App\Http\Controllers\Frontend\FC\RunPerDayPerMonth_orsale_03Controller::runbonus($value->user_name,$value->introduce_id, $value->pv_full, $i = 0);
            // dd($this->arr,$data);
             dd($data);
            if ($data['status'] == 'success') {

                DB::table('report_bonus_all_sale_permouth')
                    ->where('id', '=', $value->id)
                    ->update(['status_runbonus_allsale_2' => 'success']);
                // $resule = ['status' => 'success', 'message' => 'ไม่มี User นี้ในระบบ'];
                // return  $resule;

            } else {
                dd($data, 'เกิดข้อผิดพลาด');
            }
        }


        $report_bonus_all_sale_permouth_update = DB::table('report_bonus_all_sale_permouth')
        ->where('year', '=', $y)
        ->where('month', '=', $m)
        ->where('route', '=', $route)
        ->where('status_runbonus_allsale_2', '=','success')
        ->orderby('customer_id_fk', 'DESC')
        // ->limit(2)
        ->get();

        foreach ($report_bonus_all_sale_permouth_update as $value) {
            $user = DB::table('customers') //อัพ Pv ของตัวเอง
            ->select('id', 'pv_allsale_permouth', 'pv_allsale_permouth_group')
            ->where('user_name', '=',$value->user_name)
            // ->where('status_runbonus_allsale_1', '=', 'pending')
            ->first();

            if($user->pv_allsale_permouth_group == 0 || empty($user->pv_allsale_permouth_group)){
                $pv_allsale_permouth_group = $value->pv_total_group;
            }else{
                $pv_allsale_permouth_group = $user->pv_allsale_permouth_group;

            }

            $bonus_full_01 = $pv_allsale_permouth_group * ($value->rat/100);
           $update = DB::table('report_bonus_all_sale_permouth')
            ->where('id', '=', $value->id)
            ->update(['pv_total_group' =>  $pv_allsale_permouth_group,'bonus_full_01'=>$bonus_full_01]);
        }
        dd($update);
        // DB::commit();
        // $user = DB::table('customers') //อัพ Pv ของตัวเอง
        //     ->select('id', 'pv', 'user_name', 'introduce_id','status_runbonus_allsale_1')
        //     ->where('status_customer', '!=', 'cancel')
        //     ->where('status_runbonus_allsale_1', '=', 'success')
        //     ->get();
        // dd($db_orders, $user, 'success');
    }




  public function tree()
  {
    $request['s_date'] = date('2023-07-17');
    $request['e_date'] = date('2023-07-31');
    $y = '2023';
    $m = '07';
    $route = 1;
    $data_all = DB::table('report_bonus_all_sale_permouth')
        ->where('year', '=', $y)
        ->where('month', '=', $m)
        ->where('route', '=', $route)

        // ->where('user_name', '=','A801802')
        ->orderby('customer_id_fk', 'DESC')
        ->limit(10)
        ->get();

    $this->formatTree($data_all);
    return $data_all;
  }



  public function formatTree($data_all, $num = 0, $head = 0)
  {

    $num += 1;
    foreach ($data_all as $key => $upline_id) {


      $data = self::user_upline($upline_id->user_name);


      if ($data->isNotEmpty()) {




        if ($num == 1) {

          $upline_id->children = self::user_upline($upline_id->user_name);
          $upline_id->num = $num;

          self::formatTree($upline_id->children, $num, $upline_id->id);
          $upline_id->head = $upline_id->id;
          $upline_id->full_bonus   = $upline_id->pv_full * $upline_id->rat/100;

        } else {
            $upline_id->head = $head;

          if($upline_id->qualification_id == 'XVVIP' ||  $upline_id->qualification_id == 'SVVIP' || $upline_id->qualification_id == 'MG' || $upline_id->qualification_id == 'MR'
           ||  $upline_id->qualification_id =='ME' || $upline_id->qualification_id == 'MD'){

            if($upline_id->pv_allsale_permouth >= 100000 ){
                $rat = 75;
            }elseif($upline_id->pv_allsale_permouth  >= 30000 and $upline_id->pv_allsale_permouth < 100000){
                $rat = 55;
            }elseif($upline_id->pv_allsale_permouth  >= 10000 and $upline_id->pv_allsale_permouth < 30000){
                $rat = 40;
            }elseif($upline_id->pv_allsale_permouth  >= 5000 and $upline_id->pv_allsale_permouth < 10000){
                $rat = 30;
            }elseif($upline_id->pv_allsale_permouth  >= 2400 and $upline_id->pv_allsale_permouth < 5000){
                $rat = 20;
            }elseif($upline_id->pv_allsale_permouth  >= 1200 and $upline_id->pv_allsale_permouth < 2400){
                $rat = 15;
            }elseif($upline_id->pv_allsale_permouth  >= 800 and $upline_id->pv_allsale_permouth < 1200){
                $rat = 10;
            }elseif($upline_id->pv_allsale_permouth  >= 400 and $upline_id->pv_allsale_permouth < 800){
                $rat = 5;
            }else{
                $rat = 0;
            }
            if( $rat > 0){
                $this->arr['full_bonus'][$head][$upline_id->user_name] = $upline_id->pv_allsale_permouth * $rat/100;
            }



          }else{
            $upline_id->children = self::user_upline($upline_id->user_name);
            $upline_id->num = $num;

            self::formatTree($upline_id->children, $num, $head);
          }

        }
      } else {

        if ($num == 1) {
        } else {
          $upline_id->head = $head;
          if($upline_id->qualification_id == 'XVVIP' ||  $upline_id->qualification_id == 'SVVIP' || $upline_id->qualification_id == 'MG' || $upline_id->qualification_id == 'MR'
          ||  $upline_id->qualification_id =='ME' || $upline_id->qualification_id == 'MD'){

            if($upline_id->pv_allsale_permouth >= 100000 ){
                $rat = 75;
            }elseif($upline_id->pv_allsale_permouth  >= 30000 and $upline_id->pv_allsale_permouth < 100000){
                $rat = 55;
            }elseif($upline_id->pv_allsale_permouth  >= 10000 and $upline_id->pv_allsale_permouth < 30000){
                $rat = 40;
            }elseif($upline_id->pv_allsale_permouth  >= 5000 and $upline_id->pv_allsale_permouth < 10000){
                $rat = 30;
            }elseif($upline_id->pv_allsale_permouth  >= 2400 and $upline_id->pv_allsale_permouth < 5000){
                $rat = 20;
            }elseif($upline_id->pv_allsale_permouth  >= 1200 and $upline_id->pv_allsale_permouth < 2400){
                $rat = 15;
            }elseif($upline_id->pv_allsale_permouth  >= 800 and $upline_id->pv_allsale_permouth < 1200){
                $rat = 10;
            }elseif($upline_id->pv_allsale_permouth  >= 400 and $upline_id->pv_allsale_permouth < 800){
                $rat = 5;
            }else{
                $rat = 0;
            }
            if( $rat > 0){
                $this->arr['full_bonus'][$head][$upline_id->user_name] = $upline_id->pv_allsale_permouth * $rat/100;
            }


         }else{
            $upline_id->children = self::user_upline($upline_id->user_name);
            $upline_id->num = $num;
           self::formatTree($upline_id->children, $num, $head);
         }
        }
      }
    }

  }


  public static function user_upline($user_name)
  {
      $introduce_id = DB::table('customers')
      ->select('id','user_name','introduce_id','qualification_id','expire_date','name','last_name','id_card','pv_allsale_permouth')
    //   ->wherein('customers.qualification_id',['XVVIP','SVVIP','MG','MR','ME','MD'])
    ->where('customers.pv_allsale_permouth', '>',0)
    ->where('customers.introduce_id', '=',$user_name);

    return $introduce_id->get();
  }

//   public static function user_upline_notin($user_name)
//   {
//       $introduce_id = DB::table('customers')
//       ->select('id','user_name','introduce_id','qualification_id','expire_date','name','last_name','id_card','pv_allsale_permouth')
//       ->wherein('customers.qualification_id',['XVVIP','SVVIP','MG','MR','ME','MD'])
//     ->where('customers.pv_allsale_permouth', '>',0)
//     ->where('customers.introduce_id', '=',$user_name);

//     return $introduce_id->get();
//   }



    public function bonus_allsale_permounth_04()
    {

        // $pv_allsale_permouth =  DB::table('customers')
        //     ->where('pv_allsale_permouth', '>', 0)
        //     ->update(['pv_allsale_permouth' => '0']);

        // $status_runbonus_allsale_1 =  DB::table('customers')
        //     // ->where('user_name', '=',$upline_id->customers_user_name)
        //     ->where('status_runbonus_allsale_1', '=', 'success')
        //     ->update(['status_runbonus_allsale_1' => 'pending']);

        // dd($pv_allsale_permouth, $status_runbonus_allsale_1);

        $request['s_date'] = date('2023-07-17');
        $request['e_date'] = date('2023-07-31');
        $y = '2023';
        $m = '07';
        $route = 1;
        $report_bonus_all_sale_permouth = DB::table('report_bonus_all_sale_permouth')
            ->where('year', '=', $y)
            ->where('month', '=', $m)
            ->where('route', '=', $route)
            ->where('rat', '=', 75)
            ->orderby('customer_id_fk', 'DESC')
            // ->limit(2)
            ->get();


        foreach ($report_bonus_all_sale_permouth as $value) {

            // $customer = DB::table('customers')->select('id', 'pv', 'user_name', 'introduce_id', 'status_runbonus_allsale_1','pv_allsale_permouth_group','qualification_id')
            //     ->where('status_customer', '!=', 'cancle')
            //     ->where('pv_allsale_permouth_group', '>=', '100000')

            //     ->where('introduce_id', '=', $value->user_name)
            //     ->get();




                // DB::table('report_bonus_all_sale_permouth')
                //     ->where('user_name', '=', $value->user_name)
                //     ->update(['status_runbonus_allsale_2' => 'success']);
                // $resule = ['status' => 'success', 'message' => 'ไม่มี User นี้ในระบบ'];
                // return  $resule;


        }

    }
}
