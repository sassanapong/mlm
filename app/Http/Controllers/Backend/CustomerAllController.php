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
            ->whereRaw(("case WHEN  '{$request->id_card}' != ''  THEN  customers.id_card = '{$request->id_card}' else 1 END"))
            ->whereRaw(("case WHEN  '{$request->phone}' != ''  THEN  customers.phone = '{$request->phone}' else 1 END"))
            ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' = ''  THEN  date(customers.created_at) = '{$request->s_date}' else 1 END"))
            ->whereRaw(("case WHEN '{$request->s_date}' != '' and '{$request->e_date}' != ''  THEN  date(customers.created_at) >= '{$request->s_date}' and date(customers.created_at) <= '{$request->e_date}'else 1 END"))
            ->whereRaw(("case WHEN '{$request->s_date}' = '' and '{$request->e_date}' != ''  THEN  date(customers.created_at) = '{$request->e_date}' else 1 END"))
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

            ->addColumn('qualification_id', function ($row) { //วันที่สมัคร
                $dataset_qualification = DB::table('dataset_qualification')
                    ->where('code', $row->qualification_id)
                    ->first();

                if ($dataset_qualification) {
                    return $dataset_qualification->business_qualifications;
                } else {
                    return '-';
                }
            })
            ->addColumn('expire_date', function ($row) {
                if ($row->expire_date) {
                    return date('Y/m/d', strtotime($row->expire_date));
                } else {
                    return '';
                }
            })

            ->editColumn('status_customer', function ($query) {
                $text = 'ไม่พบเงื่อนไข';
                //'cancel','normal'
                if ($query->status_customer == 'normal') {
                    $text = '<span class="text-success">เปิดใช้งาน</span>';
                }
                if ($query->status_customer == 'cancel') {
                    $text = '<span class="text-danger">ยกเลิก</span>';
                }
                return $text;
            })

            ->addColumn('created_at', function ($row) {
                if ($row->created_at) {
                    return date('Y/m/d', strtotime($row->created_at));
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

            // ->addColumn('bonus_xvvip', function ($row) {
            //     $bonus_xvvip = \App\Http\Controllers\Frontend\FC\PvUpPositionXvvipController::get_pv_upgrade($row->user_name); //โบนัสสร้างทีม XVVIP


            //     return number_format($bonus_xvvip);
            // })


            ->addColumn('action', function ($row) {

                $name = @$row->name . ' ' . @$row->last_name . ' (' . $row->user_name . ')';
                $url = url('admin/info_customer/' . $row->id);

                $cancel_btn = '';

                // ถ้ายังไม่ถูกยกเลิก ให้แสดงปุ่ม
                if ($row->status_customer != 'cancel') {
                    $cancel_btn = '
            <li>
                <a href="javascript:;" 
                   class="dropdown-item text-danger cancel-user"
                   data-user="' . $row->user_name . '"
                   data-name="' . $name . '"
                   data-tw-toggle="modal"
                   data-tw-target="#cancel_user_modal">
                    ยกเลิกรหัส
                </a>
            </li>
        ';
                }

                $html = '
        <div class="dropdown">
            <button class="dropdown-toggle btn btn-primary" aria-expanded="false" data-tw-toggle="dropdown">
                แก้ไข
            </button>
            <div class="dropdown-menu w-40">
                <ul class="dropdown-content">

                    <li>
                        <a href="javascript:;" 
                           data-tw-toggle="modal" 
                           data-tw-target="#edit_position"
                           onclick="modal_logtranfer(\'' . $row->user_name . '\', \'' . $name . '\')" 
                           class="dropdown-item">
                            ปรับตำแหน่ง
                        </a>
                    </li>

                    <li>
                        <a href="' . $url . '" class="dropdown-item">
                            แก้ไขข้อมูลส่วนตัว
                        </a>
                    </li>

                    ' . $cancel_btn . '

                </ul>
            </div>
        </div>
    ';

                return $html;
            })


            ->rawColumns(['status_customer', 'action',])

            ->make(true);
    }

    public function update_position(Request $request)
    {
        // dd($request->all());

        $user_action = DB::table('customers')
            ->select('id', 'user_name', 'pv_upgrad', 'name', 'last_name', 'qualification_id', 'introduce_id')
            ->where('user_name', '=', $request->user_name_upgrad)
            ->first();
        if (empty($user_action)) {
            return redirect('admin/CustomerAll')->withError('ไม่พบผู้ใช้งาน กรุณาทำรายการไหม่');
        }

        if ($user_action->qualification_id == $request->position) {
            return redirect('admin/CustomerAll')->withError('สมาชิกเป็น ' . $request->position . ' อยู่แล้วไม่สามารถปรับตำแหน่งได้');
        }

        try {
            $new_lavel = DB::table('dataset_qualification')
                ->where('code', $request->position)
                ->first();

            $old_lavel = DB::table('dataset_qualification')
                ->where('code', $user_action->qualification_id)
                ->first();

            DB::BeginTransaction();

            DB::table('log_up_vl')->insert([
                'user_name' => $user_action->user_name,
                'introduce_id' => $user_action->introduce_id,
                'old_lavel' => $old_lavel->code,
                'new_lavel' => $new_lavel->code,
                'pv_upgrad' => $request->pv,
                'status' => 'success',
                'type' => 'jangpv',
                'note' => 'ปรับตำแหน่งโดย Admin'
            ]);

            DB::table('customers')
                ->where('user_name', $user_action->user_name)
                ->update(['qualification_id' => $new_lavel->code, 'pv_upgrad' => $request->pv]);
            DB::commit();
            return redirect('admin/CustomerAll')->withSuccess('ปรับตำแหน่งสำเร็จ');
        } catch (Exception $e) {
            DB::rollback();
            return redirect('admin/CustomerAll')->withError('ผิดพลาดกรุณาทำรายการไหม่อีกครั้ง');
        }
    }

    public function cancelUser(Request $request)
    {

        DB::table('customers')
            ->where('user_name', $request->user)
            ->update([
                'status_customer' => 'cancel',
                'cancel_status_date' => date('Y-m-d H:i:s'),
                'expire_date' => null,
                'expire_date_bonus_balance' => null,
                'expire_date_bonus' => null,
            ]);

        return response()->json([
            'status' => 'success'
        ]);
    }
}
