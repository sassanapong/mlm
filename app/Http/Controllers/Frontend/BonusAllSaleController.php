<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Auth;

class BonusAllSaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }
    public function index()
    {
        $AllSaleTotal = DB::table('report_bonus_all_sale_permouth')
            ->where('customer_id_fk', Auth::guard('c_user')->user()->id)
            ->sum('bonus_total_not_tax');

        $customers =  DB::table('customers')
            ->select(
                'customers.id',
                'customers.user_name',
                'customers.pv_upgrad',
                'customers.status_customer',
                'customers.ewallet',
                'customers.ewallet_use',
                'customers.expire_date',
                'customers.expire_date_bonus',
                'dataset_qualification.business_qualifications as qualification_name',
                'dataset_qualification.bonus'
            )
            ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', Auth::guard('c_user')->user()->user_name)

            ->first();


        return view('frontend/bonus-AllSale', compact('AllSaleTotal', 'customers'));
    }
}
