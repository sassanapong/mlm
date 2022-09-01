<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class HomeController extends Controller
{


    public function home(Request $request)
    {
        return view('backend/index');
    }
}
