<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;


class CashBackReportControlle extends Controller
{


    public function index()
    {
        $data = CashBackReportControlle::run_report_cashback();
        dd($data);

        return view('backend/Cashback_report/index');

    }

    public function run_report_cashback()
    {
        $y = '2023';
        $m = '03';

        $s_date = date('2023-03-25');
        $e_date = date('2023-03-31');
        $order =  DB::table('db_orders') //รายชื่อคนที่มีรายการแจงโบนัสข้อ
        ->selectRaw('db_orders.customers_user_name,sum(db_orders.pv_total) as pv_total,customers.name,customers.last_name,customers.expire_date,customers.qualification_id')
        ->leftjoin('customers', 'db_orders.customers_user_name', '=', 'customers.user_name')
        ->whereBetween('db_orders.created_at', [$s_date, $e_date])
        ->wherein('order_status_id_fk',[4,5,6,7])
        ->groupby('customers_user_name')
        ->get();
        dd($order);



    }


    public function cashback_report_datable(Request $request)
    {

        $report_bonus_all_sale = DB::table('report_bonus_all_sale')
        ->where('year','=',$request->s_date)
        ->where('month','=',$request->e_date)
        // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(db_orders.created_at) = '{$request->s_date}' else 1 END"))
        // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) >= '{$request->s_date}' and date(db_orders.created_at) <= '{$request->e_date}'else 1 END"))
        // ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) = '{$request->e_date}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  report_bonus_all_sale.user_name = '{$request->user_name}' else 1 END"))
        ->orderby('bonus_total','DESC');


        $sQuery = Datatables::of($report_bonus_all_sale);
        return $sQuery
        ->addIndexColumn()
        ->addColumn('date', function ($row) {

                return $row->year.'/'.$row->month;

        })
            ->addColumn('bonus_total', function ($row) {
                if($row->bonus_total){
                    return number_format($row->bonus_total,2);
                }else{
                    return 0;
                }
            })
            ->addColumn('bonus_type_7', function ($row) {
                if($row->bonus_type_7){
                    return number_format($row->bonus_type_7,2);
                }else{
                    return 0;
                }
            })
            ->addColumn('bonus_type_9', function ($row) {
                if($row->bonus_type_9){
                    return number_format($row->bonus_type_9,2);
                }else{
                    return 0;
                }
            })

            ->addColumn('bonus_type_10', function ($row) {
                if($row->bonus_type_10){
                    return number_format($row->bonus_type_10,2);
                }else{
                    return 0;
                }
            })

            ->addColumn('bonus_type_11', function ($row) {
                if($row->bonus_type_11){
                    return number_format($row->bonus_type_11,2);
                }else{
                    return 0;
                }
            })

              ->addColumn('active_date', function ($row) {
                if(empty($row->active_date) || (strtotime($row->active_date) < strtotime(date('Ymd')))){

                    $date_tv_active= date('d/m/Y',strtotime($row->active_date));
                    $resule ='<span class="text-danger">Not Active</span>';
                    return $resule;
                }else{
                    $date_tv_active= date('d/m/Y',strtotime($row->active_date));
                    $resule ='<span class="text-success">Active</span>';
                    return $resule;

                }
            })




            ->rawColumns(['active_date'])

            ->make(true);
    }
}
