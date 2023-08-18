<?php

namespace App\Http\Controllers\Frontend\FC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\eWallet;

class RunPerDayPerMonth_allsale_checkController extends Controller
{
    public $arr = array();

    public function bonus_allsale_permounth_check($user_check)
    {

        $request['s_date'] = date('2023-07-17');
        $request['e_date'] = date('2023-07-31');
        $db_orders = DB::table('db_orders')
            ->selectRaw('customers_user_name,sum(pv_total) as pv_type_1234')
            ->wherein('order_status_id_fk', [4, 5, 6, 7])

            ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' != ''  THEN  date(created_at) >= '{$request['s_date']}' and date(created_at) <= '{$request['e_date']}'else 1 END"))
            ->groupby('customers_user_name')
            // ->limit(10)
            ->get();

            // dd($db_orders);

        foreach ($db_orders as $value) {

            $customer = DB::table('customers')->select('id', 'pv', 'user_name', 'introduce_id','qualification_id')
                ->where('status_customer', '!=', 'cancle')
                ->where('user_name', '=', $value->customers_user_name)
                ->first();
                $data = \App\Http\Controllers\Frontend\FC\RunPerDayPerMonth_allsale_checkController::runbonus($value->customers_user_name, $value->pv_type_1234, $i = 0,$value->customers_user_name,$user_check,$customer->qualification_id);
        }
        dd($this->arr);
        // DB::commit();

    }

    public function runbonus($customers_user_name, $pv, $i,$userbuy,$user_check,$qualification_id)
    {


        $user = DB::table('customers') //อัพ Pv ของตัวเอง
            ->select('id', 'pv', 'user_name', 'introduce_id', 'status_customer')
            ->where('user_name', '=', $customers_user_name)
            // ->where('status_runbonus_allsale_1', '=', 'pending')
            ->first();

        // if (empty($user)) {
        //     DB::table('customers')
        //     ->where('user_name', '=', $customers_user_name)
        //     ->update(['status_runbonus_allsale_1' => 'success']);
        //     $resule = ['status' => 'success', 'message' => 'ไม่มี User นี้ในระบบ'];
        //     return  $resule;

        // }

        try {
            DB::BeginTransaction();

            if ($user) {
                if ($user->status_customer != 'cancel') {

                     $lv = $i+1;
                    if($user->user_name == $user_check){
                        $this->arr['order'][] = 'ได้จาก '.$userbuy.' | ตำแหน่ง '.$qualification_id.' | '.$pv .' PV' .' ชั้นที่ '. $lv ;
                    }

                }
                //DB::rollback();
                if ($user->introduce_id and $user->introduce_id != 'AA') {

                    $i++;
                    // $this->arr[$i] = $user->introduce_id;
                    $data = \App\Http\Controllers\Frontend\FC\RunPerDayPerMonth_allsale_checkController::runbonus($user->introduce_id, $pv, $i,$userbuy,$user_check,$qualification_id);
                    if ($data['status'] == 'success') {
                        DB::commit();
                        $resule = ['status' => 'success', 'message' => 'สิ้นสุด'];
                        return $resule;
                    } else {

                        \App\Http\Controllers\Frontend\FC\RunPerDayPerMonth_allsale_checkController::runbonus($user->introduce_id, $pv, $i,$userbuy,$user_check,$qualification_id);
                    }
                } else {
                    DB::commit();
                    $resule = ['status' => 'success', 'message' => 'สิ้นสุด'];
                    return $resule;
                }
            } else {
                DB::commit();
                $resule = ['status' => 'success', 'message' => 'สิ้นสุด'];
                return $resule;
            }
        } catch (Exception $e) {
            //DB::rollback();
            dd('ffff');
            $resule = [
                'status' => 'fail',
                'message' => 'Update PvPayment Fail',
            ];
            return $resule;
        }
    }



    public function bonus_allsale_permounth_check_2($user_check)//คนที่มียอดสั่งซื้อทั้งหมดทั้งสายชั้นองกร
    {
        $introduce_id = self::tree($user_check)->flatten();
        dd($introduce_id);

    }

    public function tree($user_check)
    {
      $request['s_date'] = date('2023-07-17');
      $request['e_date'] = date('2023-07-31');
      $y = '2023';
      $m = '07';
      $route = 1;
    //   $data_all = DB::table('report_bonus_all_sale_permouth')
    //       ->where('year', '=', $y)
    //       ->where('month', '=', $m)
    //       ->where('route', '=', $route)

    //       ->where('user_name', '=',$user_check)
    //       ->orderby('customer_id_fk', 'DESC')
    //       // ->limit(2)
    //       ->get();

    $data_all = DB::table('customers')
    ->select('id','user_name','introduce_id','qualification_id','expire_date','name','last_name','id_card','pv_allsale_permouth as pv_full')
        //   ->wherein('customers.qualification_id',['XVVIP','SVVIP','MG','MR','ME','MD'])
    // ->where('customers.pv_allsale_permouth', '>',0)
    ->where('customers.user_name', '=',$user_check)
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


            $upline_id->num = $num;


            $upline_id->head = $upline_id->id;


            if($upline_id->pv_full >= 100000 ){
                $rat = 75;
            }elseif($upline_id->pv_full  >= 30000 and $upline_id->pv_full < 100000){
                $rat = 55;
            }elseif($upline_id->pv_full  >= 10000 and $upline_id->pv_full < 30000){
                $rat = 40;
            }elseif($upline_id->pv_full  >= 5000 and $upline_id->pv_full < 10000){
                $rat = 30;
            }elseif($upline_id->pv_full  >= 2400 and $upline_id->pv_full < 5000){
                $rat = 20;
            }elseif($upline_id->pv_full  >= 1200 and $upline_id->pv_full < 2400){
                $rat = 15;
            }elseif($upline_id->pv_full  >= 800 and $upline_id->pv_full < 1200){
                $rat = 10;
            }elseif($upline_id->pv_full  >= 400 and $upline_id->pv_full < 800){
                $rat = 5;
            }else{
                $rat = 0;
            }


            $upline_id->full_bonus   = $upline_id->pv_full * $rat/100;
            $upline_id->children = self::user_upline($upline_id->user_name);
            self::formatTree($upline_id->children, $num, $upline_id->id);

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
                  $this->arr['full_bonus'][$upline_id->user_name] = $upline_id->pv_allsale_permouth * $rat/100;
              }else{
                $this->arr['full_bonus'][$upline_id->user_name]  = 0;
              }

              $upline_id->num = $num;

              $upline_id->rat = $rat;
              $upline_id->full_bonus = $this->arr['full_bonus'][$upline_id->user_name];
              $upline_id->children = self::user_upline($upline_id->user_name);
              self::formatTree($upline_id->children, $num, $head);


            }else{

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
                $this->arr['full_bonus'][$upline_id->user_name] = $upline_id->pv_allsale_permouth * $rat/100;
            }else{
              $this->arr['full_bonus'][$upline_id->user_name]  = 0;
            }
              $upline_id->num = $num;
              $upline_id->rat = $rat;
              $upline_id->full_bonus = $this->arr['full_bonus'][$upline_id->user_name];
              $upline_id->children = self::user_upline($upline_id->user_name);
              self::formatTree($upline_id->children, $num, $head);

            }

          }
        } else {

          if ($num == 1) {
          } else {
            $upline_id->head = $head;
            if($upline_id->qualification_id == 'XVVIP' ||  $upline_id->qualification_id == 'SVVIP' || $upline_id->qualification_id == 'MG' || $upline_id->qualification_id == 'MR'
            ||  $upline_id->qualification_id =='ME' || $upline_id->qualification_id == 'MD'){




           }else{
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
                $this->arr['full_bonus'][$upline_id->user_name] = $upline_id->pv_allsale_permouth * $rat/100;
            }else{
              $this->arr['full_bonus'][$upline_id->user_name]  = 0;
            }
              $upline_id->num = $num;
              $upline_id->rat = $rat;
              $upline_id->full_bonus = $this->arr['full_bonus'][$upline_id->user_name];
              $upline_id->children = self::user_upline($upline_id->user_name);
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


}
