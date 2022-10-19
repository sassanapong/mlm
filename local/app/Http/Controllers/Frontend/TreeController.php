<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;


class TreeController extends Controller
{
    public function index()
    {

        return view('frontend/tree');
    }


}
