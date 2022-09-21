<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Products;
use App\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;


class StockController extends Controller
{


    public function index(Request $request)
    {
        return view('backend/stock/report/index');
    }

    public function get_data_stock_report(Request $request)
    {
        $data =  Stock::where(function ($query) use ($request) {
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
            ->GroupBY('product_id_fk')
            ->OrderBy('updated_at', 'DESC')
            ->get();


        return DataTables::of($data)
            ->setRowClass('intro-x py-4 h-24 zoom-in box ')

            // ดึงข้อมูล product จาก id
            ->editColumn('product_id_fk', function ($query) {
                $product = Products::select(
                    'products.id',
                    'products.product_code',
                    'products_details.product_name',
                    'products_details.title'
                )
                    ->join('products_details', 'products_details.product_id_fk', 'products.id')
                    ->where('products.id', $query->product_id_fk)
                    ->first();

                $text_product = $product['product_code'] . ' : ' . $product['product_name'] .  ' (' . $product['title'] . ')';
                return $text_product;
            })
            // ดึงข้อมูล lot_number 
            ->editColumn('lot_number', function ($query) {
                $lot_number = Stock::select(
                    'lot_number',
                )
                    ->where('product_id_fk', $query->product_id_fk)
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
                    ->where('product_id_fk', $query->product_id_fk)
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
                    'dataset_product_unit.product_unit',
                )
                    ->join('products_details', 'products_details.product_id_fk', 'db_stocks.product_id_fk')
                    ->join('dataset_product_unit', 'dataset_product_unit.product_unit_id', 'products_details.product_unit_id_fk')
                    ->where('products_details.product_id_fk', $query->product_id_fk)
                    ->where('products_details.lang_id',  $query->business_location_id_fk)
                    ->where('dataset_product_unit.lang_id',  $query->business_location_id_fk)
                    ->get();

                $amt_arr = [];
                foreach ($amt as $val) {
                    $amt_arr[] = $val['amt'] . ' ' . $val['product_unit'];
                }
                return $amt_arr;
            })
            // ดึงข้อมูล สาขา คลัง วันหมดอายุ 
            ->editColumn('branch_id_fk', function ($query) {
                $branch = Stock::select(
                    'b_code',
                    'b_name',
                )
                    ->join('branchs', 'branchs.id', 'db_stocks.branch_id_fk')
                    ->join('warehouse', 'warehouse.branch_id_fk', 'branchs.id')
                    ->where('product_id_fk', $query->product_id_fk)
                    ->get();
                $branch_arr = [];
                foreach ($branch as $val) {
                    $branch_arr[] =  $val['b_code'] . ':' . $val['b_name'];
                }
                return $branch_arr;
            })
            // ดึงข้อมูล สาขา คลัง วันหมดอายุ 
            ->editColumn('warehouse_id_fk', function ($query) {
                $warehouse = Stock::select(
                    'w_code',
                    'w_name',
                )
                    ->join('branchs', 'branchs.id', 'db_stocks.branch_id_fk')
                    ->join('warehouse', 'warehouse.branch_id_fk', 'branchs.id')
                    ->where('product_id_fk', $query->product_id_fk)
                    ->get();
                $warehouse_arr = [];
                foreach ($warehouse as $val) {
                    $warehouse_arr[] = $val['w_code'] . ':' . $val['w_name'];
                }
                return $warehouse_arr;
            })

            // ดึงข้อมูล lot_number เอามาแสดงที่ btn กดดูรายละเอียด
            ->editColumn('s_maker', function ($query) {
                $btn_info = Stock::select(
                    'lot_number',
                )
                    ->where('product_id_fk', $query->product_id_fk)
                    ->get();

                $btn_info_arr = [];
                foreach ($btn_info as $val) {
                    $btn_info_arr[] =  $val['lot_number'];
                }
                return $btn_info_arr;
            })
            ->make(true);
    }
}
