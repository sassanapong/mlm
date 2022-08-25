<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegisterController extends Controller
{


    public function index()
    {

        $yeay = date('Y');
        $age_min = 20;
        $yeay_thai = date("Y", strtotime($yeay)) + 543 - $age_min;

        $arr_year = [];
        $age_max = 80;
        for ($i = 0; $i < $age_max; $i++) {
            $arr_year[] = date("Y", strtotime($yeay_thai)) + $i;
        }

        $day = [];
        for ($i = 1; $i < 32; $i++) {
            $day[] = $i;
        }

        return view('frontend/register')
            ->with('day', $day)
            ->with('arr_year', $arr_year);
    }
}
