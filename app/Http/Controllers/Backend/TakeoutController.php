<?php

namespace App\Http\Controllers\Backend;

use App\Branch;
use App\Http\Controllers\Controller;
use App\Matreials;
use App\Member;
use App\Products;
use App\ProductsUnit;
use App\Stock;
use App\StockMovement;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use Auth;

class TakeoutController extends Controller
{


    public function index(Request $request)
    {

        // สาขา
        $branch = Branch::where('status', 1)->get();








        return view('backend/stock/takeout/index')
            ->with('branch', $branch); //สาขา

    }



    public function get_data_takeout(Request $request)
    {
        $data = StockMovement::orderBy('updated_at', 'DESC')
            ->where('in_out', '2')
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


            // ดึงข้อมูล สินค้า จาก id

            ->editColumn('materials_id_fk', function ($query) {
                $materials = Matreials::where('id', $query->materials_id_fk)->first();
                return $materials->materials_name;
            })

            // ดึงข้อมูล หน่วยนับของสินค้า
            // ->editColumn('amt', function ($query) {
            //     $product_unit = ProductsUnit::select('product_unit')->where('id', $query->product_unit_id_fk)->first();
            //     $text_amt  = $query->amt . ' ' . $product_unit['product_unit'];
            //     return $text_amt;
            // })

            // วันที่ หมดอายุ date_in_stock แปลงเป็น d-m-y
            ->editColumn('lot_expired_date', function ($query) {
                $time =  date('d-m-Y', strtotime($query->lot_expired_date));
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
            ->editColumn('action_user', function ($query) {
                $member = Member::where('id', $query->action_user)->select('name')->first();
                return   $member['name'];
            })
            ->make(true);
    }



    public function get_data_warehouse_select(Request $request)
    {

        $warehouse = Warehouse::where('branch_id_fk', $request->id)->where('status', 1)->get();
        return response()->json($warehouse);
    }

    public function get_data_matereials(Request $request)
    {
        // สินค้า
        $matereials = Stock::select('matreials.id', 'matreials.materials_name', 'db_stocks.amt')
            ->where('amt', '>', 0)
            ->where('warehouse_id_fk', $request->warehouse_id_fk)
            ->join('matreials', 'matreials.id', 'db_stocks.materials_id_fk')
            ->GroupBy('id')
            ->get();
        return response()->json($matereials);
    }


    public function get_data_product_select(Request $request)
    {
        $id =  $request->id;

        $product = Stock::select('matreials.id', 'matreials.materials_name')
            ->join('matreials', 'matreials.id', 'db_stocks.materials_id_fk')
            ->where('warehouse_id_fk', $id)
            ->GroupBy('matreials.id')
            ->get();


        return response()->json($product);
    }

    public function get_lot_number_takeout(Request $request)
    {

        $query = Stock::select('lot_number')->where('materials_id_fk', $request->materials_id)
            ->groupBy('lot_number')
            ->get();
        return response()->json($query);
    }

    public function get_lot_expired_date(Request $request)
    {
        $query = Stock::select('lot_expired_date')->where('lot_number', $request->lot_number)->get();
        $arr_query = [];

        foreach ($query as $val) {
            $arr_query[] = [
                'lot_expired_date' => date('d-m-Y', strtotime($val['lot_expired_date']))
            ];
        }

        return response()->json($arr_query);
    }

    public function get_max_input_atm_takeout(Request $request)
    {

        $materials_id = $request->materials_id;
        $lot_number = $request->lot_number;
        $lot_expired_date =  date('Y-m-d', strtotime($request->lot_expired_date));


        $max_atm = Stock::select('amt')
            ->where('materials_id_fk', $materials_id)
            ->where('lot_number', $lot_number)
            ->whereDate('lot_expired_date', $lot_expired_date)
            ->first();

        return  $max_atm;
    }


    public function takeout_product(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'branch_id_fk' => 'required',
                'warehouse_id_fk' => 'required',
                'materials_id_fk' => 'required',
                'lot_number' => 'required',
                'lot_expired_date' => 'required',
                'amt' => 'required',
                // 'product_unit_id_fk' => 'required',
                'doc_no' => 'required',
                'doc_date' => 'required',

            ],
            [
                'branch_id_fk.required' => 'กรุณาเลือกสาขา',
                'warehouse_id_fk.required' => 'กรุณาเลือกคลัง',
                'materials_id_fk.required' => 'กรุณาเลือกสินค้า',
                'lot_number.required' => 'กรุณากรอกข้อมูล',
                'lot_expired_date.required' => 'กรุณากรอกข้อมูล',
                'amt.required' => 'กรุณากรอกข้อมูล',
                'doc_no.required' => 'กรุณากรอกข้อมูล',
                'doc_date.required' => 'กรุณากรอกข้อมูล',
                // 'product_unit_id_fk.required' => 'กรุณาเลือกหน่วยนับ',

            ]
        );

        if (!$validator->fails()) {
            $data = $request->all();

            $dataPrepareStock = [
                'branch_id_fk' => $request->branch_id_fk,
                'materials_id_fk' => $request->materials_id_fk,
                'lot_number' => $request->lot_number,
                'lot_expired_date' =>  date('Y-m-d', strtotime($request->lot_expired_date)),
                'warehouse_id_fk' => $request->warehouse_id_fk,
                'amt' => $request->amt,
                'product_unit_id_fk' => $request->product_unit_id_fk,
                'date_in_stock' => date('Y-m-d'),
                's_maker' => Auth::guard('member')->user()->id,
                'business_location_id_fk' => 1,
            ];

            $dataPrepareStockMovement = [
                'branch_id_fk' => $request->branch_id_fk,
                'materials_id_fk' => $request->materials_id_fk,
                'lot_number' => $request->lot_number,
                'lot_expired_date' =>  date('Y-m-d', strtotime($request->lot_expired_date)),
                'warehouse_id_fk' => $request->warehouse_id_fk,
                'amt' => $request->amt,
                'product_unit_id_fk' => $request->product_unit_id_fk,
                'action_date' => date('Y-m-d'),
                'action_user' => Auth::guard('member')->user()->id,
                'business_location_id_fk' => 1,
                'doc_no' => $request->doc_no,
                'doc_date' => $request->doc_date,
                'in_out' => 2
            ];


            // ถ้ามีสินค้าในระบบแล้วจะเป็นการ อัพเดท จำนวนทับกับตัวเก่าที่มีใน stock 
            // stock_movement จะเป็นการสร้างใหม่ทุกครั้ง
            $data_check = Stock::where('branch_id_fk', $request->branch_id_fk)
                ->where('materials_id_fk', $request->materials_id_fk)
                ->where('warehouse_id_fk', $request->warehouse_id_fk)
                ->where('lot_number', $request->lot_number)
                ->where('lot_expired_date',  date('Y-m-d', strtotime($request->lot_expired_date)))
                ->first();

            if ($data_check) {
                $query = Stock::where('id', $data_check->id)->first();

                $data_amt = [
                    'amt' => $query->amt - $request->amt
                ];
                $query->update($data_amt);
            } else {

                $query = Stock::create($dataPrepareStock);
            }


            $query = StockMovement::create($dataPrepareStockMovement);
            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['error' => $validator->errors()]);
    }
}
