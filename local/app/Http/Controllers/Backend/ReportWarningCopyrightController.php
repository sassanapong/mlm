<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;


class ReportWarningCopyrightController extends Controller
{


    public function index()
    {


        return view('backend/Report_warning_copyright/index');

    }

    public function report_warning_copyright_datable(Request $request)
    {
        $business_location_id = 1;
        $run_warning_copyright = DB::table('run_warning_copyright')
        ->where('status','=','success')
        ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(created_at) = '{$request->s_date}' else 1 END"))
        // ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(created_at) >= '{$request->s_date}' and date(created_at) <= '{$request->e_date}'else 1 END"))
        // ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(created_at) = '{$request->e_date}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->user_name_bonus_active}' != ''  THEN  user_name_bonus_active = '{$request->user_name_bonus_active}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->user_name_g}' != ''  THEN  user_name_g = '{$request->user_name_g}' else 1 END"));
        // ->whereRaw(("case WHEN  '{$request->position}' != ''  THEN  new_lavel = '{$request->position}' else 1 END"))
        // ->whereRaw(("case WHEN  '{$request->type}' != ''  THEN  type = '{$request->type}' else 1 END"));

        $sQuery = Datatables::of($run_warning_copyright);
        return $sQuery

            // ->setRowClass('intro-x py-4 h-24 zoom-in')
            ->addColumn('created_at', function ($row) {
                return date('Y/m/d H:i:s', strtotime($row->created_at));
            })

            ->addColumn('user_name_bonus_active', function ($row) {
                // $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->user_name);
                // if ($upline) {
                //     $html = @$upline->name . ' ' . @$upline->last_name . ' (' . $upline->user_name . ')';
                // } else {
                //     $html = '-';
                // }
                return $row->user_name_bonus_active;
            })

            ->addColumn('user_name_bonus_active', function ($row) {

                if($row->user_name_bonus_active){
                    $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->user_name_bonus_active);
                    if ($upline) {
                        $html = @$upline->name . ' ' . @$upline->last_name  ;
                    } else {
                        $html = '-';
                    }
                }else{
                    $html = '-';
                }
                return $html;
            })



            ->addColumn('regis_user_name', function ($row) {


                return $row->regis_user_name;

            })


            ->addColumn('date', function ($row) {
                return date('Y/m/d', strtotime($row->date));
            })



            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }
}
