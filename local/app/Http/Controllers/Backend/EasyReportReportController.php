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
        return view('backend/Easy_report/index');

    }


    public function easy_report_datable(Request $request)
    {


        $business_location_id = 1;
        //sum(pv_total) as pv_total
        $report_bonus_active = DB::table('db_orders')
        ->selectRaw('customers.id,customers.user_name,customers.name,customers.last_name,customers.expire_date,qualification_id,sum(pv_total) as pv_total')
        ->leftjoin('customers', 'db_orders.customers_user_name', '=', 'customers.user_name')
        ->wheredate('customers.expire_date','>=',now())
        // ->leftjoin('log_up_vl', 'log_up_vl.introduce_id', '=', 'customers.user_name')
        ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(db_orders.created_at) = '{$request->s_date}' else 1 END"))
        ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) >= '{$request->s_date}' and date(db_orders.created_at) <= '{$request->e_date}'else 1 END"))
        ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) = '{$request->e_date}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  customers.user_name = '{$request->user_name}' else 1 END"))
        // ->orwhere('log_up_vl.new_lavel','=','XVVIP')
        ->orderby('pv_total','DESC')
        ->groupby('customers.user_name');
        // ->whereRaw(("case WHEN  '{$request->code}' != ''  THEN  code = '{$request->code}' else 1 END"))
        // ->whereRaw(("case WHEN  '{$request->user_name_active}' != ''  THEN  customer_user_active = '{$request->user_name_active}' else 1 END"));

        $sQuery = Datatables::of($report_bonus_active);
        return $sQuery
        ->addIndexColumn()

            ->addColumn('name', function ($row) {
                return $row->name.' '.$row->last_name;
            })

            ->addColumn('pv_total', function ($row) use($request) {
                // $pv_total =  DB::table('db_orders') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
                // ->selectRaw('sum(pv_total) as pv_total')
                // ->where('customers_user_name','=',$row->user_name)
                // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(db_orders.created_at) = '{$request->s_date}' else 1 END"))
                // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) >= '{$request->s_date}' and date(db_orders.created_at) <= '{$request->e_date}'else 1 END"))
                // ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) = '{$request->e_date}' else 1 END"))
                // ->groupby('customers_user_name')
                // ->first();
                // if($pv_total){
                //     return  number_format($pv_total->pv_total);
                // }else{
                //     return 0;
                // }
                return  number_format($row->pv_total);

            })

            ->addColumn('xvvip_new', function ($row) use($request)   {

                $xvvip_new =  DB::table('report_bonus_register_xvvip')

                ->where('user_name_recive_bonus','=',$row->user_name)
                // ->where('log_up_vl.new_lavel','=','XVVIP')
                ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(created_at) = '{$request->s_date}' else 1 END"))
                ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(created_at) >= '{$request->s_date}' and date(created_at) <= '{$request->e_date}'else 1 END"))
                ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(created_at) = '{$request->e_date}' else 1 END"))
                ->count();
                return $xvvip_new;
            })

            ->addColumn('xvvip_active', function ($row)  use($request) {
                $xvvip_active =  DB::table('log_up_vl')
                ->leftjoin('customers', 'log_up_vl.user_name', '=', 'customers.user_name')
                ->where('log_up_vl.introduce_id','=',$row->user_name)
                ->where('log_up_vl.new_lavel','=','XVVIP')
                ->wheredate('customers.expire_date','>',now())
                // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(log_up_vl.created_at) = '{$request->s_date}' else 1 END"))
                // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(log_up_vl.created_at) >= '{$request->s_date}' and date(log_up_vl.created_at) <= '{$request->e_date}'else 1 END"))
                // ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(log_up_vl.created_at) = '{$request->e_date}' else 1 END"))
                ->count();
                return $xvvip_active;

            })



            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }
}
