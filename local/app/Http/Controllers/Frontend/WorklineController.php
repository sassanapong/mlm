<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Auth;

class WorklineController extends Controller
{
    public function index()
    {

        return view('frontend/workline');
    }

    public function datatable(Request $rs)
    {
        $s_date = !empty($rs->s_date) ? date('Y-m-d', strtotime($rs->s_date)) : date('Y-01-01');
        $e_date = !empty($rs->e_date) ? date('Y-m-d', strtotime($rs->e_date)) : date('Y-12-t');

        $date_between = [$s_date, $e_date];

        $introduce = DB::table('customers')
            ->select('customers.*')
            ->where('introduce_id', '=', Auth::guard('c_user')->user()->user_name)
            ->where('name', '!=','');
            // ->when($date_between, function ($query, $date_between) {
            //     return $query->whereBetween('created_at', $date_between);
            // });

        $sQuery = Datatables::of($introduce);
        return $sQuery

            ->addColumn('status_active', function ($row) { //การรักษาสภำพ
                return '';
            })
            ->addColumn('created_at', function ($row) { //วันที่สมัคร

                return date('Y/m/d', strtotime($row->created_at));
            })

            ->addColumn('introduce_name', function ($row) {
                $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->introduce_id);
                if($upline){
                    $html = @$upline->name.' '.@$upline->last_name;

                }else{
                    $html = '-';

                }

                return $html;
            })

            ->addColumn('sponsor_lv', function ($row) {
                $html = 'ชั้น 1';
                return  $html;
            })

            ->addColumn('action_tranfer', function ($row) {
            //     $html = '<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" href="#addTransferJPModal" role="button">
            //     <i class="bx bx-link-external"></i>
            // </button>';
               $html ='-';
                return $html;
            })
            ->addColumn('action_confirm', function ($row) {
            //     $html = '<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#confirmModal">
            //     <i class="bx bx-link-external"></i>
            // </button>';
               $html ='-';
                return $html;
            })

            ->addColumn('action_discount', function ($row) {
            //     $html = '<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#discountModal">
            //     <i class="bx bx-link-external"></i>
            // </button>';
            $html ='-';
                return $html;
            })


            ->rawColumns(['action_tranfer', 'action_confirm','action_discount'])
            ->make(true);
    }
}
