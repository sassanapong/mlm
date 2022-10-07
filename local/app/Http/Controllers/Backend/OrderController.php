<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use DB;

class OrderController extends Controller
{


    public function orders_list(Request $request)
    {
        return view('backend/orders_list/index');
    }


    public function get_data_order_list(Request $request)
    {

        $orders = DB::table('db_orders')

            ->select('db_orders.*', 'dataset_order_status.detail', 'dataset_order_status.css_class')
            ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')

            ->where('dataset_order_status.lang_id', '=', 1)
            ->where('db_orders.order_status_id_fk', ['2',])
            // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(db_orders.created_at) = '{$request->s_date}' else 1 END"))
            // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) >= '{$request->s_date}' and date(db_orders.created_at) <= '{$request->e_date}'else 1 END"))
            // ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) = '{$request->e_date}' else 1 END"))

            ->orderby('db_orders.updated_at', 'DESC')
            ->get();




        return DataTables::of($orders)
            ->setRowClass('intro-x py-4 h-20 zoom-in box ')

            ->editColumn('product_value', function ($query) {
                $price = $query->product_value;
                return  number_format($price, 2) . ' บาท';
            })
            ->editColumn('created_at', function ($query) {
                $time =  date('d-m-Y h:i', strtotime($query->created_at));
                return   $time . ' น';
            })


            ->make(true);
    }


    public function view_detail_oeder($order_id)
    {
        dd($order_id);
    }
}
