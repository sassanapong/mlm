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
}
