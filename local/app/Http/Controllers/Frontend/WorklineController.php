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
    public function index($user_name='',$lv='')
    {
        if(empty($lv)){
            $lv = 1;
        }

        if($lv>3){
            return redirect('Workline')->withError('ไม่สามารถดูสายงานมากกว่า 3 ชั้นได้');
        }

        return view('frontend/workline',compact('user_name','lv'));
    }

    public function datatable(Request $rs)
    {
        $s_date = !empty($rs->s_date) ? date('Y-m-d', strtotime($rs->s_date)) : date('Y-01-01');
        $e_date = !empty($rs->e_date) ? date('Y-m-d', strtotime($rs->e_date)) : date('Y-12-t');

        $date_between = [$s_date, $e_date];
        if($rs->user_name){
            $user_name = $rs->user_name;
        }else{
            $user_name = Auth::guard('c_user')->user()->user_name;
        }


        $introduce = DB::table('customers')
            ->select('customers.*')
            ->where('introduce_id', '=',$user_name)
            ->where('name', '!=','');
            // ->when($date_between, function ($query, $date_between) {
            //     return $query->whereBetween('created_at', $date_between);
            // });

        $sQuery = Datatables::of($introduce);
        return $sQuery

            ->addColumn('status_active', function ($row) { //การรักษาสภำพ
                if(empty($row->qualification_id)){
                    $resule ='<i class="fas fa-circle text-warning"></i>';
                    return $resule;
                }

                if(empty($row->expire_date) || (strtotime($row->expire_date) < strtotime(date('Ymd')))){

                    $date_tv_active= date('d/m/Y',strtotime($row->expire_date));
                    $resule ='<i class="fas fa-circle text-danger"></i>';
                    return $resule;
                }else{
                    $date_tv_active= date('d/m/Y',strtotime($row->expire_date));
                    $resule ='<i class="fas fa-circle text-success"></i>';
                    return $resule;

                }
            })
            ->addColumn('created_at', function ($row) { //วันที่สมัคร
                if($row->created_at == '0000-00-00 00:00:00'){
                    return '-';
                }else{
                    return date('Y/m/d', strtotime($row->created_at));
                }

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

            ->addColumn('expire_date', function ($row) {
                if(empty($row->expire_date)) {
                    return  0;
                }

                if(strtotime($row->expire_date) < strtotime(date('Ymd')) ){
                    //$html= Carbon::now()->diffInDays($row->expire_date);
                    return  0;
                }else{

                    $html= Carbon::now()->diffInDays($row->expire_date);
                    return $html;

                }

                if($row->expire_date){
                    $html= Carbon::now()->diffInDays($row->expire_date.' 00:00:00' );
                    return  $html;
                }else{
                    return  '-';
                }

            })


            ->addColumn('sponsor_lv', function ($row) use ($rs) {
                $html = "ชั้น $rs->lv";
                return  $html;
            })


            ->addColumn('view',function($row) use ($rs)  {


                $count = DB::table('customers')
                    ->select('customers.*')
                    ->where('introduce_id', '=',$row->user_name)
                    ->where('name', '!=','')
                    ->count();
                    $lv = $rs->lv+1;
                if($count > 0 and $rs->lv < 3){  $html = $count.' <a type="button" target="_blank" class="btn btn-info btn-sm" href="'.route('Workline',['user_name'=>$row->user_name,'lv'=>$lv]).'">
                    <i class="fa fa-sitemap"></i></a>';
                    return $html;
                }else{
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


            ->rawColumns(['status_active','view','action'])
            ->make(true);
    }
}
