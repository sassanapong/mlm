<?php

namespace App\Http\Controllers\Backend;

use App\Branch;
use App\Http\Controllers\Controller;
use App\Matreials;
use App\Products;
use App\Stock;
use App\StockMovement;
use App\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;


class StockController extends Controller
{


    public function index(Request $request)
    {
        // สาขา
        $branch = Branch::where('status', 1)->get();
        return view('backend/stock/report/index')
            ->with('branch', $branch);
    }

    public function get_data_stock_report(Request $request)
    {
        $data =  StockMovement::select(
            'id',
            'business_location_id_fk',
            'branch_id_fk',
            'materials_id_fk',
            'lot_number',
            'lot_expired_date',
            'amt',
            'in_out',
            'warehouse_id_fk',
        )
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
            ->GroupBY('materials_id_fk')
            ->OrderBy('updated_at', 'DESC')
            ->get();


        return DataTables::of($data)
            ->setRowClass('intro-x py-4 h-24 zoom-in')


            ->editColumn('materials_id_fk', function ($query) {
                $materials = Matreials::where('id', $query->materials_id_fk)->first();
                return $materials->materials_name;
            })


            // ดึงข้อมูล lot_number 
            ->editColumn('lot_number', function ($query) {
                $lot_number = Stock::select(
                    'lot_number',
                )
                    ->where('materials_id_fk', $query->materials_id_fk)
                    ->get();

                $lot_number_arr = [];
                foreach ($lot_number as $val) {
                    $lot_number_arr[] =  $val['lot_number'];
                }
                return $lot_number_arr;
            })
            // ดึงข้อมูล lot_expired_date วันหมดอายุ 
            ->editColumn('lot_expired_date', function ($query) {
                $lot_expired_date = Stock::select(
                    'lot_expired_date',
                )
                    ->where('materials_id_fk', $query->materials_id_fk)
                    ->get();
                $lot_expired_date_arr = [];
                foreach ($lot_expired_date as $val) {
                    $lot_expired_date_arr[] = date('d-m-Y', strtotime($val['lot_expired_date']));
                }
                return $lot_expired_date_arr;
            })
            // ดึงข้อมูล amt จำนวนของสินค้า 
            ->editColumn('amt', function ($query) {
                $amt = Stock::select(
                    'db_stocks.amt',
                    // 'dataset_product_unit.product_unit',
                    'db_stocks.business_location_id_fk',
                    'db_stocks.branch_id_fk',
                    'db_stocks.materials_id_fk',
                    'db_stocks.lot_number',
                    // 'db_stocks.product_unit_id_fk',
                    'db_stocks.warehouse_id_fk',
                    'db_stocks.lot_expired_date'
                )
                    ->where('db_stocks.materials_id_fk', $query->materials_id_fk)
                    ->get();
                $amt_arr = [];
                foreach ($amt as $val) {
                    // $type = StockMovement::where('business_location_id_fk', $val->business_location_id_fk)
                    //     ->where('branch_id_fk', $val->branch_id_fk)
                    //     ->where('product_id_fk', $val->product_id_fk)
                    //     ->where('lot_number', $val->lot_number)
                    //     ->where('warehouse_id_fk', $val->warehouse_id_fk)
                    //     ->where('amt', $val->amt)
                    //     ->where('lot_expired_date', $val->lot_expired_date)
                    //     ->get();

                    // dd($type);
                    $amt_arr[] = [
                        'amt' => $val['amt'],
                        // 'product_unit' => $val['product_unit'],
                        // 'in_out' => $query->in_out
                    ];
                }
                // dd($amt_arr);
                return $amt_arr;
            })
            // ดึงข้อมูล สาขา คลัง วันหมดอายุ 
            ->editColumn('branch_id_fk', function ($query) {
                $branch = Stock::select(
                    'b_code',
                    'b_name',
                )
                    ->join('branchs', 'branchs.id', 'db_stocks.branch_id_fk')
                    ->where('materials_id_fk', $query->materials_id_fk)
                    ->where('branchs.id', $query->branch_id_fk)
                    ->where('branchs.status', 1)

                    ->get();
                $branch_arr = [];
                foreach ($branch as $val) {
                    $branch_arr[] =  $val['b_code'] . ':' . $val['b_name'];
                }
                return $branch_arr;
            })
            // // ดึงข้อมูล สาขา คลัง วันหมดอายุ 
            ->editColumn('warehouse_id_fk', function ($query) {
                $warehouse_id_fk = Stock::select(
                    'db_stocks.warehouse_id_fk',
                )
                    ->join('branchs', 'branchs.id', 'db_stocks.branch_id_fk')
                    ->where('materials_id_fk', $query->materials_id_fk)
                    ->where('branchs.id', $query->branch_id_fk)
                    ->where('branchs.status', 1)
                    ->get();
                foreach ($warehouse_id_fk as $val) {
                    $query_find_warhouse[] = Warehouse::select('w_code', 'w_name')->where('id', $val['warehouse_id_fk'])->first();
                }
                $warehouse_arr = [];
                foreach ($query_find_warhouse as $val) {

                    $warehouse_arr[] =  $val['w_code'] . ':' . $val['w_name'];
                }
                return  $warehouse_arr;
            })

            // ดึงข้อมูล lot_number เอามาแสดงที่ btn กดดูรายละเอียด
            ->editColumn('s_maker', function ($query) {
                $btn_info = Stock::select(
                    'lot_number',
                )
                    ->where('materials_id_fk', $query->materials_id_fk)
                    ->get();

                $btn_info_arr = [];
                foreach ($btn_info as $val) {
                    $btn_info_arr[] =  $val['lot_number'];
                }
                return $btn_info_arr;
            })

            ->addColumn('card_product_id', function ($query) {
                return  $query->materials_id_fk;
            })
            ->addColumn('card_branch_id_fk', function ($query) {
                return  $query->branch_id_fk;
            })
            ->addColumn('card_warehouse_id_fk', function ($query) {
                $warehouse_id_fk = Stock::select(
                    'db_stocks.warehouse_id_fk',
                )
                    ->join('branchs', 'branchs.id', 'db_stocks.branch_id_fk')
                    ->where('materials_id_fk', $query->materials_id_fk)
                    ->where('branchs.id', $query->branch_id_fk)
                    ->where('branchs.status', 1)
                    ->get();
                foreach ($warehouse_id_fk as $val) {
                    $query_find_warhouse[] = Warehouse::select('id')->where('id', $val['warehouse_id_fk'])->first();
                }
                $warehouse_arr = [];
                foreach ($query_find_warhouse as $val) {

                    $warehouse_arr[] =  $val['id'];
                }
                return  $warehouse_arr;
            })
            ->make(true);
    }
}
