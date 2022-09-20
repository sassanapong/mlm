<?php

namespace App\Http\Controllers\Backend;

use App\Branch;
use App\Http\Controllers\Controller;
use App\Member;
use App\Products;
use App\ProductsUnit;
use App\Stock;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Auth;

class ReceiveController extends Controller
{


    public function index(Request $request)
    {

        // สาขา
        $branch = Branch::where('status', 1)->get();


        // สินค้า
        $product = Products::select(
            'products.id',
            'products.product_code',
            'products_details.product_name',
            'products_details.title'
        )
            ->join('products_details', 'products_details.product_id_fk', 'products.id')
            ->where('products_details.lang_id', 1)
            ->get();



        return view('backend/stock/receive/index')
            ->with('branch', $branch) //สาขา
            ->with('product', $product); //สินค้า

    }



    public function get_data_receive(Request $request)
    {
        $data = Stock::orderBy('created_at', 'DESC')
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
            ->get();


        return DataTables::of($data)
            ->setRowClass('intro-x py-4 h-20 zoom-in box')

            // ดึงข้อมูลสามขา branch จาก branch_id_fk
            ->editColumn('branch_id_fk', function ($query) {
                $branch =  Branch::select('b_code', 'b_name')->where('id', $query->branch_id_fk)->first();
                $text_branch =   $branch['b_code'] . ":" . $branch['b_name'];
                return  $text_branch;
            })


            // ดึงข้อมูล product จาก id
            ->editColumn('product_id_fk', function ($query) {
                $product = Products::select(
                    'products.product_code',
                    'products_details.product_name',
                    'products_details.title'
                )
                    ->join('products_details', 'products_details.product_id_fk', 'products.id')
                    ->where('products.product_code', $query->product_unit_id_fk)
                    ->first();

                $text_product =  $product['product_name'] .  ' (' . $product['title'] . ')';
                return   $text_product;
            })

            // ดึงข้อมูล หน่วยนับของสินค้า
            ->editColumn('amt', function ($query) {
                $product_unit = ProductsUnit::select('product_unit')->where('id', $query->product_unit_id_fk)->first();
                $text_amt  = $query->amt . ' ' . $product_unit['product_unit'];
                return $text_amt;
            })
            // วันที่ หมดอายุ date_in_stock แปลงเป็น d-m-y
            ->editColumn('date_in_stock', function ($query) {
                $time =  date('d-m-Y', strtotime($query->date_in_stock));
                return   $time;
            })

            // ดึงข้อมูล คลังที่จัดเก็บ
            ->editColumn('warehouse_id_fk', function ($query) {
                $warehouse = Warehouse::select('w_code', 'w_name')->where('id', $query->warehouse_id_fk)->first();
                $text_warehouse =   $warehouse['w_code'] . ":" . $warehouse['w_name'];
                return   $text_warehouse;
            })

            // วันที่รับเข้าสินค้า แปลงเป็น d-m-y
            ->editColumn('created_at', function ($query) {
                $time =  date('d-m-Y H:i:s', strtotime($query->created_at));
                return   $time;
            })

            // ดึงข้อมูล member จาก id
            ->editColumn('s_maker', function ($query) {
                $member = Member::where('id', $query->s_maker)->select('name')->first();
                return   $member['name'];
            })
            ->make(true);
    }



    public function get_data_warehouse_select(Request $request)
    {

        $warehouse = Warehouse::where('branch_id_fk', $request->id)->where('status', 1)->get();
        return response()->json($warehouse);
    }


    public function get_data_product_unit(Request $request)
    {
        $product_id =  $request->product_id;

        $product_unit = Products::select(
            'dataset_product_unit.product_unit',
            'products_details.product_unit_id_fk',
        )
            ->join('products_details', 'products_details.product_id_fk', 'products.id')
            ->join('dataset_product_unit', 'dataset_product_unit.product_unit_id', 'products_details.product_unit_id_fk')
            ->where('products.id', $product_id)
            ->first();

        return response()->json($product_unit);
    }


    public function store_product(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'branch_id_fk' => 'required',
                'warehouse_id_fk' => 'required',
                'product_id_fk' => 'required',
                'lot_number' => 'required',
                'lot_expired_date' => 'required',
                'amt' => 'required',
                'product_unit_id_fk' => 'required',

            ],
            [
                'branch_id_fk.required' => 'กรุณาเลือกสาขา',
                'warehouse_id_fk.required' => 'กรุณาเลือกคลัง',
                'product_id_fk.required' => 'กรุณาเลือกสินค้า',
                'lot_number.required' => 'กรุณากรอกข้อมูล',
                'lot_expired_date.required' => 'กรุณากรอกข้อมูล',
                'amt.required' => 'กรุณากรอกข้อมูล',
                'product_unit_id_fk.required' => 'กรุณาเลือกหน่วยนับ',

            ]
        );

        if (!$validator->fails()) {
            $data = $request->all();
            $dataPrepare = [];
            foreach ($data as $key => $value) {
                if ($key != '_token') {
                    $dataPrepare[$key] = $value;
                }
                $dataPrepare['date_in_stock'] = date('Y-m-d');
                $dataPrepare['s_maker'] = Auth::guard('member')->user()->id;
                $dataPrepare['business_location_id_fk'] = 1;
            }

            $query = Stock::create($dataPrepare);
            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['error' => $validator->errors()]);
    }
}
