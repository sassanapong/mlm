<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;


class ReportOrdersController extends Controller
{


    public function index()
    {


        return view('backend/orders_report/index');
    }

    public function order_report_datable(Request $request)
    {
        $business_location_id = 1;


        $orders = DB::table('db_orders')
        ->select('db_orders.*','db_order_products_list.product_name','db_order_products_list.product_name','db_order_products_list.amt',
        'db_order_products_list.total_pv','db_order_products_list.total_price','customers.id_card','customers_address_card.address','customers_address_card.moo'
        ,'customers_address_card.soi','customers_address_card.road','district_name as district','province_name as province',
        'tambon_name as tambon','customers_address_card.zipcode','customers.name as c_name','customers.last_name')
        ->leftjoin('db_order_products_list', 'db_order_products_list.code_order', '=', 'db_orders.code_order')
        ->leftjoin('customers', 'db_orders.customers_user_name', '=', 'customers.user_name')
        ->leftjoin('customers_address_card', 'db_orders.customers_user_name', '=', 'customers_address_card.user_name')
        ->leftjoin('address_districts', 'address_districts.district_id', 'customers_address_card.district')
        ->leftjoin('address_provinces', 'address_provinces.province_id', 'customers_address_card.province')
        ->leftjoin('address_tambons', 'address_tambons.tambon_id', 'customers_address_card.tambon')

        ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(db_orders.created_at) = '{$request->s_date}' else 1 END"))
        ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) >= '{$request->s_date}' and date(db_orders.created_at) <= '{$request->e_date}'else 1 END"))
        ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) = '{$request->e_date}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  db_orders.customers_user_name = '{$request->user_name}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->code_order}' != ''  THEN  db_orders.code_order = '{$request->code_order}' else 1 END"));

        // $orders = DB::table('db_orders')
        //     ->select('db_orders.*', 'dataset_order_status.detail', 'dataset_order_status.css_class')
        //     ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')

        //     ->where('dataset_order_status.lang_id', '=', $business_location_id)
        //     ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(db_orders.created_at) = '{$request->s_date}' else 1 END"))
        //     ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) >= '{$request->s_date}' and date(db_orders.created_at) <= '{$request->e_date}'else 1 END"))
        //     ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) = '{$request->e_date}' else 1 END"));
        //     // ->where('db_orders.customers_id_fk', '=', Auth::guard('c_user')->user()->id)
        //     // ->orwhere('db_orders.customers_sent_id_fk', '=', Auth::guard('c_user')->user()->id);
        //     // ->orderby('db_orders.updated_at', 'DESC')
        //     // ->get();
        // // dd($orders);

        $sQuery = Datatables::of($orders);
        return $sQuery

            ->setRowClass('intro-x py-4 h-24 zoom-in')
            ->addColumn('date', function ($row) {
                return date('Y/m/d H:i:s', strtotime($row->created_at));
            })
            ->addColumn('code_order', function ($row) {
                $data =  $row->code_order;

                return $data;
            })

            ->addColumn('name', function ($row) {

                return $row->c_name.' '.$row->last_name;
            })


            ->addColumn('product_name', function ($row) {
                return  $row->product_name;
            })

            ->addColumn('amt', function ($row) {
                return  number_format($row->amt);
            })
            ->addColumn('total_pv', function ($row) {
                return  number_format($row->total_pv);
            })

            ->addColumn('total_price', function ($row) {
                return  number_format($row->total_price);
            })

            ->addColumn('discount', function ($row) {
                $discount = $row->total_pv * $row->bonus_percent/100;
                return  number_format($discount);
            })

            ->addColumn('total', function ($row) {
                $total = $row->total_price- ($row->total_pv * $row->bonus_percent/100);
                return  number_format($total);
            })
            // ->addColumn('id_card', function ($row) {
            //     $id_card = '';
            //     return  $id_card;
            // })

            ->addColumn('address', function ($row) {
                if($row->district){
                    $address = $row->address.' ม.'.$row->moo.' ซอย.'.$row->soi.' ถนน.'.$row->road.' ต.'.$row->tambon.' อ.'.$row->province.' จ.'.$row->district.' '.$row->zipcode;
                }else{
                    $address = '';
                }
                return $address;
            })




            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }
}
