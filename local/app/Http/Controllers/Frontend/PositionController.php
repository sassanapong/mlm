<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Auth;
class PositionController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }

    public function index()
    {

        $data_user =  DB::table('customers')
        ->select('dataset_qualification.id','dataset_qualification.business_qualifications as qualification_name','dataset_qualification.bonus')
        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
        ->where('user_name','=',Auth::guard('c_user')->user()->user_name)
        ->first();

        $count_vvip =  DB::table('customers')
                                ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                                ->where('customers.introduce_id', '=', Auth::guard('c_user')->user()->user_nam)
                                ->where('dataset_qualification.id', '=', 4)
                                ->count();


        return view('frontend/upgradePosition',compact('data_user','count_vvip'));
    }
}
