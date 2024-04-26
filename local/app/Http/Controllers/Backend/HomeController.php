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

        $report_pv_ewallet_all = DB::table('report_pv_ewallet')
            ->orderByDesc('id')
            ->limit('10')
            ->get();



        return view('backend/index', compact('report_pv_ewallet', 'report_pv_ewallet_all'));
    }
}
