<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class ReceiveController extends Controller
{


    public function index(Request $request)
    {
        return view('backend/stock/receive/index');
    }
}
