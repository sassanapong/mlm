<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
//use Yajra\DataTables\Facades\DataTables;
use DataTables;
use Auth;

class OrderHistoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }
    public function index()
    {

        return view('frontend/order-history');
    }

    // รายละเอียดของ ออเดอร์
    public function order_detail()
    {
        return view('frontend/order-detail');
    }



    public function history_datable(Request $request)
    {
        $business_location_id = 1;

        $orders = DB::table('db_orders')
            ->select('db_orders.*', 'dataset_order_status.detail', 'dataset_order_status.css_class')
            ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')

            ->where('dataset_order_status.lang_id', '=', $business_location_id)
            ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(db_orders.created_at) = '{$request->s_date}' else 1 END"))
            ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) >= '{$request->s_date}' and date(db_orders.created_at) <= '{$request->e_date}'else 1 END"))
            ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) = '{$request->e_date}' else 1 END"))
            ->where('db_orders.customers_id_fk', '=', Auth::guard('c_user')->user()->id)
            ->orwhere('db_orders.customers_sent_id_fk', '=', Auth::guard('c_user')->user()->id)
            ->orderby('db_orders.id', 'DESC');
            // ->get();
        // dd($orders);


        $sQuery = Datatables::of($orders);
        return $sQuery

            ->setRowClass('intro-x py-4 h-24 zoom-in')
            ->addColumn('date', function ($row) {
                return date('Y/m/d H:i:s', strtotime($row->created_at));
            })
            ->addColumn('code_order', function ($row) {
                $data = '<a href="' . route('order_detail', ['code_order' => $row->code_order]) . '" class="btn btn-outline-success">' . $row->code_order . '</a>';

                return $data;
            })
            ->addColumn('total_price', function ($row) {

                return number_format($row->total_price, 2);
            })


            ->addColumn('pv_total', function ($row) {
                return '<b class="text-success">' . number_format($row->pv_total) . '</b>';
            })

            ->addColumn('quantity', function ($row) {
                return  number_format($row->quantity);
            })
            ->addColumn('tracking', function ($row) {
                if ($row->tracking_no) {
                $data = '<a href="' . route('order_detail', ['code_order' => $row->tracking_no]) . '" class="btn btn-outline-primary">' . $row->tracking_no . '</a>';

                    return  $data ;
                } else {
                    return '-';
                }
            })

            ->addColumn('user', function ($row) {
                if( $row->customers_user_name == '0534768'){

                    $name =  DB::table('customers')
                        ->select('customers.name', 'customers.last_name')
                        ->where('customers.user_name', '=', $row->customers_user_name)
                        ->first();
                    return $name->name . ' ' . $name->last_name ;
                }
                if ($row->status_payment_sent_other == 1) {

                    $name =  DB::table('customers')
                        ->select('customers.name', 'customers.last_name')
                        ->where('customers.user_name', '=', $row->customers_user_name)
                        ->first();

                    return $name->name . ' ' . $name->last_name . ' (' . $row->customers_user_name . ')';
                } else {
                    $name =  DB::table('customers')
                        ->select('customers.name', 'customers.last_name')
                        ->where('customers.user_name', '=', $row->customers_user_name)
                        ->first();

                    return $name->name . ' ' . $name->last_name . ' (' . $row->customers_user_name . ')';
                }
            })

            ->addColumn('detail', function ($row) {

                $data = '<span class="badge bg-' . $row->css_class . ' fw-light">' . $row->detail . '</span>';
                return $data;
            })





            ->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }
}
