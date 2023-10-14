<?php

namespace App\Http\Controllers\Frontend\FC;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\eWallet;

class RunPerDayPerMonth_orsale_01Controller extends Controller
{
    public $arr = array();

    public function bonus_allsale_permounth_01()
    {

        dd('closs');
        $request['s_date'] = date('2023-09-01');
        $request['e_date'] = date('2023-09-31');
        $s_date = date('2023-09-01');
        $e_date = date('2023-09-31');

        // // check
        // $db_orders =  DB::table('db_orders') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        // ->selectRaw('db_orders.customers_user_name,code_order,count(code_order) as count_code')
        // ->leftjoin('customers', 'db_orders.customers_user_name', '=', 'customers.user_name')
        // ->wheredate('customers.expire_date','>=',$e_date)
        // ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(db_orders.created_at) = '{$s_date}' else 1 END"))
        // ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(db_orders.created_at) >= '{$s_date}' and date(db_orders.created_at) <= '{$e_date}'else 1 END"))
        // ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(db_orders.created_at) = '{$e_date}' else 1 END"))
        // ->havingRaw('count(count_code) > 1 ')
        // ->groupby('db_orders.code_order')
        // ->get();
        //  dd($db_orders);

        // $pv_allsale_permouth =  DB::table('customers')
        //     ->where('pv_allsale_permouth', '>', 0)
        //     // ->limit(100)
        //     ->count();
        //     dd($pv_allsale_permouth);
        //     // ->update(['pv_allsale_permouth' => '0']);



//         $status_runbonus_allsale_1 =  DB::table('customers')
//             // ->where('user_name', '=',$value->customers_user_name)
//             ->where('status_runbonus_allsale_1', '=', 'success')
//             ->limit(100) 
//             ->get();
//             dd($status_runbonus_allsale_1);
//         //     ->update(['status_runbonus_allsale_1' => 'pending']);



//         // dd($pv_allsale_permouth, $status_runbonus_allsale_1);

// dd('sss');





        $db_orders = DB::table('db_orders')
            ->selectRaw('customers_user_name,sum(pv_total) as pv_type_1234')
            ->wherein('order_status_id_fk', [4, 5, 6, 7])
             ->where('customers_user_name','!=','A530461')
            ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' != ''  THEN  date(created_at) >= '{$request['s_date']}' and date(created_at) <= '{$request['e_date']}'else 1 END"))
            ->groupby('customers_user_name')
            // ->limit(10)
            ->get();
 
            //  dd($db_orders);

        foreach ($db_orders as $value) {

            $customer = DB::table('customers')->select('id', 'pv', 'user_name', 'introduce_id', 'status_runbonus_allsale_1')
                ->where('status_customer', '!=', 'cancle')
                ->where('user_name', '=', $value->customers_user_name)
                ->first();

            if ($customer->status_runbonus_allsale_1 == 'pending') {
                $data = \App\Http\Controllers\Frontend\FC\RunPerDayPerMonth_orsale_01Controller::runbonus($value->customers_user_name, $value->pv_type_1234, $i = 0,$value->customers_user_name);
                // dd($this->arr,$data);
                // dd($data);
                if ($data['status'] == 'success') {

                    DB::table('customers')
                        ->where('user_name', '=', $value->customers_user_name)
                        ->update(['status_runbonus_allsale_1' => 'success']);
                    // $resule = ['status' => 'success', 'message' => 'ไม่มี User นี้ในระบบ'];
                    // return  $resule;

                } else {
                    dd($data, 'เกิดข้อผิดพลาด');
                }
            }
        }
        // dd($this->arr);
        // DB::commit();
        $user = DB::table('customers') //อัพ Pv ของตัวเอง
            ->select('id', 'pv', 'user_name', 'introduce_id','status_runbonus_allsale_1')
            ->where('status_customer', '!=', 'cancel')
            ->where('status_runbonus_allsale_1', '=', 'success')
            ->get();
        dd($db_orders, $user, 'success');
    } 

    public function runbonus($customers_user_name, $pv, $i,$userbuy)
    {


        $user = DB::table('customers') //อัพ Pv ของตัวเอง
            ->select('id', 'pv', 'user_name', 'introduce_id', 'status_customer', 'pv_allsale_permouth')
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
                // if ($user->status_customer != 'cancel') {
                    if ($user->pv_allsale_permouth) {
                        $pv_allsale_permouth = $user->pv_allsale_permouth + $pv;
                    } else {
                        $pv_allsale_permouth = 0 + $pv;
                    }

                    // if($user->user_name == '0857072'){
                    //     $this->arr['order'][] = $userbuy.' | '.$pv;
                    // }

                    DB::table('customers')
                        ->where('user_name', '=', $user->user_name)
                        ->update(['pv_allsale_permouth' => $pv_allsale_permouth]);
                // }
                //DB::rollback();
                if ($user->introduce_id and $user->introduce_id != 'AA') {

                    $i++;
                    // $this->arr[$i] = $user->introduce_id;
                    $data = \App\Http\Controllers\Frontend\FC\RunPerDayPerMonth_orsale_01Controller::runbonus($user->introduce_id, $pv, $i,$userbuy);
                    if ($data['status'] == 'success') {
                        DB::commit();
                        $resule = ['status' => 'success', 'message' => 'สิ้นสุด'];
                        return $resule;
                    } else {

                        \App\Http\Controllers\Frontend\FC\RunPerDayPerMonth_orsale_01Controller::runbonus($user->introduce_id, $pv, $i,$userbuy);
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

            $resule = [
                'status' => 'fail',
                'message' => 'Update PvPayment Fail',
            ];
            return $resule;
        }
    }

    public function bonus_allsale_permounth_02()
    {
         dd('succss');
        $request['s_date'] = date('2023-09-01');
        $request['e_date'] = date('2023-09-31');
        $y = '2023';
        $m = '09';
        $route = 1;
        $note = 'All Sale หุ้นส่วนแห่งความสำเร็จ ก.ค.66';

        $data_all = DB::table('customers')
                ->select('id','user_name','introduce_id','qualification_id','expire_date','name','last_name','id_card','pv_allsale_permouth')
                ->wherein('customers.qualification_id',['XVVIP','SVVIP','MG','MR','ME','MD'])
                 ->wheredate('customers.expire_date','>=',$request['e_date'])
                ->where('pv_allsale_permouth', '>', 0)
                ->where('status_customer', '!=', 'cancel')
                // ->limit(2)
                ->get();

                //  dd($data_all);
 

           foreach($data_all as $value) {

            if($value->pv_allsale_permouth >= 100000 ){
                $rat = 75;
            }elseif($value->pv_allsale_permouth  >= 30000 and $value->pv_allsale_permouth < 100000){
                $rat = 55;
            }elseif($value->pv_allsale_permouth  >= 10000 and $value->pv_allsale_permouth < 30000){
                $rat = 40;
            }elseif($value->pv_allsale_permouth  >= 5000 and $value->pv_allsale_permouth < 10000){
                $rat = 30;
            }elseif($value->pv_allsale_permouth  >= 2400 and $value->pv_allsale_permouth < 5000){
                $rat = 20;
            }elseif($value->pv_allsale_permouth  >= 1200 and $value->pv_allsale_permouth < 2400){
                $rat = 15;
            }elseif($value->pv_allsale_permouth  >= 800 and $value->pv_allsale_permouth < 1200){
                $rat = 10;
            }elseif($value->pv_allsale_permouth  >= 400 and $value->pv_allsale_permouth < 800){
                $rat = 5;
            }else{
                $rat = 0;
            }

                                $dataPrepare = [
                                    'user_name' => $value->user_name,
                                    'name' =>  $value->name.' '.$value->last_name,
                                    'customer_id_fk' =>  $value->id,
                                    'id_card' =>  $value->id_card,
                                    'introduce_id' =>  $value->introduce_id,
                                    'qualification' => $value->qualification_id,
                                    'active_date' => $value->expire_date,
                                    'pv_full' => $value->pv_allsale_permouth,
                                    'year' => $y,
                                    'rat' => $rat,
                                    'month' => $m,
                                    'route' => $route,
                                    'note' => $note,

                                ];

                               $report_bonus_all_sale_permouth =  DB::table('report_bonus_all_sale_permouth')
                                ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y,'month'=>$m,'route' => $route],$dataPrepare);

                                //   DB::table('customers')
                                //     ->where('user_name', '=', $value->user_name)
                                //     ->update(['status_runbonus_allsale_1' => 'success']);
                            }

                dd($report_bonus_all_sale_permouth,'success');

    }
}
