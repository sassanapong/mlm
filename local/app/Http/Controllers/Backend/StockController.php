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
            ->setRowClass('intro-x py-4 h-20 zoom-in box ')

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
            ->make(true);
    }
}
