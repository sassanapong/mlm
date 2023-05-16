<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;


class EasyReportReportController extends Controller
{


    public function index()
    {
        // $s_date = date('2022-12-24');
        // $e_date = date('2023-01-06');
        // $user_name = '3266422';
        // $report_bonus_active = DB::table('db_orders')
        // ->selectRaw('customers.id,customers.user_name,customers.name,customers.last_name,customers.expire_date,qualification_id,sum(pv_total) as pv_total')
        // ->leftjoin('customers', 'db_orders.customers_user_name', '=', 'customers.user_name')
        // ->wheredate('customers.expire_date','>',now())
        // // ->leftjoin('log_up_vl', 'log_up_vl.introduce_id', '=', 'customers.user_name')
        // ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(db_orders.created_at) = '{$s_date}' else 1 END"))
        // ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(db_orders.created_at) >= '{$s_date}' and date(db_orders.created_at) <= '{$e_date}'else 1 END"))
        // ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(db_orders.created_at) = '{$e_date}' else 1 END"))
        // ->whereRaw(("case WHEN  '{$user_name}' != ''  THEN  customers.user_name = '{$user_name}' else 1 END"))
        // // ->orwhere('log_up_vl.new_lavel','=','XVVIP')
        // ->orderby('pv_total','DESC')
        // ->groupby('customers.user_name')
        // ->get();

    //    $data =  EasyReportReportController::run_easy();
    //    dd($data);
        return view('backend/Easy_report/index');

    }


    public function easy_report_datable(Request $request)
    {


        $business_location_id = 1;
        //sum(pv_total) as pv_total
        $report_bonus_active = DB::table('report_bonus_easy')

        ->where('year','=',$request->s_date)
        ->where('month','=',$request->e_date)
        // ->where('active_date','!=',null)
        ->where('route','=',$request->route)
        ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  report_bonus_easy.user_name = '{$request->user_name}' else 1 END"));


        $sQuery = Datatables::of($report_bonus_active);
        return $sQuery
        ->addIndexColumn()


        ->addColumn('date_data', function ($row)  {
            $date =$row->year.'-'.$row->month;
                return $date;

        })

            ->addColumn('pv_total', function ($row)  {


                if($row->pv_order){
                    return  number_format($row->pv_order);
                }else{
                    return 0;
                }


            })

            ->addColumn('FastStart', function ($row)    {



                if($row->pv_faststart){
                    return  number_format($row->pv_faststart);
                }else{
                    return 0;
                }


                // return $xvvip_new;
            })

            ->addColumn('pv_xvvip', function ($row)    {
                if($row->pv_xvvip){
                    return  number_format($row->pv_xvvip);
                }else{
                    return 0;
                }


            })

            ->addColumn('pv_active', function ($row)  {

                if($row->pv_active){
                    return  number_format($row->pv_active);
                }else{
                    return 0;
                }

            })

            ->addColumn('note', function ($row)  {


                    return $row->note;


            })



            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }

    public function run_easy(){

        $y = '2023';
        $m = '02';
        $route = '1';
        $s_date = date('2023-02-06');
        $e_date = date('2023-02-20');

        // $pv_total =  DB::table('db_orders') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        // ->selectRaw('db_orders.customers_user_name,sum(db_orders.pv_total) as pv_total,customers.name,customers.last_name,customers.expire_date,customers.qualification_id')
        // ->leftjoin('customers', 'db_orders.customers_user_name', '=', 'customers.user_name')
        // ->wheredate('customers.expire_date','>=',$e_date)
        // ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(db_orders.created_at) = '{$s_date}' else 1 END"))
        // ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(db_orders.created_at) >= '{$s_date}' and date(db_orders.created_at) <= '{$e_date}'else 1 END"))
        // ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(db_orders.created_at) = '{$e_date}' else 1 END"))
        // ->groupby('db_orders.customers_user_name')
        // ->get();
        // foreach($pv_total as $value){
        //     $dataPrepare = [
        //         'user_name' => $value->customers_user_name,
        //         'name' =>  $value->name.' '.$value->last_name,
        //         'pv_order'=>$value->pv_total,
        //         'qualification' => $value->qualification_id,
        //         'active_date' => $value->expire_date,
        //         'year' => $y,
        //         'month' => $m,
        //         'route'=>$route,
        //         'note'=>'รอบที่ 1 วันที่ 6 กุมภาพัน 2023 ถึงวันที่ 20 กุมภาพัน 2023',

        //     ];
        //     DB::table('report_bonus_easy')
        //     ->updateOrInsert(['user_name' => $value->customers_user_name, 'year' => $y,'month'=>$m,'route'=>$route],$dataPrepare);
        // }
        // dd('success1');


        // $pv_faststart =  DB::table('report_bonus_register') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        //         ->selectRaw('report_bonus_register.regis_user_introduce_id,sum(report_bonus_register.pv) as pv_total,customers.name,customers.last_name,customers.expire_date,customers.qualification_id')
        //         ->leftjoin('customers', 'report_bonus_register.regis_user_introduce_id', '=', 'customers.user_name')
        //         ->where('g','=','1')
        //         ->wheredate('customers.expire_date','>=',$e_date)
        //         ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(report_bonus_register.created_at) = '{$s_date}' else 1 END"))
        //         ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(report_bonus_register.created_at) >= '{$s_date}' and date(report_bonus_register.created_at) <= '{$e_date}'else 1 END"))
        //         ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(report_bonus_register.created_at) = '{$e_date}' else 1 END"))
        //         ->groupby('report_bonus_register.regis_user_introduce_id')
        //         ->get();

        //     foreach($pv_faststart as $value){
        //     $dataPrepare = [
        //         'user_name' => $value->regis_user_introduce_id,
        //         'name' =>  $value->name.' '.$value->last_name,
        //         'pv_faststart'=>$value->pv_total,
        //         'qualification' => $value->qualification_id,
        //         'active_date' => $value->expire_date,
        //         'year' => $y,
        //         'month' => $m,
        //         'note'=>'รอบที่ 1 วันที่ 6 กุมภาพัน 2023 ถึงวันที่ 20 กุมภาพัน 2023',
        //         'route'=>$route,

        //     ];
        //     DB::table('report_bonus_easy')
        //     ->updateOrInsert(['user_name' => $value->regis_user_introduce_id, 'year' => $y,'month'=>$m,'route'=>$route],$dataPrepare);
        // }
        // dd('success2');

        // $pv_xvvip =  DB::table('report_bonus_register_xvvip') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        // ->selectRaw('report_bonus_register_xvvip.introduce_id,sum(pv_vvip_1) as pv_1,sum(pv_vvip_2) as pv_2,customers.name,customers.last_name,customers.expire_date,customers.qualification_id')
        // ->leftjoin('customers', 'report_bonus_register_xvvip.introduce_id', '=', 'customers.user_name')
        // // ->where('regis_user_introduce_id','=',$row->user_name)
        // ->wheredate('customers.expire_date','>=',$e_date)
        // ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(report_bonus_register_xvvip.created_at) = '{$s_date}' else 1 END"))
        // ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(report_bonus_register_xvvip.created_at) >= '{$s_date}' and date(report_bonus_register_xvvip.created_at) <= '{$e_date}'else 1 END"))
        // ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(report_bonus_register_xvvip.created_at) = '{$e_date}' else 1 END"))
        // ->groupby('introduce_id')
        // ->get();

        // foreach($pv_xvvip as $value){
        //     $pv = $value->pv_1 + $value->pv_2;
        //         $dataPrepare = [
        //             'user_name' => $value->introduce_id,
        //             'name' =>  $value->name.' '.$value->last_name,
        //             'pv_xvvip'=>$pv,
        //             'qualification' => $value->qualification_id,
        //             'active_date' => $value->expire_date,
        //             'year' => $y,
        //             'month' => $m,
        //             'note'=>'รอบที่ 1 วันที่ 6 กุมภาพัน 2023 ถึงวันที่ 20 กุมภาพัน 2023',
        //             'route'=>$route,

        //         ];
        //         DB::table('report_bonus_easy')
        //         ->updateOrInsert(['user_name' => $value->introduce_id, 'year' => $y,'month'=>$m,'route'=>$route],$dataPrepare);
        //     }
        // dd('success3');

        $pv_active =  DB::table('report_bonus_active') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        ->selectRaw('report_bonus_active.introduce_id,sum(report_bonus_active.pv) as pv_total,customers.name,customers.last_name,customers.expire_date,customers.qualification_id')
        ->leftjoin('customers', 'report_bonus_active.introduce_id', '=', 'customers.user_name')
        ->where('g','=','1')
        ->wheredate('customers.expire_date','>=',$e_date)
        ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(report_bonus_active.created_at) = '{$s_date}' else 1 END"))
        ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(report_bonus_active.created_at) >= '{$s_date}' and date(report_bonus_active.created_at) <= '{$e_date}'else 1 END"))
        ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(report_bonus_active.created_at) = '{$e_date}' else 1 END"))
        ->groupby('introduce_id')
        ->get();

        //dd($pv_active);

        foreach($pv_active as $value){
            if($value->introduce_id){

                $dataPrepare = [
                    'user_name' => $value->introduce_id,
                    'name' =>  $value->name.' '.$value->last_name,
                    'pv_active'=>$value->pv_total,
                    'qualification' => $value->qualification_id,
                    'active_date' => $value->expire_date,
                    'year' => $y,
                    'month' => $m,
                    'note'=>'รอบที่ 1 วันที่ 6 กุมภาพัน 2023 ถึงวันที่ 20 กุมภาพัน 2023',
                    'route'=>$route,

                ];
                DB::table('report_bonus_easy')
                ->updateOrInsert(['user_name' => $value->introduce_id, 'year' => $y,'month'=>$m,'route'=>$route],$dataPrepare);
            }
        }
        dd('success4');

    }
    ////////////////////////////////////////////////// new ///////////////
    public function index_new()
    {


    //    $data =  EasyReportReportController::run_easy_new();
    //    dd($data);
        return view('backend/Easy_report/index_new');

    }


    public function easy_report_datable_new(Request $request)
    {


        $business_location_id = 1;
        //sum(pv_total) as pv_total
        $report_bonus_active = DB::table('report_bonus_easy_new')

        ->where('year','=',$request->s_date)
        ->where('month','=',$request->e_date)
        // ->where('active_date','!=',null)
        ->where('route','=',$request->route)
        ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  report_bonus_easy_new.user_name = '{$request->user_name}' else 1 END"));


        $sQuery = Datatables::of($report_bonus_active);
        return $sQuery
        ->addIndexColumn()


        ->addColumn('date_data', function ($row)  {
            $date =$row->year.'-'.$row->month;
                return $date;

        })

        ->addColumn('active_date', function ($row) {

             return  $date_tv_active= date('d/m/Y',strtotime($row->active_date));
        })

        ->addColumn('bonus_type_7', function ($row) {
            if($row->bonus_type_7){
                return number_format($row->bonus_type_7,2);
            }else{
                return 0;
            }
        })



        ->addColumn('note', function ($row)  {


                    return $row->note;


            })



            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }

    public function run_easy_new(){

        $y = '2023';
        $m = '05';
        $route = '1';
        $s_date = date('2023-05-01');
        $e_date = date('2023-05-15');
        $note = 'รอบที่ 1 วันที่ 01 พฤษภาคม 2023 ถึงวันที่ 15 พฤษภาคม 2023';

        $bonus_type_7 = DB::table('ewallet')//ช่วยเพื่อน
        ->selectRaw('customers.id_card,customers.name,customers.last_name,customers.expire_date,customers.qualification_id,ewallet.customer_username,sum(ewallet.bonus_full) as bonus_type_7')
        ->leftjoin('customers', 'ewallet.customer_username', '=', 'customers.user_name')
        ->where('ewallet.type','=',7)
        ->wheredate('customers.expire_date','>=',$e_date)
        ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' = ''  THEN  date(ewallet.created_at) = '{$s_date}' else 1 END"))
        ->whereRaw(("case WHEN '{$s_date}' != '' and '{$e_date}' != ''  THEN  date(ewallet.created_at) >= '{$s_date}' and date(ewallet.created_at) <= '{$e_date}'else 1 END"))
        ->whereRaw(("case WHEN '{$s_date}' = '' and '{$e_date}' != ''  THEN  date(ewallet.created_at) = '{$e_date}' else 1 END"))
        ->groupby('ewallet.customer_username')
        ->get();

              foreach($bonus_type_7 as $value){

                $lv_1_mb = \App\Http\Controllers\Backend\AllsaleReportControlle::count_upline($value->customer_username,['MB']);
                $lv_1_mo = \App\Http\Controllers\Backend\AllsaleReportControlle::count_upline($value->customer_username,['MO']);
                $lv_1_vip = \App\Http\Controllers\Backend\AllsaleReportControlle::count_upline($value->customer_username,['VIP']);
                $lv_1_vvip = \App\Http\Controllers\Backend\AllsaleReportControlle::count_upline($value->customer_username,['VVIP']);
                $lv_1_xvvip_up =\App\Http\Controllers\Backend\AllsaleReportControlle::count_upline($value->customer_username,['XVVIP','SVVIP','MG','MR','ME','MD']);


            $dataPrepare = [
                'user_name' => $value->customer_username,
                'name' =>  $value->name.' '.$value->last_name,
                'id_card' =>  $value->id_card,
                'qualification' => $value->qualification_id,
                'active_date' => $value->expire_date,
                'bonus_type_7'=> $value->bonus_type_7,
                'lv_1_mb' =>  $lv_1_mb,
                'lv_1_mo' =>  $lv_1_mo,
                'lv_1_vip' =>  $lv_1_vip,
                'lv_1_vvip' =>  $lv_1_vvip,
                'lv_1_xvvip_up' =>  $lv_1_xvvip_up,

                'lv_1_mb_bonus' =>  $lv_1_mb*20,
                'lv_1_mo_bonus' =>  $lv_1_mo*40,
                'lv_1_vip_bonus' =>  $lv_1_vip*60,
                'lv_1_vvip_bonus' =>  $lv_1_vvip*80,
                'lv_1_xvvip_up_bonus' =>  $lv_1_xvvip_up*100,
                'year' => $y,
                'month' => $m,
                'route'=>$route,
                'note'=>$note,

            ];
            DB::table('report_bonus_easy_new')
            ->updateOrInsert(['user_name' => $value->customer_username, 'year' => $y,'month'=>$m,'route'=>$route],$dataPrepare);
        }
        // 1 //
        dd('success 1');


        $report_bonus_easy_new = DB::table('report_bonus_easy_new')
        ->where('lv_2_mb','=',null)
        // ->limit(50)
        ->get();

        $array_lv_1 = array();
        $array_lv_2 = array();

         $i = 0;
        foreach($report_bonus_easy_new as $value){
            $i++;
            $customers = DB::table('customers')
           ->select('user_name')
           ->where('customers.introduce_id','=',$value->user_name)
           ->get();
           foreach($customers as $vl_1){
            $array_lv_1[] = $vl_1->user_name;
           }

           if(@$array_lv_1){
            $customers_lv2_mb = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_1)
            ->where('qualification_id','=','MB')
            ->count();

            $customers_lv2_mo = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_1)
            ->where('qualification_id','=','MO')
            ->count();

            $customers_lv2_vip = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_1)
            ->where('qualification_id','=','VIP')
            ->count();

            $customers_lv2_vvip = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_1)
            ->where('qualification_id','=','VVIP')
            ->count();

            $customers_lv2_xvvipup = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_1)
            ->wherein('customers.qualification_id',['XVVIP','SVVIP','MG','MR','ME','MD'])
            ->count();

            $customers_vl2 = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_1)
            ->get();


            foreach($customers_vl2 as $vl_2){
             $array_lv_2[] = $vl_2->user_name;
            }


            if(@$array_lv_2){

            $customers_lv3_mb = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_2)
            ->where('qualification_id','=','MB')
            ->count();



            $customers_lv3_mo = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_2)
            ->where('qualification_id','=','MO')
            ->count();

            $customers_lv3_vip = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_2)
            ->where('qualification_id','=','VIP')
            ->count();

            $customers_lv3_vvip = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_2)
            ->where('qualification_id','=','VVIP')
            ->count();

            $customers_lv3_xvvipup = DB::table('customers')
            ->select('user_name')
            ->wherein('customers.introduce_id',$array_lv_2)
            ->wherein('customers.qualification_id',['XVVIP','SVVIP','MG','MR','ME','MD'])
            ->count();

            }else{
                $customers_lv3_mb = 0;
                 $customers_lv3_mo = 0;
                 $customers_lv3_vip = 0;
                 $customers_lv3_vvip = 0;
                $customers_lv3_xvvipup = 0;
            }


            $dataPrepare = [
                'user_name' => $value->user_name,
                'lv_2_mb' => $customers_lv2_mb,
                'lv_2_mo' => $customers_lv2_mo,
                'lv_2_vip' => $customers_lv2_vip,
                'lv_2_vvip' => $customers_lv2_vvip,
                'lv_2_xvvip_up' => $customers_lv2_xvvipup,
                'lv_3_mb' =>  $customers_lv3_mb,
                'lv_3_mo' =>  $customers_lv3_mo,
                'lv_3_vip' =>  $customers_lv3_vip,
                'lv_3_vvip' =>  $customers_lv3_vvip,
                'lv_3_xvvip_up' =>  $customers_lv3_xvvipup,


                'lv_2_mb_bonus' => $customers_lv2_mb*20,
                'lv_2_mo_bonus' => $customers_lv2_mo*40,
                'lv_2_vip_bonus' => $customers_lv2_vip*60,
                'lv_2_vvip_bonus' => $customers_lv2_vvip*80,
                'lv_2_xvvip_up_bonus' => $customers_lv2_xvvipup*100,
                'lv_3_mb_bonus' =>  $customers_lv3_mb*20,
                'lv_3_mo_bonus' =>  $customers_lv3_mo*40,
                'lv_3_vip_bonus' =>  $customers_lv3_vip*60,
                'lv_3_vvip_bonus' =>  $customers_lv3_vvip*80,
                'lv_3_xvvip_up_bonus' =>  $customers_lv3_xvvipup*100,
                'year' => $y,
                'month' => $m,
                'route'=>$route,
            ];

            DB::table('report_bonus_easy_new')
            ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y,'month'=>$m,'route'=>$route],$dataPrepare);
            unset($array_lv_1);
            unset($array_lv_2);

           }else{
            $dataPrepare = [
                'user_name' => $value->user_name,
                'lv_2_mb' => 0,
                'lv_2_mo' => 0,
                'lv_2_vip' => 0,
                'lv_2_vvip' => 0,
                'lv_2_xvvip_up' => 0,
                'lv_3_mb' =>  0,
                'lv_3_mo' =>  0,
                'lv_3_vip' =>  0,
                'lv_3_vvip' =>  0,
                'lv_3_xvvip_up' => 0,
                'year' => $y,
                'month' => $m,
                'route'=>$route,

            ];
            DB::table('report_bonus_easy_new')
            ->updateOrInsert(['user_name' => $value->user_name, 'year' => $y,'month'=>$m,'route'=>$route],$dataPrepare);
            unset($array_lv_1);
            unset($array_lv_2);

           }

        }
        dd('success 2');





    }
}
