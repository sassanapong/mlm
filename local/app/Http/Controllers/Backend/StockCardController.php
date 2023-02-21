<?php

namespace App\Http\Controllers\Backend;

use App\Branch;
use App\Http\Controllers\Controller;
use App\Matreials;
use App\Member;
use App\Products;
use App\Stock;
use App\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\FuncCall;
use Yajra\DataTables\Facades\DataTables;


class StockCardController extends Controller
{


    public function index($materials_id_fk, $branch_id_fk, $warehouse_id_fk, $lot_expired_date, $lot_number)
    {

        $stock_movement = StockMovement::select(

            'db_stock_movement.lot_number',
            'db_stock_movement.lot_expired_date',
            'branchs.b_code',
            'branchs.b_name',
            'warehouse.w_code',
            'warehouse.w_name',

        )
            ->join('branchs', 'branchs.id', 'db_stock_movement.branch_id_fk')
            ->join('warehouse', 'warehouse.branch_id_fk', 'branchs.id')
            ->where('db_stock_movement.materials_id_fk', $materials_id_fk)
            ->where('db_stock_movement.branch_id_fk',  $branch_id_fk)
            ->where('db_stock_movement.warehouse_id_fk',  $warehouse_id_fk)
            // ->GroupBy('db_stock_movement.lot_number')
            ->get();


        $max_amt = Stock::select('amt')
            ->where('materials_id_fk', $materials_id_fk)
            ->where('lot_number', $lot_number)
            ->whereDate('lot_expired_date', date('Y-m-d', strtotime($lot_expired_date)))
            ->first();

        $data = [
            'materials_id_fk' => $materials_id_fk,
            'branch_id_fk' => $branch_id_fk,
            'warehouse_id_fk' => $warehouse_id_fk,
            'stock_movement' => $stock_movement,
            'lot_expired_date' => $lot_expired_date,
            'lot_number' => $lot_number,
            'max_amt' => $max_amt['amt'],
        ];

        return view('backend/stock/card/index', $data);
    }

    public function get_stock_card(Request $request)
    {

        $date = date('Y-m-d', strtotime($request->lot_expired_date));


        $data = StockMovement::select(
            // 'dataset_product_unit.product_unit',
            'db_stock_movement.materials_id_fk',
            'db_stock_movement.lot_number',
            'db_stock_movement.lot_expired_date',
            'db_stock_movement.in_out',
            'db_stock_movement.amt',
            'db_stock_movement.action_user',
            'db_stock_movement.action_date',
            'db_stock_movement.doc_no',
            'db_stock_movement.doc_date',
            'db_stock_movement.created_at',
        )
            ->where('db_stock_movement.materials_id_fk',  $request->materials_id_fk)
            ->where('db_stock_movement.branch_id_fk',  $request->branch_id_fk)
            ->where('db_stock_movement.warehouse_id_fk',  $request->warehouse_id_fk)
            ->where('db_stock_movement.lot_number',  $request->lot_number)
            ->whereDate('db_stock_movement.lot_expired_date',  $date)
            // ->where('dataset_product_unit.lang_id', 1)
            // ->GroupBy('db_stock_movement.product_id_fk')
            // ->GroupBy('db_stock_movement.lot_number')
            // ->GroupBy('db_stock_movement.in_out')
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

            ->editColumn('materials_id_fk', function ($query) {
                $materials = Matreials::where('id', $query->materials_id_fk)->first();
                return $materials->materials_name;
            })
            // ดึงข้อมูล amt_in
            ->editColumn('amt_in', function ($query) {
                $amt =  StockMovement::select('amt')
                    ->where('materials_id_fk', $query->materials_id_fk)
                    ->where('lot_number', $query->lot_number)
                    ->where('in_out', 1)
                    ->get()->sum('amt');
                return  $amt;
            })
            // ดึงข้อมูล amt_out
            ->editColumn('amt_out', function ($query) {
                $amt =  StockMovement::select('amt')
                    ->where('materials_id_fk', $query->materials_id_fk)
                    ->where('lot_number', $query->lot_number)
                    ->where('in_out', 2)
                    ->get()->sum('amt');
                return  $amt;
            })
            // ดึงข้อมูล amt_stock
            ->editColumn('amt_stock', function ($query) {
                $amt =  Stock::select('amt')
                    ->where('materials_id_fk', $query->materials_id_fk)
                    ->where('lot_number', $query->lot_number)
                    ->get()->sum('amt');
                return  $amt;
            })
            // ดึงข้อมูล member จาก id
            ->editColumn('action_user', function ($query) {
                $member = Member::where('id', $query->action_user)->select('name')->first();
                return   $member['name'];
            })
            // วันที่ หมดอายุ date_in_stock แปลงเป็น d-m-y
            ->editColumn('lot_expired_date', function ($query) {
                $time =  date('d-m-Y', strtotime($query->lot_expired_date));
                return   $time;
            })
            // วันที่ หมดอายุ date_in_stock แปลงเป็น d-m-y
            ->editColumn('action_date', function ($query) {
                $time =  date('d-m-Y', strtotime($query->action_date));
                return   $time;
            })
            // วันที่ doc_date
            ->editColumn('doc_date', function ($query) {
                $time =  date('d-m-Y', strtotime($query->action_date));
                return   $time;
            })
            // วันที่ ทำรายการ
            ->editColumn('created_at', function ($query) {
                $time =  date('d-m-Y H:i:s', strtotime($query->created_at));
                return   $time;
            })
            // วันที่ หมดอายุ date_in_stock แปลงเป็น d-m-y
            ->editColumn('in_out', function ($query) {
                $in_out =  $query->in_out  == 1 ? "รับเข้า" : 'จ่ายออก';
                return   $in_out;
            })
            // ->editColumn('amt', function ($query) {
            //     $amt =  $query->amt;
            //     $product_unit =  StockMovement::select('dataset_product_unit.product_unit')
            //         ->join('products_details', 'products_details.product_id_fk', 'db_stock_movement.product_id_fk')
            //         ->join('products', 'products.id', 'products_details.product_id_fk')
            //         ->join('dataset_product_unit', 'dataset_product_unit.product_unit_id', 'products.unit_id')
            //         ->where('products_details.product_id_fk', $query->product_id_fk)
            //         ->where('dataset_product_unit.lang_id', 1)
            //         ->get();

            //     return  $amt . ' ' . $product_unit[0]['product_unit'];
            // })
            ->make(true);
    }
}
