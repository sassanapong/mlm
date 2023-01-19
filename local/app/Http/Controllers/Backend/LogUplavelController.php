<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;


class LogUplavelController extends Controller
{


    public function index()
    {


        return view('backend/LogUplavel_report/index');

    }

    public function log_uplavel_report_datable(Request $request)
    {
        $business_location_id = 1;
        $log_up_vl = DB::table('log_up_vl')
        ->where('status','=','success')
        ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(created_at) = '{$request->s_date}' else 1 END"))
        ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(created_at) >= '{$request->s_date}' and date(created_at) <= '{$request->e_date}'else 1 END"))
        ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(created_at) = '{$request->e_date}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  user_name = '{$request->user_name}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->position}' != ''  THEN  new_lavel = '{$request->position}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->type}' != ''  THEN  type = '{$request->type}' else 1 END"));

        $sQuery = Datatables::of($log_up_vl);
        return $sQuery

            // ->setRowClass('intro-x py-4 h-24 zoom-in')
            ->addColumn('created_at', function ($row) {
                return date('Y/m/d H:i:s', strtotime($row->created_at));
            })
            ->addColumn('bonus_total', function ($row) {
                return number_format($row->bonus_total,2);
            })

            ->addColumn('user_name', function ($row) {
                $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->user_name);
                if ($upline) {
                    $html = @$upline->name . ' ' . @$upline->last_name . ' (' . $upline->user_name . ')';
                } else {
                    $html = '-';
                }
                return $html;
            })

            ->addColumn('introduce_name', function ($row) {

                if($row->introduce_id){
                    $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->introduce_id);
                    if ($upline) {
                        $html = @$upline->name . ' ' . @$upline->last_name . ' (' . $upline->user_name . ')';
                    } else {
                        $html = '-';
                    }
                }else{
                    $html = '-';
                }

                return $html;
            })





            ->addColumn('type', function ($row) {
                if($row->type == 'register'){
                    return 'สมัครไหม่';
                }else{
                    return 'แจง PV';
                }

            })



            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }
}
