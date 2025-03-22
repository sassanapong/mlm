<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;
use Carbon\Carbon;
use Auth;

class WorklineController extends Controller
{
    public function index($user_name = '', $lv = '')
    {
        if (empty($lv)) {
            $lv = 1;
        }

        if ($lv > 3) {
            return redirect('Workline')->withError('ไม่สามารถดูสายงานมากกว่า 3 ชั้นได้');
        }

        return view('frontend/workline', compact('user_name', 'lv'));
    }

    public function datatable(Request $rs)
    {
        // $s_date = !empty($rs->s_date) ? date('Y-m-d', strtotime($rs->s_date)) : date('Y-01-01');
        // $e_date = !empty($rs->e_date) ? date('Y-m-d', strtotime($rs->e_date)) : date('Y-12-t');

        // $date_between = [$s_date, $e_date];
        if ($rs->user_name) {
            $user_name = $rs->user_name;
        } else {
            $user_name = Auth::guard('c_user')->user()->user_name;
        }

        $introduce = DB::table('customers')
            ->select('customers.*')
            ->where('introduce_id', '=', $user_name)
            ->where('name', '!=', '')
            ->whereRaw("CASE WHEN '{$rs->slv}' != '' THEN customers.qualification_id = '{$rs->slv}' ELSE 1 END")
            ->whereRaw("CASE WHEN '{$rs->susername}' != '' THEN customers.user_name LIKE '%{$rs->susername}%' ELSE 1 END");

        if ($rs->ex_date == 2) {
            $introduce->where(function ($query) {
                $query->whereNull('expire_date')
                    ->orWhere('expire_date', '<', date('Y-m-d'));
            });
        } elseif ($rs->ex_date == 1) {
            $introduce->where(function ($query) {
                $query->whereNotNull('expire_date')
                    ->where('expire_date', '>', date('Y-m-d'));
            });
        }

        $sQuery = Datatables::of($introduce);
        return $sQuery

            ->addColumn('status_active', function ($row) { //การรักษาสภำพ
                if (empty($row->qualification_id)) {
                    $resule = '<i class="fas fa-circle text-warning"></i>';
                    return $resule;
                }
                if (
                    empty($row->expire_date) || strtotime($row->expire_date) < strtotime(date('Y-m-d')) and
                    empty($row->expire_date_bonus) || strtotime($row->expire_date_bonus) < strtotime(date('Y-m-d'))
                ) {
                    return '<i class="fas fa-circle text-danger"></i>';
                }

                return '<i class="fas fa-circle text-success"></i>';
            })




            ->addColumn('created_at', function ($row) { //วันที่สมัคร
                if ($row->created_at == '0000-00-00 00:00:00') {
                    return '-';
                } else {
                    return date('Y/m/d', strtotime($row->created_at));
                }
            })


            ->addColumn('phone', function ($row) { //วันที่สมัคร
                $user_name = Auth::guard('c_user')->user()->user_name;
                if ($row->introduce_id == $user_name) {
                    return $row->phone;
                } else {
                    return '';
                }
            })


            ->addColumn('introduce_name', function ($row) {
                $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->introduce_id);
                if ($upline) {
                    $html = @$upline->name . ' ' . @$upline->last_name;
                } else {
                    $html = '-';
                }

                return $html;
            })

            ->addColumn('expire_date', function ($row) {
                if (empty($row->expire_date)) {
                    return  0;
                }

                if (strtotime($row->expire_date) < strtotime(date('Ymd'))) {
                    //$html= Carbon::now()->diffInDays($row->expire_date);
                    return  0;
                } else {

                    $html = Carbon::now()->diffInDays($row->expire_date);
                    if ($html == 0) {
                        return  1;
                    } else {
                        return $html;
                    }
                }

                if ($row->expire_date) {
                    $html = Carbon::now()->diffInDays($row->expire_date . ' 00:00:00');
                    return  $html;
                } else {
                    return  '-';
                }
            })

            ->addColumn('expire_date_bonus', function ($row) {
                if (empty($row->expire_date_bonus)) {
                    return  0;
                }

                if (strtotime($row->expire_date_bonus) < strtotime(date('Ymd'))) {
                    //$html= Carbon::now()->diffInDays($row->expire_date_bonus);
                    return  0;
                } else {

                    $html = Carbon::now()->diffInDays($row->expire_date_bonus);
                    if ($html == 0) {
                        return  1;
                    } else {
                        return $html;
                    }
                }

                if ($row->expire_date_bonus) {
                    $html = Carbon::now()->diffInDays($row->expire_date_bonus . ' 00:00:00');
                    return  $html;
                } else {
                    return  '-';
                }
            })



            ->addColumn('sponsor_lv', function ($row) use ($rs) {
                $html = "ชั้น $rs->lv";
                return  $html;
            })


            ->addColumn('view', function ($row) use ($rs) {


                $count = DB::table('customers')
                    ->select('customers.*')
                    ->where('introduce_id', '=', $row->user_name)
                    ->where('name', '!=', '')
                    ->count();
                $lv = $rs->lv + 1;
                if ($count > 0 and $rs->lv < 3) {
                    $html = $count . ' <a type="button" target="_blank" class="btn btn-info btn-sm" href="' . route('Workline', ['user_name' => $row->user_name, 'lv' => $lv]) . '">
                    <i class="fa fa-sitemap"></i></a>';
                    return $html;
                } else {
                    return '-';
                }
            })

            ->addColumn('action', function ($row) {
                //     $html = '<button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#discountModal">
                //     <i class="bx bx-link-external"></i>
                // </button>';
                $html = '<div class="btn-group">
            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bx bx-link-external"></i>
            </button>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" data-bs-toggle="modal" href="#addTransferJPModal" role="button">โอน</a></li>
              <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#confirmModal">ยืนยันสิทธิ์</a></li>
              <li><a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#discountModal">รับส่วนลด</a></li>


            </ul>
          </div>';

                return '-';
            })


            ->rawColumns(['status_active', 'view', 'action'])
            ->make(true);
    }
}
