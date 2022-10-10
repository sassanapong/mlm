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
            // ->where('db_orders.order_status_id_fk', ['2',])
            ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(db_orders.created_at) = '{$request->s_date}' else 1 END"))
            ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) >= '{$request->s_date}' and date(db_orders.created_at) <= '{$request->e_date}'else 1 END"))
            ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) = '{$request->e_date}' else 1 END"))

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


    public function view_detail_oeder($code_order)
    {


        $orders_detail = DB::table('db_orders')
            ->select(

                'customers.user_name',
                'customers.name',
                'customers.last_name',
                'dataset_order_status.detail',
                'dataset_order_status.css_class',
                'db_orders.*',
            )
            ->leftjoin('customers', 'customers.id', 'db_orders.customers_id_fk')
            ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', 'db_orders.order_status_id_fk')
            ->where('code_order', $code_order)
            ->get()

            ->map(function ($item) use ($code_order) {
                $item->address = DB::table('db_orders')
                    ->select(
                        'house_no',
                        'house_name',
                        'moo',
                        'soi',
                        'road',
                        'district_name as district',
                        'province_name as province',
                        'tambon_name as tambon',
                        'db_orders.zipcode',
                        'email',
                        'tel',
                    )
                    ->leftjoin('address_districts', 'address_districts.district_id', 'db_orders.district_id')
                    ->leftjoin('address_provinces', 'address_provinces.province_id', 'db_orders.province_id')
                    ->leftjoin('address_tambons', 'address_tambons.tambon_id', 'db_orders.tambon_id')
                    ->GroupBy('house_no')
                    ->where('code_order', $code_order)
                    ->get();
                return $item;
            })

            // เอาข้อมูลสินค้าที่อยู่ในรายการ order
            ->map(function ($item) use ($code_order) {
                $item->product_detail = DB::table('db_order_products_list')
                    ->leftjoin('products_details', 'products_details.product_id_fk', 'db_order_products_list.product_id_fk')
                    ->leftjoin('products_images', 'products_images.product_id_fk', 'db_order_products_list.product_id_fk')
                    ->where('products_details.lang_id', 1)
                    ->where('code_order', $code_order)
                    ->GroupBy('products_details.product_name')
                    ->get();
                return $item;
            })
            // sum total
            ->map(function ($item) use ($code_order) {
                $item->sum_total = DB::table('db_order_products_list')
                    ->where('code_order', $code_order)
                    ->get();
                return $item;
            });


        // return $orders_detail;
        return view('backend/orders_list/view_detail_oeder')
            ->with('orders_detail', $orders_detail);
    }
}
