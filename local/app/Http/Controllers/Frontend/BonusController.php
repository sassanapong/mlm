<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Auth;

class BonusController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }
    public function bonus_all()
    {
        return view('frontend/bonus-all');
    }
    public function bonusws()
    {
        return view('frontend/bonus-ws');
    }

    public function reportsws()
    {
        return view('frontend/report-ws');
    }

    public function reportsws_datatable(Request $rs)
    {

        $s_date = !empty($rs->startDate) ? date('Y-m-d', strtotime($rs->startDate)) : date('Y-01-01');
        $e_date = !empty($rs->endDate) ? date('Y-m-d', strtotime($rs->endDate)) : date('Y-12-t');
        $date_between = [$s_date, $e_date];

        if ($rs->user_name) {
            $user_name = $rs->user_name;
        } else {
            $user_name = Auth::guard('c_user')->user()->user_name;
        }


        $log_pv_per_day_ab_balance_all = DB::table('log_pv_per_day_ab_balance_all')
            ->where('user_name', $user_name)
            ->when($date_between, function ($query, $date_between) {
                return $query->whereBetween('date_action', $date_between);
            })
            ->OrderbyDESC('date_action');

        $sQuery = Datatables::of($log_pv_per_day_ab_balance_all);
        return $sQuery

            ->addColumn('date_action', function ($row) { //วันที่สมัคร
                if ($row->date_action == '0000-00-00') {
                    return '-';
                } else {
                    return date('Y/m/d', strtotime($row->date_action));
                }
            })

            ->addColumn('date_action', function ($row) { //วันที่สมัคร
                if ($row->date_action == '0000-00-00') {
                    return '-';
                } else {
                    return date('Y/m/d', strtotime($row->date_action));
                }
            })


            ->addColumn('pv_today', function ($row) { //วันที่สมัคร
                return number_format($row->pv_today);
            })


            ->addColumn('pv_a_new', function ($row) { //วันที่สมัคร
                return number_format($row->pv_a_new);
            })
            ->addColumn('pv_b_new', function ($row) { //วันที่สมัคร
                return number_format($row->pv_b_new);
            })

            ->addColumn('balance', function ($row) { //วันที่สมัคร
                return number_format($row->balance);
            })

            ->rawColumns(['action'])
            ->make(true);
    }
    public function bonusws_datatable(Request $rs)
    {

        $s_date = !empty($rs->startDate) ? date('Y-m-d', strtotime($rs->startDate)) : date('Y-01-01');
        $e_date = !empty($rs->endDate) ? date('Y-m-d', strtotime($rs->endDate)) : date('Y-12-t');
        $date_between = [$s_date, $e_date];

        if ($rs->user_name) {
            $user_name = $rs->user_name;
        } else {
            $user_name = Auth::guard('c_user')->user()->user_name;
        }


        $log_pv_per_day_ab_balance_all = DB::table('log_pv_per_day_ab_balance_all')
            ->where('user_name', $user_name)
            ->when($date_between, function ($query, $date_between) {
                return $query->whereBetween('date_action', $date_between);
            })
            ->OrderbyDESC('date_action');

        $sQuery = Datatables::of($log_pv_per_day_ab_balance_all);
        return $sQuery

            ->addColumn('date_action', function ($row) { //วันที่สมัคร
                if ($row->date_action == '0000-00-00') {
                    return '-';
                } else {
                    return date('Y/m/d', strtotime($row->date_action));
                }
            })

            ->addColumn('date_action', function ($row) { //วันที่สมัคร
                if ($row->date_action == '0000-00-00') {
                    return '-';
                } else {
                    return date('Y/m/d', strtotime($row->date_action));
                }
            })


            ->addColumn('pv_today', function ($row) { //วันที่สมัคร
                return number_format($row->pv_today);
            })


            ->addColumn('pv_a_new', function ($row) { //วันที่สมัคร
                return number_format($row->pv_a_new);
            })
            ->addColumn('pv_b_new', function ($row) { //วันที่สมัคร
                return number_format($row->pv_b_new);
            })

            ->addColumn('balance', function ($row) { //วันที่สมัคร
                return number_format($row->balance);
            })

            ->rawColumns(['action'])
            ->make(true);
    }

    public function bonus_fastStart()
    {
        return view('frontend/bonus-fastStart');
    }

    public function bonus_team()
    {
        return view('frontend/bonus-team');
    }


    public function bonus_discount()
    {
        return view('frontend/bonus-discount');
    }

    public function bonus_matching()
    {
        return view('frontend/bonus-matching');
    }

    public function bonus_history()
    {
        return view('frontend/bonus-history');
    }
}
