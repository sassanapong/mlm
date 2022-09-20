<?php

namespace App\Http\Controllers\Backend;

use App\Branch;
use App\Http\Controllers\Controller;
use App\Products;
use App\ProductsUnit;
use App\Stock;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Auth;

class ReceiveController extends Controller
{


    public function index(Request $request)
    {

        // สาขา
        $branch = Branch::where('status', 1)->get();


        // สินค้า
        $product = Products::select(
            'products.product_code',
            'products_details.product_name',
            'products_details.title'
        )
            ->join('products_details', 'products_details.product_id_fk', 'products.id')
            ->where('products_details.lang_id', 1)
            ->get();

        //หน่วยนับสินค้า
        $product_unit = ProductsUnit::where('lang_id', 1)->get();

        return view('backend/stock/receive/index')
            ->with('branch', $branch) //สาขา
            ->with('product', $product) //สินค้า
            ->with('product_unit', $product_unit); //หน่วยนับสินค้า
    }


    public function get_data_warehouse_select(Request $request)
    {

        $warehouse = Warehouse::where('branch_id_fk', $request->id)->get();
        return response()->json($warehouse);
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
