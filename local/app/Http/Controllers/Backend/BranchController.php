<?php

namespace App\Http\Controllers\Backend;

use App\AddressProvince;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Auth;

class BranchController extends Controller
{

    public function index(Request $request)
    {

        $province = AddressProvince::orderBy('province_name', 'ASC')->get();
        return view('backend.stock.branch.index')
            ->with('province', $province);
    }
}
