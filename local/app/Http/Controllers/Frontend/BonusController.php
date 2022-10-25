<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BonusController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }
    public function bonus_all()
    {
        return view('frontend/bonus-all');
    }


    public function bonus_fastStart()
    {
        return view('frontend/bonus-fastStart');
    }

    public function bonus_team()
    {
        return view('frontend/bonus-team');
    }


    public function bonus_discount()
    {
        return view('frontend/bonus-discount');
    }

    public function bonus_matching()
    {
        return view('frontend/bonus-matching');
    }

    public function bonus_history()
    {
        return view('frontend/bonus-history');
    }
}
