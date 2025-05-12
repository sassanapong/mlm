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
    protected $s_date;
    protected $e_date;
    protected $y;
    protected $m;
    protected $route;
    protected $note;


    public function __construct()
    {
        $this->s_date = date('2025-04-16');
        $this->e_date = date('2025-04-30');
        $this->y = '2025';
        $this->m = '04';
        $this->route = 2;
        $this->note = 'All Sale จากยอดขายทั่วโลกประจำเดือน เมษายน 2568 รอบที่ 2';
    }

    public function order_reset_pv()
    {
        $request['s_date'] = $this->s_date;
        $request['e_date'] = $this->e_date;

        // // สินค้า(A|B)
        // $db_order_products_list = DB::table('db_order_products_list')
        //     ->where('product_name', 'like', '%B)')
        //     ->where('pv', 0)
        //     ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' != ''  THEN  date(created_at) >= '{$request['s_date']}' and date(created_at) <= '{$request['e_date']}'else 1 END"))
        //     ->get();
        // // dd($db_order_products_list);

        // $db_order_products_list = DB::table('db_order_products_list')
        //     ->where('product_name', 'like', '%B)')
        //     ->where('pv', 0)
        //     ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' != ''  THEN  date(created_at) >= '{$request['s_date']}' and date(created_at) <= '{$request['e_date']}'else 1 END"))
        //     ->update(['pv' => 100]);


        // $db_order_products_list = DB::table('db_order_products_list')
        //     ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' != ''  THEN  date(created_at) >= '{$request['s_date']}' and date(created_at) <= '{$request['e_date']}'else 1 END"))
        //     ->where('product_name', 'like', '%B)')
        //     // ->orwhere('product_name', 'like', '%A)')
        //     ->get();

        // // dd($db_order_products_list);
        // $i = 0;
        // foreach ($db_order_products_list as $value) {
        //     $i++;
        //     $total_pv = $value->amt * $value->pv;

        //     $db_order_products_list_update = DB::table('db_order_products_list')
        //         ->where('id',  $value->id)
        //         ->update(['total_pv' => $total_pv]);
        // }

        // dd($i);


        //dd($db_order_products_list);

        $db_order_products_list = DB::table('db_order_products_list')
            ->selectRaw('code_order,sum(total_pv) as total_pv')
            ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' != ''  THEN  date(created_at) >= '{$request['s_date']}' and date(created_at) <= '{$request['e_date']}'else 1 END"))
            ->groupby('code_order')
            // ->limit(10)
            ->get();
        // dd($db_order_products_list);

        $i = 0;
        foreach ($db_order_products_list as $value) {
            $i++;
            $db_order_products_list_update = DB::table('db_orders')
                ->where('code_order',  $value->code_order)
                ->update(['pv_total' => $value->total_pv]);
        }
        dd($i);
    }


    public function bonus_allsale_permounth_01()
    {

        dd('closs');
        $request['s_date'] = $this->s_date;
        $request['e_date'] = $this->e_date;
        $s_date = $this->s_date;
        $e_date = $this->e_date;


        // $jang_pv = DB::table('jang_pv') // รายชื่อคนที่มีรายการแจงโบนัสข้อ
        //     ->selectRaw('jang_pv.customer_username, code, COUNT(code) as count_code')
        //     ->leftJoin('customers', 'jang_pv.customer_username', '=', 'customers.user_name')
        //     ->whereRaw(("CASE WHEN '{$s_date}' != '' AND '{$e_date}' = '' THEN DATE(jang_pv.created_at) = '{$s_date}' ELSE 1 END"))
        //     ->whereRaw(("CASE WHEN '{$s_date}' != '' AND '{$e_date}' != '' THEN DATE(jang_pv.created_at) >= '{$s_date}' AND DATE(jang_pv.created_at) <= '{$e_date}' ELSE 1 END"))
        //     ->whereRaw(("CASE WHEN '{$s_date}' = '' AND '{$e_date}' != '' THEN DATE(jang_pv.created_at) = '{$e_date}' ELSE 1 END"))
        //     ->havingRaw('count_code > 1') // Use the alias directly
        //     ->groupBy('jang_pv.code')
        //     ->get();

        // $pv_allsale_permouth =  DB::table('customers')
        //     ->where('pv_allsale_permouth', '>', 0)
        //     ->update(['pv_allsale_permouth' => '0']);
        // // dd($pv_allsale_permouth);


        // $status_runbonus_allsale_1 =  DB::table('customers')
        //     ->where('status_runbonus_allsale_1', '=', 'success')
        //     ->update(['status_runbonus_allsale_1' => 'pending']);


        // $update_jang_pv = DB::table('jang_pv') // รายชื่อคนที่มีรายการแจงโบนัสข้อ
        //     ->where('status_runbonus', '=', 'success')
        //     ->wherein('type', [1, 2, 3, 4])

        //     ->update(['status_runbonus' => 'pending']);


        // $delete_report_bonus_all_sale_permouth = DB::table('report_bonus_all_sale_permouth')
        //     ->where('year', $this->y)
        //     ->where('month', $this->m)
        //     ->where('route', $this->route)
        //     ->delete();

        // dd('success step 1');

        $jang_pv = DB::table('jang_pv') // รายชื่อคนที่มีรายการแจงโบนัสข้อ
            ->selectRaw('jang_pv.to_customer_username as customers_user_name,sum(jang_pv.pv) as pv_type_1234')
            ->leftJoin('customers', 'jang_pv.to_customer_username', '=', 'customers.user_name')
            ->where('jang_pv.status_runbonus', '=', 'pending')
            ->wherein('type', [1, 2, 3, 4])
            ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' != ''  THEN  date(jang_pv.created_at) >= '{$request['s_date']}' and date(jang_pv.created_at) <= '{$request['e_date']}'else 1 END"))
            ->groupby('jang_pv.to_customer_username')
            ->orderby('customers.id', 'DESC')
            ->limit(500)
            ->get();


        foreach ($jang_pv as $value) {

            $customer = DB::table('customers')->select('id', 'pv', 'user_name', 'introduce_id', 'status_runbonus_allsale_1')
                ->where('status_customer', '!=', 'cancle')
                ->where('user_name', '=', $value->customers_user_name)
                ->first();

            if ($customer->status_runbonus_allsale_1 == 'pending') {
                $data = \App\Http\Controllers\Frontend\FC\RunPerDayPerMonth_orsale_01Controller::runbonus($value->customers_user_name, $value->pv_type_1234, $i = 0, $value->customers_user_name);
                // dd($this->arr,$data);
                // dd($data);
                if ($data['status'] == 'success') {

                    DB::table('jang_pv')
                        ->where('jang_pv.to_customer_username', '=', $value->customers_user_name)
                        ->wherein('type', [1, 2, 3, 4])
                        ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' != ''  THEN  date(created_at) >= '{$request['s_date']}' and date(created_at) <= '{$request['e_date']}'else 1 END"))
                        ->update(['status_runbonus' => 'success']);
                    // $resule = ['status' => 'success', 'message' => 'ไม่มี User นี้ในระบบ'];
                    // return  $resule;

                } else {
                    dd($data, 'เกิดข้อผิดพลาด');
                }
            }
        }
        // dd($this->arr);   
        // DB::commit();

        $status_runbonus = DB::table('jang_pv') // รายชื่อคนที่มีรายการแจงโบนัสข้อ
            ->selectRaw('jang_pv.to_customer_username as customers_user_name,sum(jang_pv.pv) as pv_type_1234')
            ->leftJoin('customers', 'jang_pv.to_customer_username', '=', 'customers.user_name')
            ->where('jang_pv.status_runbonus', '=', 'pending')
            ->wherein('type', [1, 2, 3, 4])
            ->whereRaw(("case WHEN '{$request['s_date']}' != '' and '{$request['e_date']}' != ''  THEN  date(jang_pv.created_at) >= '{$request['s_date']}' and date(jang_pv.created_at) <= '{$request['e_date']}'else 1 END"))
            ->groupby('jang_pv.to_customer_username')
            ->orderby('customers.id', 'DESC')
            ->get();
        dd($status_runbonus, 'success');
    }

    public function runbonus($customers_user_name, $pv, $i, $userbuy)
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
                    $data = \App\Http\Controllers\Frontend\FC\RunPerDayPerMonth_orsale_01Controller::runbonus($user->introduce_id, $pv, $i, $userbuy);
                    if ($data['status'] == 'success') {
                        DB::commit();
                        $resule = ['status' => 'success', 'message' => 'สิ้นสุด'];
                        return $resule;
                    } else {

                        \App\Http\Controllers\Frontend\FC\RunPerDayPerMonth_orsale_01Controller::runbonus($user->introduce_id, $pv, $i, $userbuy);
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

        dd('closs');
        $request['s_date'] = $this->s_date;
        $request['e_date'] = $this->e_date;
        $y = $this->y;
        $m =  $this->m;
        $route = $this->route;
        $note = $this->note;
        $data_all = DB::table('customers')
            ->select('id', 'user_name', 'introduce_id', 'qualification_id', 'expire_date', 'name', 'last_name', 'id_card', 'pv_allsale_permouth')
            ->whereIn('customers.qualification_id', ['VVIP', 'XVVIP', 'SVVIP', 'MG', 'MR', 'ME', 'MD'])
            ->where('customers.status_customer', '!=', 'cancel')
            ->whereDate('customers.expire_date_bonus', '>=', $request['e_date'])
            ->where('pv_allsale_permouth', '>', 0)
            ->where('status_customer', '!=', 'cancel')
            // ->limit(2)
            ->get();

        // dd($data_all);


        foreach ($data_all as $value) {

            if ($value->pv_allsale_permouth >= 100000) {
                $rat = 22.5;
            } elseif ($value->pv_allsale_permouth  >= 30000 and $value->pv_allsale_permouth < 100000) {
                $rat = 16.5;
            } elseif ($value->pv_allsale_permouth  >= 10000 and $value->pv_allsale_permouth < 30000) {
                $rat = 12;
            } elseif ($value->pv_allsale_permouth  >= 5000 and $value->pv_allsale_permouth < 10000) {
                $rat = 9;
            } elseif ($value->pv_allsale_permouth  >= 2400 and $value->pv_allsale_permouth < 5000) {
                $rat = 6;
            } elseif ($value->pv_allsale_permouth  >= 1200 and $value->pv_allsale_permouth < 2400) {
                $rat = 4.5;
            } elseif ($value->pv_allsale_permouth  >= 800 and $value->pv_allsale_permouth < 1200) {
                $rat = 3;
            } elseif ($value->pv_allsale_permouth  >= 400 and $value->pv_allsale_permouth < 800) {
                $rat = 1.5;
            } else {
                $rat = 0;
            }

            $dataPrepare = [
                'user_name' => $value->user_name,
                'name' =>  $value->name . ' ' . $value->last_name,
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
                ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y, 'month' => $m, 'route' => $route], $dataPrepare);

            //   DB::table('customers')
            //     ->where('user_name', '=', $value->user_name)
            //     ->update(['status_runbonus_allsale_1' => 'success']);
        }

        dd($report_bonus_all_sale_permouth, 'success');
    }
}
