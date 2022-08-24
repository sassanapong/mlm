<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class eWalletController extends Controller
{
    public function eWallet_history()
    {
        return view('frontend/eWallet-history');
    }
}
