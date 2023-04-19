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
        $report_bonus_register_xvvip = DB::table('report_bonus_register_xvvip')
        ->where('status','=','success')
        ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(created_at) = '{$request->s_date}' else 1 END"))
        ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(created_at) >= '{$request->s_date}' and date(created_at) <= '{$request->e_date}'else 1 END"))
        ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(created_at) = '{$request->e_date}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  user_name = '{$request->user_name}' else 1 END"))
        // ->whereRaw(("case WHEN  '{$request->position}' != ''  THEN  new_lavel = '{$request->position}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->type}' != ''  THEN  type = '{$request->type}' else 1 END"));

        $sQuery = Datatables::of($report_bonus_register_xvvip);
        return $sQuery

            // ->setRowClass('intro-x py-4 h-24 zoom-in')
            ->addColumn('created_at', function ($row) {
                return date('Y/m/d H:i:s', strtotime($row->created_at));
            })

            ->addColumn('user_name', function ($row) {
                // $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->user_name);
                // if ($upline) {
                //     $html = @$upline->name . ' ' . @$upline->last_name . ' (' . $upline->user_name . ')';
                // } else {
                //     $html = '-';
                // }
                return $row->name.'('.$row->user_name.')';
            })

            ->addColumn('introduce_name', function ($row) {

                // if($row->introduce_id){
                //     $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->introduce_id);
                //     if ($upline) {
                //         $html = @$upline->name . ' ' . @$upline->last_name . ' (' . $upline->user_name . ')';
                //     } else {
                //         $html = '-';
                //     }
                // }else{
                //     $html = '-';
                // }

                return $row->introduce_id;
            })



            ->addColumn('regis_user_name', function ($row) {


                return $row->regis_user_name;

            })

            ->addColumn('regis_user_name', function ($row) {


                return $row->regis_user_name;

            })

            ->addColumn('user_name_vvip_1', function ($row) {


                return $row->user_name_vvip_1;

            })

            ->addColumn('user_name_vvip_2', function ($row) {


                return $row->user_name_vvip_2;

            })


            ->addColumn('bonus', function ($row) {


                return number_format($row->bonus);

            })
            ->addColumn('pv_vvip_1', function ($row) {


                return number_format($row->pv_vvip_1);

            })
            ->addColumn('pv_vvip_2', function ($row) {


                return number_format($row->pv_vvip_2);

            })


            ->addColumn('type', function ($row) {
                if($row->type == 'register'){
                    return 'สมัครไหม่';
                }elseif($row->type == 'jangpv_vvip'){
                    return 'แจง PV ทัวไป';
                }elseif($row->type == 'jangpv_1200'){
                    return 'แจง PV 1200';
                }else{
                    return '-';
                }

            })



            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }
}
