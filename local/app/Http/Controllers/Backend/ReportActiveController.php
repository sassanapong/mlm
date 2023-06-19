<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;


class ReportActiveController extends Controller
{


    public function index()
    {

dd('dd');
        return view('backend/Report_active/index');

    }

    public function report_active_datable(Request $request)
    {

        $report_bonus_copyright = DB::table('report_bonus_active')
        // ->where('status','=','success')
        ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(created_at) = '{$request->s_date}' else 1 END"))
        ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(created_at) >= '{$request->s_date}' and date(created_at) <= '{$request->e_date}'else 1 END"))
        ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(created_at) = '{$request->e_date}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  user_name = '{$request->user_name}' else 1 END"))
        ->whereRaw(("case WHEN  '{$request->user_name_active}' != ''  THEN  customer_user_active = '{$request->user_name_active}' else 1 END"));
        // ->whereRaw(("case WHEN  '{$request->position}' != ''  THEN  new_lavel = '{$request->position}' else 1 END"))
        // ->whereRaw(("case WHEN  '{$request->type}' != ''  THEN  type = '{$request->type}' else 1 END"));

        $sQuery = Datatables::of($report_bonus_copyright);
        return $sQuery

            // ->setRowClass('intro-x py-4 h-24 zoom-in')
            ->addColumn('created_at', function ($row) {
                return date('Y/m/d H:i:s', strtotime($row->created_at));
            })



            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }
}
