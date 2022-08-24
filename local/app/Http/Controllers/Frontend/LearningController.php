<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LearningController extends Controller
{

    public function index()
    {
        return view('frontend/learning');
    }

    public function learning_detail()
    {
        return view('frontend/learning-detail');
    }


    public function ct()
    {
        return view('frontend/ct');
    }
    public function ct_detail()
    {
        return view('frontend/ct-detail');
    }
}
