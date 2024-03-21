<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{


    public function home(Request $request)
    {
        $customers = DB::table('customers')
            ->selectRaw('sum(ewallet) as total_ewallet,sum(pv) as pv_total')
            ->where('user_name', '!=', '0534768')
            ->first();


        return view('backend/index', compact('customers'));
    }
}
