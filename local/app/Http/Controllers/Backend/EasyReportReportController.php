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
        $report_bonus_active = DB::table('customers')
        ->selectRaw('customers.id,customers.user_name,customers.name,customers.last_name,customers.expire_date,qualification_id,sum(pv_total) as pv_total')
        ->wheredate('customers.expire_date','>',now())
        ->leftjoin('db_orders', 'db_orders.customers_user_name', '=', 'customers.user_name')
        ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(db_orders.created_at) = '{$request->s_date}' else 1 END"))
        ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) >= '{$request->s_date}' and date(db_orders.created_at) <= '{$request->e_date}'else 1 END"))
        ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) = '{$request->e_date}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  customers.user_name = '{$request->user_name}' else 1 END"))
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


            ->addColumn('pv_total', function ($row) {
                return  number_format($row->pv_total);
            })

            ->addColumn('xvvip_new', function ($row) {

                // $report_bonus_active1 =  DB::table('customers') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
                // ->selectRaw('count(id) as count_id')
                // // ->havingRaw('count(g) > 1 ')
                // // ->wheredate('date_active', '=', $date)
                // ->wheredate('date_active', '=','2022-12-13')
                // ->where('g', '=', 1)
                // ->where('status_copyright', '=', 'panding')
                // ->groupby('code','g')
                // ->first();
                return '';
            })

            ->addColumn('xvvip_active', function ($row) {
                return '';
            })



            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }
}
