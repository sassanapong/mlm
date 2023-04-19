<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;


class ReportCopyrightController extends Controller
{


    public function index()
    {


        return view('backend/Report_copyright/index');

    }

    public function report_copyright_datable(Request $request)
    {

        $report_bonus_copyright = DB::table('report_bonus_copyright')
        // ->where('status','=','success')
        ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(created_at) = '{$request->s_date}' else 1 END"))
        ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(created_at) >= '{$request->s_date}' and date(created_at) <= '{$request->e_date}'else 1 END"))
        ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(created_at) = '{$request->e_date}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  customer_user = '{$request->user_name}' else 1 END"));
        // ->whereRaw(("case WHEN  '{$request->position}' != ''  THEN  new_lavel = '{$request->position}' else 1 END"))
        // ->whereRaw(("case WHEN  '{$request->type}' != ''  THEN  type = '{$request->type}' else 1 END"));

        $sQuery = Datatables::of($report_bonus_copyright);
        return $sQuery

            // ->setRowClass('intro-x py-4 h-24 zoom-in')
            ->addColumn('created_at', function ($row) {
                return date('Y/m/d H:i:s', strtotime($row->created_at));
            })

            ->addColumn('customer_user', function ($row) {
                // $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->user_name);
                // if ($upline) {
                //     $html = @$upline->name . ' ' . @$upline->last_name . ' (' . $upline->user_name . ')';
                // } else {
                //     $html = '-';
                // }
                return $row->customer_user;
            })

            ->addColumn('name', function ($row) {

                if($row->customer_user){
                    $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->customer_user);
                    if ($upline) {
                        $html = @$upline->name . ' ' . @$upline->last_name;
                    } else {
                        $html = '-';
                    }
                }else{
                    $html = '-';
                }

                return $html;
            })




            ->addColumn('bonus_full', function ($row) {


                return number_format($row->bonus_full);

            })
            ->addColumn('total_bonus', function ($row) {


                return number_format($row->total_bonus);

            })


            ->addColumn('date_active', function ($row) {
                return date('Y/m/d', strtotime($row->date_active));
            })


            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }
}
