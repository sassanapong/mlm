<?php

namespace App\Http\Controllers\Backend;

use App\Branch;
use App\Http\Controllers\Controller;
use App\Warehouse;
use Illuminate\Http\Request;


class ReceiveController extends Controller
{


    public function index(Request $request)
    {

        $branch = Branch::where('status', 1)->get();
        return view('backend/stock/receive/index')
            ->with('branch', $branch);
    }


    public function get_data_warehouse_select(Request $request)
    {

        $warehouse = Warehouse::where('branch_id_fk', $request->id)->get();
        return response()->json($warehouse);
    }
}
