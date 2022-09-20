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
        $customer =  DB::table('db_stocks')
            ->select('*')
            ->groupBy('product_id_fk')
            ->get();
        dd($customer);


        return DataTables::of($data)
            ->setRowClass('intro-x py-4 h-20 zoom-in box ')

            // // ดึงข้อมูล product จาก id
            // ->editColumn('product_id_fk', function ($query) {
            //     $product = Products::select(
            //         'products.id',
            //         'products.product_code',
            //         'products_details.product_name',
            //         'products_details.title'
            //     )
            //         ->join('products_details', 'products_details.product_id_fk', 'products.id')
            //         ->where('products.id', $query->product_id_fk)
            //         ->first();

            //     $text_product = $product['product_code'] . ' : ' . $product['product_name'] .  ' (' . $product['title'] . ')';
            //     return $text_product;
            // })
            ->make(true);
    }
}
