<?php

namespace App\Http\Controllers\Backend;

use App\Branch;
use App\Http\Controllers\Controller;
use App\Products;
use App\Stock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;


class StockCardController extends Controller
{


    public function index(Request $request)
    {
        dd('StockCardController');
    }
}
