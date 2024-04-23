<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{


    public function home(Request $request)
    {
        $report_pv_ewallet = DB::table('report_pv_ewallet')
            ->whereDate('created_at', '=', date('Y-m-d'))
            ->first();


        return view('backend/index', compact('report_pv_ewallet'));
    }
}
