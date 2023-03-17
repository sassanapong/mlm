<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use DataTables;


class CustomerAllController extends Controller
{


    public function index()
    {
        $position = DB::table('dataset_qualification')
            ->get();
        return view('backend/CustomerAll/index', compact('position'));
    }

    public function customer_all_datable(Request $request)
    {
        $business_location_id = 1;
        $jang_pv = DB::table('customers')
            // ->select('jang_pv.*','jang_type.type as type_name')
            // ->leftjoin('jang_type', 'jang_pv.type', '=', 'jang_type.id')
            ->whereRaw(("case WHEN  '{$request->user_name}' != ''  THEN  customers.user_name = '{$request->user_name}' else 1 END"))
            ->whereRaw(("case WHEN  '{$request->position}' != ''  THEN  customers.qualification_id = '{$request->position}' else 1 END"))
            ->orderby('id', 'DESC');

        $sQuery = Datatables::of($jang_pv);
        return $sQuery


            ->addColumn('user_name', function ($row) {
                return $row->user_name;
            })

            ->addColumn('name', function ($row) {
                $html = @$row->name . ' ' . @$row->last_name . ' (' . $row->user_name . ')';
                return $html;
            })

            ->addColumn('expire_date', function ($row) {
                if ($row->expire_date) {
                    return date('Y/m/d', strtotime($row->expire_date));
                } else {
                    return '';
                }
            })

            ->addColumn('introduce_id', function ($row) {
                $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->introduce_id);
                if ($upline) {
                    $html = @$upline->name . ' ' . @$upline->last_name . ' (' . $upline->user_name . ')';
                } else {
                    $html = '-';
                }

                return $html;
            })

            ->addColumn('bonus_total', function ($row) {

                return number_format($row->bonus_total);
            })

            ->addColumn('pv_upgrad', function ($row) {
                return number_format($row->pv_upgrad);
            })

            ->addColumn('bonus_xvvip', function ($row) {
                $bonus_xvvip = \App\Http\Controllers\Frontend\FC\PvUpPositionXvvipController::get_pv_upgrade($row->user_name); //โบนัสสร้างทีม XVVIP


                return number_format($bonus_xvvip);
            })





            ->addColumn('action', function ($row) {
                $name = @$row->name . ' ' . @$row->last_name . ' (' . $row->user_name . ')';
                $html = '
                        <div class="dropdown">
                            <button class="dropdown-toggle btn btn-primary" aria-expanded="false" data-tw-toggle="dropdown">
                                แก้ไข
                            </button>
                            <div class="dropdown-menu w-40">
                                <ul class="dropdown-content">
                                    <li>

                                        <a href="javascript:;" data-tw-toggle="modal" data-tw-target="#basic-modal-preview"  onclick="modal_logtranfer(\'' . $row->user_name . '\', \'' . $name . '\')" class="dropdown-item">
                                            ปรับตำแหน่ง
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    ';
                return  $html;
            })


            //->rawColumns(['detail', 'pv_total', 'date', 'code_order','tracking'])

            ->make(true);
    }

    public function update_position(Request $request)
    {

        $user_action = DB::table('customers')
        ->select( 'id', 'user_name','pv_upgrad', 'name', 'last_name','qualification_id','introduce_id')
        ->where('user_name','=',$request->user_name_upgrad)
        ->first();
        if(empty($user_action)){
            return redirect('admin/CustomerAll')->withError('ไม่พบผู้ใช้งาน กรุณาทำรายการไหม่');
        }

        if($user_action->qualification_id == $request->position){
            return redirect('admin/CustomerAll')->withError('สมาชิกเป็น '.$request->position.' อยู่แล้วไม่สามารถปรับตำแหน่งได้');
        }

        try {


            DB::BeginTransaction();

            DB::table('log_up_vl')->insert([
                'user_name' => $user_action->user_name,'introduce_id' => $user_action->introduce_id,
                'old_lavel' => $user_action->qualification_id, 'new_lavel' =>$request->position,'pv_upgrad'=>$request->pv, 'status' => 'success', 'type' => 'jangpv','note'=>'ปรับตำแหน่งโดย Admin'
            ]);

            DB::table('customers')
            ->where('user_name', $user_action->user_name)
            ->update(['qualification_id' => $request->position, 'pv_upgrad' => $request->pv]);
            DB::commit();

            return redirect('admin/CustomerAll')->withSuccess('ปรับตำแหน่งสำเร็จ');

    } catch (Exception $e) {
         DB::rollback();
         return redirect('admin/CustomerAll')->withError('ผิดพลาดกรุณาทำรายการไหม่อีกครั้ง');
    }

    }
}
