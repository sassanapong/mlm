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

        return view('backend/Easy_report/index');

    }

    // public function bonus_active_report_datable(Request $request)
    // {
    //     dd('sss');


    //     $report_bonus_active = DB::table('report_bonus_active')
    //     ->select('report_bonus_active.*')
    //     // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(created_at) = '{$request->s_date}' else 1 END"))
    //     // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(created_at) >= '{$request->s_date}' and date(created_at) <= '{$request->e_date}'else 1 END"))
    //     // ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(created_at) = '{$request->e_date}' else 1 END"))
    //     // ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  user_name = '{$request->user_name}' else 1 END"))
    //     ->whereRaw(("case WHEN  '{$request->code}' != ''  THEN  code = '{$request->code}' else 1 END"))
    //     ->limit(10);



    //     $sQuery = Datatables::of($report_bonus_active);
    //     return $sQuery

    //         // ->setRowClass('intro-x py-4 h-24 zoom-in')
    //         ->addColumn('created_at', function ($row) {
    //             return date('Y/m/d H:i:s', strtotime($row->created_at));
    //         })

    //         ->addColumn('code', function ($row) {
    //             return $row->code;
    //         })

    //         ->addColumn('username_action', function ($row) {
    //             return $row->name.' ('.$row->user_name.')';
    //         })


    //         ->addColumn('active', function ($row) {
    //             return $row->customer_name_active.' ('.$row->customer_user_active.')';
    //         })

    //         ->addColumn('user_recive_bonus', function ($row) {
    //             return $row->name_g.' ('.$row->user_name_g.')';
    //         })



    //         // ->addColumn('created_at', function ($row) {
    //         //     if($row->date_active){
    //         //         return date('Y/m/d', strtotime($row->date_active));
    //         //     }else{
    //         //         return '';
    //         //     }

    //         // })


    //         // ->addColumn('note_orther', function ($row) {
    //         //     return  $row->note_orther;
    //         // })


    //         //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

    //         ->make(true);
    // }

    public function easy_report_datable(Request $request)
    {


        $business_location_id = 1;
        //sum(pv_total) as pv_total
        $report_bonus_active = DB::table('customers')
        ->selectRaw('customers.id,customers.user_name,customers.name,customers.last_name,customers.expire_date,qualification_id,sum(pv_total) as pv_total')
        ->leftjoin('db_orders', 'db_orders.customers_user_name', '=', 'customers.user_name')
        ->wheredate('customers.expire_date','>',now())
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
                ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(log_up_vl.created_at) = '{$request->s_date}' else 1 END"))
                ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(log_up_vl.created_at) >= '{$request->s_date}' and date(log_up_vl.created_at) <= '{$request->e_date}'else 1 END"))
                ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(log_up_vl.created_at) = '{$request->e_date}' else 1 END"))
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
