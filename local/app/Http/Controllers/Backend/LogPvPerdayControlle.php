<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;

use App\Exports\LogPvPerDayExport;
use Maatwebsite\Excel\Facades\Excel;

class LogPvPerdayControlle extends Controller
{


    public function index()
    {
        //   $data = AllsaleReportControlle::vl_2_3();
        //  dd($data);ssas
        return view('backend/LogPvPerday/index');
    }

    public function log_pv_per_day_ab_balance_all()
    {
        //   $data = AllsaleReportControlle::vl_2_3();
        //  dd($data);



        return view('backend/LogPvPerday/log_pv_per_day_ab_balance_all');
    }

    public function report_bonus_es()
    {
        //   $data = AllsaleReportControlle::vl_2_3();
        //  dd($data);

        return view('backend/LogPvPerday/report_bonus_es');
    }


    public function report_pv_per_day_ab_balance_bonus7()
    {
        //   $data = AllsaleReportControlle::vl_2_3();
        //  dd($data);


        $data = DB::table('report_pv_per_day_ab_balance_bonus7')

            ->selectRaw('SUM(bonus_full) AS el,date_action')
            ->where('status', '=', 'pending')
            // ->where('recive_user_name', '1169186')
            ->limit(100)
            // ->whereDate('date_action', '=', $action_date)
            ->groupBy('date_action')
            ->get();


        return view('backend/LogPvPerday/report_pv_per_day_ab_balance_bonus7', compact('data'));
    }

    public function report_pv_per_day_ab_balance()
    {
        //   $data = AllsaleReportControlle::vl_2_3();
        //  dd($data);


        $data = DB::table('report_pv_per_day_ab_balance')

            ->selectRaw('SUM(bonus_full) AS el,date_action')
            ->where('status', '=', 'pending')
            // ->where('recive_user_name', '1169186')
            ->limit(100)
            // ->whereDate('date_action', '=', $action_date)
            ->groupBy('date_action')
            ->get();

        return view('backend/LogPvPerday/report_pv_per_day_ab_balance', compact('data'));
    }



    public function report_pv_per_day_ab_balance_bonus9()
    {
        //   $data = AllsaleReportControlle::vl_2_3();
        //  dd($data);

        $data = DB::table('report_pv_per_day_ab_balance_bonus9')
            ->select(
                'recive_user_name',
                DB::raw('SUM(bonus_full) as el'),

                'date_action'
            )
            ->where('status', '=', 'pending')
            // ->where('recive_user_name', '1169186')
            ->limit('500')
            ->groupby('date_action')
            ->get();

        return view('backend/LogPvPerday/report_pv_per_day_ab_balance_bonus9', compact('data'));
    }



    public function log_pv_per_day_excel(Request $request)
    {
        $log_pv_per_day = DB::table('log_pv_per_day')
            ->whereDate('date_action', $request->e_date)
            ->get();


        $logArray[] = [
            'id',
            'user_name',
            'type_recive',
            'user_name_recive',
            'customer_id_fk',
            'customer_id_fk_recive',
            'pv_upline_total',
            'pv',
            'type',
            'year',
            'month',
            'day',
            'date_action',
            'created_at',
            'updated_at',
            'deleted_at'
        ];

        foreach ($log_pv_per_day as $log) {
            $logArray[] = (array) $log;
        }

        return Excel::download(new LogPvPerDayExport($logArray), 'log_pv_per_day.xlsx');
    }

    public function log_pv_per_day_all_excel(Request $request)
    {
        $log_pv_per_day_ab_balance_all = DB::table('log_pv_per_day_ab_balance_all')
            ->whereBetween('date_action', [$request->s_date, $request->e_date])
            ->get();

        $logArray[] = [
            'id',
            'user_name',
            'customer_id_fk',
            'name',
            'introduce_id',
            'qualification_id',
            'balance',
            'balance_type',
            'balance_up_old',
            'pv_today',
            'pv_a_new',
            'pv_b_new',
            'pv_a',
            'pv_b',
            'pv_a_old',
            'pv_b_old',
            'kang',
            'aoon',
            'note',
            'year',
            'month',
            'day',
            'date_action',
            'status',
            'created_at',
            'updated_at',
            'deleted_at'
        ];


        foreach ($log_pv_per_day_ab_balance_all as $log) {
            $logArray[] = (array) $log;
        }

        return Excel::download(new LogPvPerDayExport($logArray), 'log_pv_per_day_ab_balance_all.xlsx');
    }

    public function report_pv_per_day_ab_balance_bonus7_excel(Request $request)
    {
        $report_pv_per_day_ab_balance_bonus7 = DB::table('report_pv_per_day_ab_balance_bonus7')
            ->whereDate('date_action', $request->e_date)
            ->get();
        $logArray[] = [
            'id',
            'user_name',
            'qualification',
            'introduce_id',
            'upline_id',
            'type_upline',
            'jang_pv_fk',
            'code',
            'jang_user_name',
            'jang_introduce_id',
            'jang_qualification',
            'jang_expire_date',
            'pv',
            'g',
            'percen',
            'tax_percen',
            'tax_total',
            'bonus_full',
            'bonus',
            'remark_transfer',
            'date_action',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
        ];

        foreach ($report_pv_per_day_ab_balance_bonus7 as $log) {
            $logArray[] = (array) $log;
        }

        return Excel::download(new LogPvPerDayExport($logArray), 'report_pv_per_day_ab_balance_bonus7.xlsx');
    }

    public function report_pv_per_day_ab_balance_excel(Request $request)
    {
        $report_pv_per_day_ab_balance = DB::table('report_pv_per_day_ab_balance')
            ->whereBetween('date_action', [$request->s_date, $request->e_date])
            ->get();
        $logArray[] = [
            'id',
            'user_name',
            'introduce_id',
            'customer_id_fk',
            'name',
            'qualification_id',
            'bonus_limit',
            'expire_date',
            'balance',
            'balance_up_old',
            'kang',
            'aoon',
            'rate',
            'balance_type',
            'bonus_aoon',
            'bonus_kang',
            'kang_balance_up_old',
            'note',
            'year',
            'month',
            'day',
            'date_action',
            'tax_percen',
            'tax_total',
            'bonus_full',
            'bonus',
            'status',
            'status_bonus9',
            'created_at',
            'updated_at',
            'deleted_at',
        ];

        foreach ($report_pv_per_day_ab_balance as $log) {
            $logArray[] = (array) $log;
        }

        return Excel::download(new LogPvPerDayExport($logArray), 'report_pv_per_day_ab_balance.xlsx');
    }


    public function report_pv_per_day_ab_balance_bonus9_excel(Request $request)
    {
        $report_pv_per_day_ab_balance_bonus9 = DB::table('report_pv_per_day_ab_balance_bonus9')
            ->whereBetween('date_action', [$request->s_date, $request->e_date])
            ->get();

        $logArray[] = [
            'id',
            'user_name',
            'qualification',
            'introduce_id',
            'rate',
            'recive_user_name',
            'recive_introduce_id',
            'recive_qualification',
            'recive_expire_date',
            'bonus_full_8',
            'g',
            'percen',
            'tax_percen',
            'tax_total',
            'bonus_full',
            'bonus',
            'remark_transfer',
            'date_action',
            'status',
            'created_at',
            'updated_at',
            'deleted_at',
        ];


        foreach ($report_pv_per_day_ab_balance_bonus9 as $log) {
            $logArray[] = (array) $log;
        }

        return Excel::download(new LogPvPerDayExport($logArray), 'report_pv_per_day_ab_balance_bonus9.xlsx');
    }




    public function report_pv_per_day_ab_balance_bonus9_datable(Request $request)
    {
        $log_pv_per_day = DB::table('report_pv_per_day_ab_balance_bonus9')
            ->whereBetween('date_action', [$request->s_date, $request->e_date])
            ->whereRaw("case WHEN '{$request->user_name}' != '' THEN report_pv_per_day_ab_balance_bonus9.user_name = '{$request->user_name}' else 1 END");

        $sQuery = Datatables::of($log_pv_per_day);

        return $sQuery
            ->addIndexColumn() // เพิ่มดัชนีแถวที่นี่
            ->addColumn('date_action', function ($row) {
                return date('d/m/Y', strtotime($row->date_action));
            })
            ->rawColumns(['date_action']) // เปลี่ยนจาก 'active_date' เป็น 'date_action'
            ->make(true);
    }


    public function bonus_es_datable(Request $request)
    {
        $report_bonus_2024_easy = DB::table('report_bonus_2024_easy')
            ->whereBetween('date_action', [$request->s_date, $request->e_date])
            ->whereRaw("case WHEN '{$request->user_name}' != '' THEN report_bonus_2024_easy.user_name = '{$request->user_name}' else 1 END");

        $sQuery = Datatables::of($report_bonus_2024_easy);

        return $sQuery
            ->addIndexColumn() // เพิ่มดัชนีแถวที่นี่
            ->addColumn('date_action', function ($row) {
                return date('d/m/Y', strtotime($row->date_action));
            })
            ->rawColumns(['date_action']) // เปลี่ยนจาก 'active_date' เป็น 'date_action'
            ->make(true);
    }

    public function  bonus_es_excel(Request $request)
    {
        $report_bonus_2024_easy = DB::table('report_bonus_2024_easy')
            ->whereBetween('date_action', [$request->s_date, $request->e_date])
            ->get();

        $logArray[] = [
            'id',
            'user_name',
            'qualification',
            'expire_date',
            'code_order',
            'buy_user_name',
            'buy_qualification',
            'pv',
            'percen',
            'tax_percen',
            'tax_total',
            'bonus_full',
            'bonus',
            'remark_transfer',
            'date_action',
            'status',
            'created_at',
            'updated_at',
            'deleted_at'
        ];

        foreach ($report_bonus_2024_easy as $log) {
            $logArray[] = (array) $log;
        }

        return Excel::download(new LogPvPerDayExport($logArray), 'report_bonus_easy.xlsx');
    }

    public function report_pv_per_day_ab_balance_datable(Request $request)
    {
        $log_pv_per_day = DB::table('report_pv_per_day_ab_balance')
            ->whereBetween('date_action', [$request->s_date, $request->e_date])
            ->whereRaw("case WHEN '{$request->user_name}' != '' THEN report_pv_per_day_ab_balance.user_name = '{$request->user_name}' else 1 END");

        $sQuery = Datatables::of($log_pv_per_day);

        return $sQuery
            ->addIndexColumn() // เพิ่มดัชนีแถวที่นี่
            ->addColumn('date_action', function ($row) {
                return date('d/m/Y', strtotime($row->date_action));
            })
            ->rawColumns(['date_action']) // เปลี่ยนจาก 'active_date' เป็น 'date_action'
            ->make(true);
    }

    public function report_pv_per_day_ab_balance_bonus7_datable(Request $request)
    {
        $log_pv_per_day = DB::table('report_pv_per_day_ab_balance_bonus7')
            ->whereBetween('date_action', [$request->s_date, $request->e_date])
            ->whereRaw("case WHEN '{$request->user_name}' != '' THEN report_pv_per_day_ab_balance_bonus7.user_name = '{$request->user_name}' else 1 END");

        $sQuery = Datatables::of($log_pv_per_day);

        return $sQuery
            ->addIndexColumn() // เพิ่มดัชนีแถวที่นี่
            ->addColumn('date_action', function ($row) {
                return date('d/m/Y', strtotime($row->date_action));
            })
            ->rawColumns(['date_action']) // เปลี่ยนจาก 'active_date' เป็น 'date_action'
            ->make(true);
    }

    public function log_pv_per_day_datable(Request $request)
    {
        $log_pv_per_day = DB::table('log_pv_per_day')
            ->whereBetween('date_action', [$request->s_date, $request->e_date])
            ->whereRaw("case WHEN '{$request->user_name}' != '' THEN log_pv_per_day.user_name = '{$request->user_name}' else 1 END");

        $sQuery = Datatables::of($log_pv_per_day);

        return $sQuery
            ->addIndexColumn() // เพิ่มดัชนีแถวที่นี่
            ->addColumn('date_action', function ($row) {
                return date('d/m/Y', strtotime($row->date_action));
            })
            ->rawColumns(['date_action']) // เปลี่ยนจาก 'active_date' เป็น 'date_action'
            ->make(true);
    }

    public function log_pv_per_day_ab_balance_all_datable(Request $request)
    {
        $log_pv_per_day = DB::table('log_pv_per_day_ab_balance_all')
            ->whereBetween('date_action', [$request->s_date, $request->e_date])
            ->whereRaw("case WHEN '{$request->user_name}' != '' THEN log_pv_per_day_ab_balance_all.user_name = '{$request->user_name}' else 1 END")
            ->orderby('id');
        $sQuery = Datatables::of($log_pv_per_day);
        return $sQuery
            ->addIndexColumn() // เพิ่มดัชนีแถวที่นี่
            ->addColumn('date_action', function ($row) {
                return date('d/m/Y', strtotime($row->date_action));
            })
            ->rawColumns(['date_action']) // เปลี่ยนจาก 'active_date' เป็น 'date_action'
            ->make(true);
    }
}
