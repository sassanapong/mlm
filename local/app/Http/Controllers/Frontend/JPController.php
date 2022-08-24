<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class JPController extends Controller
{
    public function jp_clarify()
    {
        return view('frontend/jp-clarify');
    }
    public function jp_transfer()
    {
        return view('frontend/jp-transfer');
    }
}
