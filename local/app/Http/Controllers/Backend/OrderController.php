<?php

namespace App\Http\Controllers\Backend;

use App\Customers;
use App\Orders;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\OrderExport;
use App\Imports\OrderImport;
use App\Matreials;
use App\Order_products_list;
use App\ProductMaterals;
use App\Stock;
use App\StockMovement;
use DB;
use PDF;

use  Maatwebsite\Excel\Facades\Excel;

class OrderController extends Controller
{


    public function orders_list(Request $request)
    {
        return view('backend/orders_list/index');
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


    public function report_order_pdf(Request $request)
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
            ->whereDate('db_orders.created_at', $request->date)
            ->where('db_orders.order_status_id_fk', '=', '5')
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

        // dd($data);

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


            return   $this->get_material($request->code_order);

            return redirect('admin/orders/list');
        }
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



    public function get_material($code_order)
    {


        $list_product = Order_products_list::select('product_id_fk', 'amt')->where('code_order', $code_order)
            ->get();



        $amt_material = [];
        foreach ($list_product as $key => $item) {
            $data_detail = ProductMaterals::select('matreials_id')
                ->where('product_id', $item->product_id_fk)
                ->selectRaw('matreials_count * ' . $item->amt . ' as  sum_amt_material')
                ->get();


            foreach ($data_detail as $val) {
                $amt_material[] = $val;
            }
        }

        // return  $amt_material;
        return  $this->cal_material($amt_material);
    }

    public function cal_material($data)
    {


        $carts = [
            [
                "matreials_id" => 1,
                "sum_amt_material" => 15
            ],
            [
                "matreials_id" => 2,
                "sum_amt_material" => 30
            ],
            [
                "matreials_id" => 3,
                "sum_amt_material" => 20
            ],
        ];

        $stock = [
            [
                "matreials_id" => 1,
                "sum_amt_material" => 10
            ],
            [
                "matreials_id" => 1,
                "sum_amt_material" => 50
            ],
            [
                "matreials_id" => 2,
                "sum_amt_material" => 40
            ],
            [
                "matreials_id" => 3,
                "sum_amt_material" => 40
            ],
        ];
        $result = [];
        foreach ($carts as $cart) {
            $id = $cart['matreials_id'];
            $amt = $cart['sum_amt_material'];
            $sum = 0;
            $i = 1;
            foreach ($stock as $st) {

                // loop stoc
                $st_id = $st['matreials_id'];
                $st_am = $st['sum_amt_material'];

                if (isset($esus)) {
                    if ($st_id == $id) { // match item
                        if ($amt > $st_am) {
                            $esus =  $st_am - $amt; // stock less than
                            $sum =  abs($esus);
                            $st_am = 0;
                        } else { //stock more than
                            $esus = $st_am - $sum; 
                            $sum =  abs($esus);
                        }
                        $rs['sum'] = $sum;
                        $rs['id'] = $st_id;
                        $rs['amont'] = $amt;
                        array_push($result, $rs);
                    }
                } else {
                    if ($st_id == $id) { // match item
                        if ($amt > $st_am) {
                            $esus =  $st_am - $amt; // stock less than
                            $sum =  abs($esus);
                            $st_am = 0;
                        } else { //stock more than
                            $esus = $amt - $st_am;
                            $sum =  abs($esus);
                        }
                        $rs['sum'] = $sum;
                        $rs['id'] = $st_id;
                        $rs['amont'] = $amt;
                        array_push($result, $rs);
                    }
                }
                $i++;
            }
        }

        echo '<pre>';
        print_r($result);





        // foreach ($data as $item) {
        //     $stocks[$item->matreials_id] = StockMovement::select('materials_id_fk', 'lot_number', 'lot_expired_date', 'amt', 'doc_date')
        //         ->where('amt', '>', 0)
        //         ->where('materials_id_fk', $item->matreials_id)
        //         ->OrderBy('doc_date', 'asc')
        //         ->get();
        // }

        // return [$stocks, $data];







        // foreach ($data as $coust) {

        //     $id_materials = $coust->matreials_id;

        //     $cost = 0;
        //     $cal = [];
        //     foreach ($stocks[$coust->matreials_id] as $stock) {

        //         if ($id_materials == $stock->materials_id_fk) {
        //             $cost_amt =  $coust->sum_amt_material;
        //             $stock_amt = $stock->amt;
        //             if ($cost_amt >= $stock_amt) {
        //                 $cal['more'] = $cost_amt - $stock_amt;
        //             } else {
        //                 $cal['less'] = $stock_amt - $cost_amt;
        //             }
        //         }

        //         if ($cal['less'] != 0) {

        //             return '123';
        //         }
        //     }
        // }


        // return $cal;

        // $carts = [
        //     [
        //         "matreials_id" => 1,
        //         "sum_amt_material" => 15
        //     ],
        //     [
        //         "matreials_id" => 2,
        //         "sum_amt_material" => 30
        //     ],
        // ];

        // $stock = [
        //     [
        //         "matreials_id" => 1,
        //         "sum_amt_material" => 10
        //     ],
        //     [
        //         "matreials_id" => 1,
        //         "sum_amt_material" => 50
        //     ],
        //     [
        //         "matreials_id" => 2,
        //         "sum_amt_material" => 40
        //     ],
        // ];
        // $result = [];
        // foreach ($carts as $cart) {
        //     $id = $cart['matreials_id'];
        //     $amt = $cart['sum_amt_material'];
        //     $sum = 0;
        //     $i = 1;
        //     foreach ($stock as $st) {

        //         // loop stoc
        //         $st_id = $st['matreials_id'];
        //         $st_am = $st['sum_amt_material'];


        //         // } elseif ($st_id ==   $st['matreials_id']) {
        //         // } else {
        //         //     $st_am = $st['sum_amt_material'];
        //         // }

        //         if ($st_id == $id) { // match item
        //             if ($amt > $st_am) {
        //                 $esus =  $st_am - $amt; // stock less than
        //                 $sum =  abs($esus);
        //                 $st_am = 0;
        //             } else { //stock more than
        //                 $esus = $st_am - $sum;
        //                 $sum =  abs($esus);
        //             }
        //             $rs['sum'] = $sum;
        //             array_push($result, $rs);
        //         }

        //         $i++;
        //     }
        // }




        // return [$result];



        // return $data;

        // ลบจำนวนวัถุดิบ
    }
}
