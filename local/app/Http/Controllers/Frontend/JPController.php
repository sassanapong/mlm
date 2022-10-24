<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customers;
use App\Jang_pv;
use DB;
use DataTables;
use Auth;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class JPController extends Controller
{
    public function jp_clarify()
    {
        $data = DB::table('dataset_qualification')
            ->where('code', Auth::guard('c_user')->user()->qualification_id)
            ->first();
        $pv_to_price = 1 * $data->bonus_jang_pv / 100;
        $data = ['pv_to_price' => $pv_to_price];

        return view('frontend/jp-clarify', compact('data'));
    }




    public function jp_transfer()
    {
        return view('frontend/jp-transfer');
    }

    public function jang_pv(Request $rs)
    {

        if ($rs->type == 2) {
            if ($rs->pv <= 0) {
                return redirect('jang_pv')->withError('ไม่สามารถแจง 0 PV ได้');
            }

            $user = DB::table('customers')
                // ->select('upline_id', 'user_name', 'name', 'last_name')
                ->where('user_name', '=', $rs->user_name)
                ->first();

            if (empty($user)) {
                return redirect('jang_pv')->withError('ไม่มี User ' . $rs->user_name . 'ในระบบ');
            }

            $customer_update = Customers::find($user->id);
            $jang_pv = new Jang_pv();
            $y = date('Y') + 543;
            $y = substr($y, -2);
            $code =  IdGenerator::generate([
                'table' => 'jang_pv',
                'field' => 'code',
                'length' => 15,
                'prefix' => 'PV' . $y . '' . date("m") . '-',
                'reset_on_prefix_change' => true
            ]);
            $jang_pv->code = $code;
            $jang_pv->customer_username = $rs->user_name;
            $jang_pv->to_customer_username = $rs->user_name;
            $jang_pv->position = $user->qualification_id;

            $bonus_percen = DB::table('dataset_qualification')
                ->where('code', $user->qualification_id)
                ->first();

            $jang_pv->bonus_percen = $bonus_percen->bonus_jang_pv;
            $jang_pv->pv_old = $user->pv;
            $jang_pv->pv = $rs->pv;
            $pv_balance = $user->pv - $rs->pv;
            $jang_pv->pv_balance =  $pv_balance;
            $pv_to_price =  $rs->pv * $bonus_percen->bonus_jang_pv / 100;
            $jang_pv->wallet =  $pv_to_price;
            if (empty($user->ewallet)) {
                $ewallet_user = 0;
            } else {
                $ewallet_user = $user->ewallet;
            }
            $jang_pv->old_wallet =  $ewallet_user;
            $jang_pv->wallet_balance =  $ewallet_user + $pv_to_price;
            $jang_pv->note_orther =  '';
            $jang_pv->type =  '2';
            $jang_pv->status =  'Success';
            $customer_update->pv = $pv_balance;
            $customer_update->ewallet = $ewallet_user + $pv_to_price;

            try {
                DB::BeginTransaction();
                $customer_update->save();
                $jang_pv->save();
                DB::commit();
                return redirect('jp_clarify')->withSuccess('เแจง PV สำเร็จ');
            } catch (Exception $e) {
                DB::rollback();
                return redirect('jp_clarify')->withError('เแจง PV ไม่สำเร็จกรุณาทำรายการไหม่อีกครั้ง');
            }
        } else {
            return redirect('jp_clarify')->withError('เงื่อนไขการแจง PV ไม่ถูกต้อง');
        }
    }

    public function datatable(Request $rs)
    {
        $s_date = !empty($rs->s_date) ? date('Y-m-d', strtotime($rs->s_date)) : date('Y-01-01');
        $e_date = !empty($rs->e_date) ? date('Y-m-d', strtotime($rs->e_date)) : date('Y-12-t');

        $date_between = [$s_date, $e_date];

        $user_name = Auth::guard('c_user')->user()->user_name;

        $introduce = DB::table('jang_pv')
            ->where('customer_username', '=', $user_name);
        // ->when($date_between, function ($query, $date_between) {
        //     return $query->whereBetween('created_at', $date_between);
        // });

        $sQuery = Datatables::of($introduce);
        return $sQuery

            ->addColumn('created_at', function ($row) { //วันที่สมัคร
                if ($row->created_at == '0000-00-00 00:00:00') {
                    return '-';
                } else {
                    return date('Y/m/d', strtotime($row->created_at));
                }
            })
            ->addColumn('type', function ($row) { //การรักษาสภำพ

                $resule = 'แจงรับส่วนลด';
                return $resule;
            })


            ->addColumn('name', function ($row) {
                $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->customer_username);
                if ($upline) {
                    $html = @$upline->name . ' ' . @$upline->last_name . '(' . $row->customer_username . ')';
                } else {
                    $html = '-';
                }

                return $html;
            })

            ->addColumn('qualification_id', function ($row) {
                if (empty($row->position)) {
                    return  '-';
                } else {
                    return $row->position;
                }
            })


            ->addColumn('pv', function ($row) {
                $html = number_format($row->pv);
                return  $html;
            })

            ->addColumn('pv_balance', function ($row) {
                $html = number_format($row->pv_balance);
                return  $html;
            })

            ->addColumn('status', function ($row) {
                $html =  $row->status;
                return  $html;
            })


            ->rawColumns(['status_active', 'view', 'action'])
            ->make(true);
    }
}
