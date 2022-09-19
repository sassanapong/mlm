<?php

namespace App\Http\Controllers\Backend;

use App\Branch;
use App\Http\Controllers\Controller;
use App\Products;
use App\Warehouse;
use Illuminate\Http\Request;


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


        return view('backend/stock/receive/index')
            ->with('branch', $branch) //สาขา
            ->with('product', $product); //สินค้า
    }


    public function get_data_warehouse_select(Request $request)
    {

        $warehouse = Warehouse::where('branch_id_fk', $request->id)->get();
        return response()->json($warehouse);
    }
}
