<?php

namespace App\Http\Controllers\Backend;

use App\Customers;
use App\Orders;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\OrderExport;
use App\Imports\OrderImport;
use App\Shipping_type;
use DB;
use PDF;

use  Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{


    public function orders_list(Request $request)
    {

        $Shipping_type = Shipping_type::get();


        return view('backend/orders_list/index')
            ->with('Shipping_type', $Shipping_type);
    }
    public function orders_success(Request $request)
    {
        return view('backend/orders_list/succes');
    }


    public function get_data_order_list(Request $request)
    {


        $date_start = null;

        if (@request('Custom')['date_start']) {
            $date_start = date('Y-m-d H:i:s', strtotime(@request('Custom')['date_start']));
        }
        $date_end = null;
        if (@request('Custom')['date_end']) {
            $date_end = date('Y-m-d H:i:s', strtotime(@request('Custom')['date_end']));
        }


        $orders = DB::table('db_orders')
            ->select(
                'db_orders.*',
                'dataset_order_status.detail',
                'dataset_order_status.css_class',
            )
            ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
            ->leftjoin('customers', 'customers.id', '=', 'db_orders.customers_id_fk')
            ->where('dataset_order_status.lang_id', '=', 1)
            ->where('db_orders.order_status_id_fk', '=', '5')

            ->where(function ($query) use ($date_start, $date_end) {
                if ($date_start != null && $date_end != null) {
                    $query->whereDate('db_orders.created_at', '>=', date('Y-m-d', strtotime($date_start)));
                    // $query->whereTime('db_orders.created_at', '>=', date('H:i:s', strtotime($date_start)));
                    $query->whereDate('db_orders.created_at', '<=', date('Y-m-d', strtotime($date_end)));
                    // $query->whereTime('db_orders.created_at', '<=', date('H:i:s', strtotime($date_end)));
                }
            })

            ->where(function ($query) use ($request) {
                if ($request->has('Where')) {
                    foreach (request('Where') as $key => $val) {
                        if ($val) {
                            if (strpos($val, ',')) {
                                $query->whereIn($key, explode(',', $val));
                            } else {
                                $query->where($key, $val);
                            }
                        }
                    }
                }
                if ($request->has('Like')) {
                    foreach (request('Like') as $key => $val) {
                        if ($val) {
                            $query->where($key, 'like', '%' . $val . '%');
                        }
                    }
                }
            })
            // ->where('db_orders.order_status_id_fk', ['2',])
            // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(db_orders.created_at) = '{$request->s_date}' else 1 END"))
            // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) >= '{$request->s_date}' and date(db_orders.created_at) <= '{$request->e_date}'else 1 END"))
            // ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) = '{$request->e_date}' else 1 END"))
            ->orderby('db_orders.updated_at', 'DESC');

        // dd($date_start, $date_end);
        return DataTables::of($orders)
            ->setRowClass('intro-x py-4 h-20 zoom-in box ')

            ->editColumn('total_price', function ($query) {
                $price = $query->total_price;
                return  number_format($price, 2) . ' บาท';
            })

            // รวม รหัสกับชื่อสมาชิก
            // ->editColumn('customers_user_name', function ($query) {

            //     return   $customers;
            // })
            // ->editColumn('created_at', function ($query) {
            //     $time =  date('d-m-Y h:i', strtotime($query->created_at));
            //     return   $time . ' น';
            // })
            ->make(true);
    }

    public function get_data_order_list_success(Request $request)
    {



        $orders = DB::table('db_orders')
            ->select(
                'db_orders.*',
                'dataset_order_status.detail',
                'dataset_order_status.css_class',
            )
            ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id', '=', 'db_orders.order_status_id_fk')
            ->leftjoin('customers', 'customers.id', '=', 'db_orders.customers_id_fk')
            ->where('dataset_order_status.lang_id', '=', 1)
            ->where('db_orders.order_status_id_fk', '=', '7')



            // ->where('db_orders.order_status_id_fk', ['2',])
            // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(db_orders.created_at) = '{$request->s_date}' else 1 END"))
            // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) >= '{$request->s_date}' and date(db_orders.created_at) <= '{$request->e_date}'else 1 END"))
            // ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(db_orders.created_at) = '{$request->e_date}' else 1 END"))
            ->orderby('db_orders.updated_at', 'DESC');

        return DataTables::of($orders)
            ->setRowClass('intro-x py-4 h-20 zoom-in box ')

            ->editColumn('total_price', function ($query) {
                $price = $query->total_price;
                return  number_format($price, 2) . ' บาท';
            })

            // รวม รหัสกับชื่อสมาชิก
            // ->editColumn('customers_user_name', function ($query) {

            //     return   $customers;
            // })
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
                'customers.name as customers_name',
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


    public function report_order_pdf($type, $date)
    {




        $orders_detail = DB::table('db_orders')
            ->select(
                'db_orders.*',
                'district_name as district',
                'province_name as province',
                'tambon_name as tambon',
                'customers.name as customers_name',
                'customers.last_name as customers_last_name',
            )
            ->leftjoin('customers', 'customers.id', 'db_orders.customers_id_fk')
            ->leftjoin('address_districts', 'address_districts.district_id', 'db_orders.district_id')
            ->leftjoin('address_provinces', 'address_provinces.province_id', 'db_orders.province_id')
            ->leftjoin('address_tambons', 'address_tambons.tambon_id', 'db_orders.tambon_id')
            ->whereDate('db_orders.created_at', $date)
            ->where('db_orders.order_status_id_fk', '=', '5')
            ->where(function ($query) use ($type) {
                if ($type != 'all') {
                    $query->where('tracking_type', $type);
                }
            })
            ->get()
            ->map(function ($item) {
                $item->product_detail = DB::table('db_order_products_list')
                    ->select('db_order_products_list.product_name', 'db_order_products_list.amt')
                    ->leftjoin('products_details', 'products_details.product_id_fk', 'db_order_products_list.product_id_fk')
                    ->where('products_details.lang_id', 1)
                    ->where('db_order_products_list.code_order', $item->code_order)
                    ->GroupBy('products_details.product_name')
                    ->get();
                return $item;
            });


        $data = [
            'orders_detail' => $orders_detail,
        ];



        if ($orders_detail->count() > 0) {

            $pdf = PDF::loadView('backend/orders_list/report_order_pdf', $data);
            return $pdf->stream('document.pdf');
        } else {
            $status = 'ยังไม่มีรายการสั่งซ์้อ';
            return redirect('admin/orders/list')->withSuccess('Deleted Success');
        }
    }

    public function tracking_no(Request $request)
    {
        $order = Orders::where('code_order', $request->code_order)->first();
        if ($order) {
            $order->tracking_type = $request->tracking_type;
            $order->tracking_no = $request->tracking_no;
            $order->order_status_id_fk = "7";
            $order->save();
            return redirect('admin/orders/list');
        }
    }


    public function tracking_no_sort(Request $reques)
    {

        $date_start = $reques->date_start;
        $date_end = $reques->date_end;


        $orders =  DB::table('db_orders')
            ->whereDate('db_orders.created_at', '>=', date('Y-m-d', strtotime($date_start)))
            ->whereDate('db_orders.created_at', '<=', date('Y-m-d', strtotime($date_end)))
            ->where('tracking_no_sort', '');
    }

    public function orderexport()
    {
        return  Excel::download(new OrderExport, 'OrderExport-' . date("d-m-Y") . '.xlsx');
        return redirect('admin/orders/list')->with('success', 'All good!');
    }

    public function importorder()
    {
        Excel::import(new OrderImport, request()->file('excel'));

        return redirect('admin/orders/list')->with('success', 'All good!');
    }
}
