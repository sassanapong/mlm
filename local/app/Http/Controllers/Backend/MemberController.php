<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class MemberController extends Controller
{


    public function index(Request $request)
    {
        return view('backend/member/index');
    }
}
