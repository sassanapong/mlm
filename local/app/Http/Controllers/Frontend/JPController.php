<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customers;
use App\Jang_pv;
use DB;
use DataTables;
use Auth;
use App\eWallet;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class JPController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }
    public function jp_clarify()
    {

        // $data = \App\Http\Controllers\Frontend\ฺBonusCashBackController::RunBonusCashBack();
        // dd($data);


        $data = DB::table('dataset_qualification')
            ->where('code', Auth::guard('c_user')->user()->qualification_id)
            ->first();
        $pv_to_price = 1 * $data->bonus_jang_pv / 100;
        $data = ['pv_to_price' => $pv_to_price, 'rs' => $data];

        return view('frontend/jp-clarify', compact('data'));
    }


    public function jp_transfer()
    {
        return view('frontend/jp-transfer');
    }

    public function jang_pv_cash_back(Request $rs)
    {
        // sleep(1);
        // usleep(2500000);
        if ($rs->type == 2) {
            if ($rs->pv <= 0) {
                return redirect('jp_clarify')->withError('ไม่สามารถแจง 0 PV ได้');
            }

            $user = DB::table('customers')
                // ->select('upline_id', 'user_name', 'name', 'last_name')
                ->where('user_name', '=', $rs->user_name)
                ->first();


            if ($rs->pv > $user->pv) {
                return redirect('jp_clarify')->withError('PV ไม่พอสำหรับการแจง ');
            }


            if (empty($user)) {
                return redirect('jp_clarify')->withError('ไม่มี User ' . $rs->user_name . 'ในระบบ');
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

            $jang_pv->customer_username = Auth::guard('c_user')->user()->user_name;
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
            $pv_to_price_tax = $pv_to_price - ($pv_to_price *3/100);
            $jang_pv->wallet =  $pv_to_price_tax;
            if (empty($user->ewallet)) {
                $ewallet_user = 0;
            } else {
                $ewallet_user = $user->ewallet;
            }

            if ($customer_update->ewallet_use == '' || empty($customer_update->ewallet_use)) {
                $ewallet_use = 0;
            } else {

                $ewallet_use = $customer_update->ewallet_use;
            }


            $jang_pv->old_wallet =  $ewallet_user;
            $wallet_balance = $ewallet_user + $pv_to_price_tax;
            $jang_pv->wallet_balance =   $wallet_balance;
            $jang_pv->note_orther =  '';
            $jang_pv->type =  '2';
            $jang_pv->status =  'Success';
            $customer_update->pv = $pv_balance;
            $customer_update->ewallet = $ewallet_user + $pv_to_price_tax;
            $customer_update->ewallet_use = $ewallet_use + $pv_to_price_tax;

            $eWallet = new eWallet();
            $eWallet->transaction_code = $code;
            $eWallet->customers_id_fk = Auth::guard('c_user')->user()->id;
            $eWallet->customer_username = Auth::guard('c_user')->user()->user_name;
            $eWallet->customers_id_receive = $user->id;
            $eWallet->customers_name_receive = $user->user_name;
            $eWallet->tax_total = $pv_to_price * 3/100;
            $eWallet->bonus_full =$pv_to_price;
            $eWallet->amt = $pv_to_price_tax;
            $eWallet->old_balance = $ewallet_user;
            $eWallet->balance = $wallet_balance;
            $eWallet->type = 5;
            $eWallet->receive_date = now();
            $eWallet->receive_time = now();
            $eWallet->status = 2;


            try {
                DB::BeginTransaction();
                $check_jang_pv = DB::table('jang_pv')
                    ->where('code', '=', $code)
                    ->first();
                if ($check_jang_pv) {
                    DB::rollback();
                    return redirect('jp_clarify')->withError('แจง PV ไม่สำเร็จกรุณาทำรายการไหม่อีกครั้ง');
                }
                $customer_update->save();
                $jang_pv->save();
                $eWallet->save();
                $RunBonusCashBack = \App\Http\Controllers\Frontend\BonusCashBackController::RunBonusCashBack($code);
                if ($RunBonusCashBack == true) {
                    $report_bonus_cashback = DB::table('report_bonus_cashback')
                        ->where('code', '=', $code)
                        ->get();

                    foreach ($report_bonus_cashback as $value) {

                        if ($value->bonus > 0) {
                            $wallet_g = DB::table('customers')
                                ->select('ewallet', 'id', 'user_name', 'ewallet_use','bonus_total')
                                ->where('user_name', $value->user_name_g)
                                ->first();

                            if ($wallet_g->ewallet == '' || empty($wallet_g->ewallet)) {
                                $wallet_g_user = 0;
                            } else {

                                $wallet_g_user = $wallet_g->ewallet;
                            }

                            if ($wallet_g->ewallet_use == '' || empty($wallet_g->ewallet_use)) {
                                $ewallet_use = 0;
                            } else {

                                $ewallet_use = $wallet_g->ewallet_use;
                            }
                            $eWallet_cash_back = new eWallet();
                            $wallet_g_total = $wallet_g_user +  $value->bonus;
                            $ewallet_use_total =  $ewallet_use + $value->bonus;

                            $eWallet_cash_back->transaction_code = $value->code_bonus;
                            $eWallet_cash_back->customers_id_fk = $wallet_g->id;
                            $eWallet_cash_back->customer_username = $value->user_name_g;
                            $eWallet_cash_back->customers_id_receive = $user->id;
                            $eWallet_cash_back->customers_name_receive = $user->user_name;

                            $eWallet_cash_back->tax_total = $value->tax_total;
                            $eWallet_cash_back->bonus_full = $value->bonus_full;
                            $eWallet_cash_back->amt = $value->bonus;
                            $eWallet_cash_back->old_balance = $wallet_g_user;
                            $eWallet_cash_back->balance = $wallet_g_total;
                            $eWallet_cash_back->type = 6;
                            $eWallet_cash_back->note_orther = 'G' . $value->g;
                            $eWallet_cash_back->receive_date = now();
                            $eWallet_cash_back->receive_time = now();
                            $eWallet_cash_back->status = 2;
                            $eWallet_cash_back->save();

                            DB::table('customers')
                                ->where('user_name', $value->user_name_g)
                                ->update(['ewallet' => $wallet_g_total, 'ewallet_use' => $ewallet_use_total]);

                            DB::table('report_bonus_cashback')
                                ->where('id', $value->id)
                                ->update(['status' => 'success', 'date_active' => now()]);
                        }
                    }
                }

                DB::commit();

                return redirect('jp_clarify')->withSuccess('แจง PV สำเร็จ');
            } catch (Exception $e) {
                DB::rollback();
                return redirect('jp_clarify')->withError('แจง PV ไม่สำเร็จกรุณาทำรายการไหม่อีกครั้ง');
            }
        } else {
            return redirect('jp_clarify')->withError('เงื่อนไขการแจง PV ไม่ถูกต้อง');
        }
    }

    public function jang_pv_active(Request $rs)
    {

        $wallet_g = DB::table('customers')
            ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'pv','bonus_total','bonus_total')
            ->where('user_name', Auth::guard('c_user')->user()->user_name)
            ->first();

        $data_user =  DB::table('customers')
            ->select(
                'customers.pv',
                'customers.id',
                'customers.name',
                'customers.last_name',
                'customers.user_name',
                'customers.qualification_id',
                'customers.expire_date',
                'dataset_qualification.pv_active'
            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', $rs->input_user_name_active)
            ->first();



        if (empty($data_user)) {
            return redirect('jp_clarify')->withError('เแจง PV ไม่สำเร็จกรุณาทำรายการไหม่อีกครั้ง');
        }
        $customer_update_use = Customers::find($wallet_g->id);
        $customer_update = Customers::find($data_user->id);
        if ($data_user->qualification_id == '' || $data_user->qualification_id == null || $data_user->qualification_id == '-') {
            $qualification_id = 'MB';
        } else {
            $qualification_id = $data_user->qualification_id;
        }

        $pv_balance = $wallet_g->pv - $data_user->pv_active;


        if ($pv_balance < 0) {
            return redirect('jp_clarify')->withError('PV ไม่พอสำหรับการแจง');
        }
        $customer_update_use->pv = $pv_balance;

        if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
            $start_month = date('Y-m-d');
            $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
            $customer_update->expire_date = date('Y-m-d', $mt_mount_new);
        } else {
            $start_month = $data_user->expire_date;
            $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
            $customer_update->expire_date = date('Y-m-d', $mt_mount_new);
        }

        // $jang_pv = new Jang_pv();
        $y = date('Y') + 543;
        $y = substr($y, -2);
        $code =  IdGenerator::generate([
            'table' => 'jang_pv',
            'field' => 'code',
            'length' => 15,
            'prefix' => 'PV' . $y . '' . date("m") . '-',
            'reset_on_prefix_change' => true
        ]);
        $jang_pv['code'] = $code;
        $jang_pv['customer_username'] = Auth::guard('c_user')->user()->user_name;
        $jang_pv['to_customer_username'] = $data_user->user_name;
        $jang_pv['position'] = $data_user->qualification_id;

        $jang_pv['bonus_percen'] = 100;
        $jang_pv['pv_old'] = $data_user->pv;
        $jang_pv['pv'] = $data_user->pv_active;
        $jang_pv['pv_balance'] =  $pv_balance;
        $jang_pv['date_active'] =  date('Y-m-d', $mt_mount_new);
        $pv_to_price =  $data_user->pv_active-$data_user->pv_active*(3/100); //ได้รับ 100%
        $jang_pv['wallet'] =  $pv_to_price;
        $jang_pv['type'] =  '1';
        $jang_pv['status'] =  'Success';

        $eWallet = new eWallet();
        $eWallet->transaction_code = $code;
        $eWallet->customers_id_fk = Auth::guard('c_user')->user()->id;
        $eWallet->customer_username = Auth::guard('c_user')->user()->user_name;
        $eWallet->customers_id_receive =  $data_user->id;
        $eWallet->customers_name_receive =  $data_user->user_name;
        $eWallet->tax_total = $data_user->pv_active*3/100;
        $eWallet->bonus_full =$data_user->pv_active;
        $eWallet->amt = $pv_to_price;

        if (empty($wallet_g->ewallet)) {
            $ewallet_user = 0;
        } else {
            $ewallet_user = $wallet_g->ewallet;
        }

        if (empty($wallet_g->bonus_total)) {
            $bonus_total = 0+$pv_to_price;
        } else {
            $bonus_total = $wallet_g->bonus_total+$pv_to_price;
        }


        if ($wallet_g->ewallet_use == '' || empty($wallet_g->ewallet_use)) {
            $ewallet_use = 0;
        } else {

            $ewallet_use = $wallet_g->ewallet_use;
        }

        $customer_update_use->ewallet_use = $ewallet_use + $pv_to_price;
        $customer_update_use->ewallet = $ewallet_use + $pv_to_price;
        $customer_update_use->bonus_total =  $bonus_total;
        $eWallet->old_balance = $ewallet_user;
        $wallet_balance = $ewallet_user + $pv_to_price;
        $customer_update_use->ewallet = $wallet_balance;
        $eWallet->balance = $wallet_balance;
        $eWallet->note_orther =  'สินสุดวันที่ ' . date('Y-m-d', $mt_mount_new);
        $eWallet->type = 7;
        $eWallet->receive_date = now();
        $eWallet->receive_time = now();
        $eWallet->status = 2;

        try {
            DB::BeginTransaction();
            $check_jang_pv = DB::table('jang_pv')
                ->where('code', '=', $code)
                ->first();
            if ($check_jang_pv) {
                DB::rollback();
                return redirect('jp_clarify')->withError('แจง PV ไม่สำเร็จกรุณาทำรายการไหม่อีกครั้ง');
            }

            $customer_update->save();

            DB::table('jang_pv')
            ->updateOrInsert(
                ['code' => $jang_pv['code'],'to_customer_username' => $jang_pv['to_customer_username']],
                $jang_pv
            );

            $eWallet->save();
            $customer_update_use->save();

            $RunBonusActive = \App\Http\Controllers\Frontend\BonusActiveController::RunBonusActive($code);

            if ($RunBonusActive == true) {
                $report_bonus_active = DB::table('report_bonus_active')
                    ->where('code', '=', $code)
                    ->get();

                foreach ($report_bonus_active as $value) {

                    if ($value->bonus > 0) {
                        $wallet_g = DB::table('customers')
                            ->select('ewallet', 'id', 'user_name', 'ewallet_use','bonus_total')
                            ->where('user_name', $value->user_name_g)
                            ->first();

                        if ($wallet_g->ewallet == '' || empty($wallet_g->ewallet)) {
                            $wallet_g_user = 0;
                        } else {

                            $wallet_g_user = $wallet_g->ewallet;
                        }

                        if ($wallet_g->ewallet_use == '' || empty($wallet_g->ewallet_use)) {
                            $ewallet_use = 0;
                        } else {

                            $ewallet_use = $wallet_g->ewallet_use;
                        }
                        $eWallet_active = new eWallet();
                        $wallet_g_total = $wallet_g_user +  $value->bonus;
                        $ewallet_use_total =  $ewallet_use + $value->bonus;
                        $eWallet_active->transaction_code = $value->code_bonus;
                        $eWallet_active->customers_id_fk = $wallet_g->id;
                        $eWallet_active->customer_username = $value->user_name_g;
                        $eWallet_active->customers_id_receive = $data_user->id;
                        $eWallet_active->customers_name_receive = $data_user->user_name;
                        $eWallet_active->tax_total =  $value->tax_total ;
                        $eWallet_active->bonus_full = $value->bonus_full;
                        $eWallet_active->amt = $value->bonus;
                        $eWallet_active->old_balance = $wallet_g_user;
                        $eWallet_active->balance = $wallet_g_total;
                        $eWallet_active->type = 8;
                        $eWallet_active->note_orther = 'G' . $value->g;
                        $eWallet_active->receive_date = now();
                        $eWallet_active->receive_time = now();
                        $eWallet_active->status = 2;
                        $eWallet_active->save();

                        DB::table('customers')
                            ->where('user_name', $value->user_name_g)
                            ->update(['ewallet' => $wallet_g_total, 'ewallet_use' => $ewallet_use_total]);

                        DB::table('report_bonus_active')
                            ->where('id', $value->id)
                            ->update(['ewalet_old' => $wallet_g_user, 'ewalet_new' => $wallet_g_total, 'ewallet_use_old' => $ewallet_use, 'ewallet_use_new' => $ewallet_use_total, 'status' => 'success', 'date_active' => now()]);
                    }
                }
            }


            DB::commit();

            return redirect('jp_clarify')->withSuccess('เแจง PV สำเร็จ');
        } catch (Exception $e) {
            DB::rollback();
            return redirect('jp_clarify')->withError('เแจง PV ไม่สำเร็จกรุณาทำรายการไหม่อีกครั้ง');
        }
    }

    public function tranfer_pv(Request $rs)
    {

        $wallet_g = DB::table('customers')
            ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'pv','bonus_total')
            ->where('user_name', Auth::guard('c_user')->user()->user_name)
            ->first();

        $username_pv_tranfer_recive = trim($rs->username_pv_tranfer_recive);
        $data_user =  DB::table('customers')
            ->select('customers.pv', 'customers.id', 'customers.name', 'customers.last_name', 'customers.user_name', 'ewallet')
            ->where('user_name', '=', $username_pv_tranfer_recive)
            ->first();

        if (Auth::guard('c_user')->user()->user_name == $username_pv_tranfer_recive) {
            return redirect('jp_clarify')->withError('ไม่สามารถทำรายการให้ตัวเองได้');
        }

        $pv_tranfer = $rs->pv_tranfer;
        if ($pv_tranfer <= 0) {
            return redirect('jp_clarify')->withError('PV เป็น 0 กรุณาทำรายการไหม่');
        }

        if (empty($data_user)) {
            return redirect('jp_clarify')->withError('โอน PV ไม่สำเร็จกรุณาทำรายการไหม่อีกครั้ง');
        }
        $customer_update_use = Customers::find($wallet_g->id);
        $customer_update = Customers::find($data_user->id);


        $pv_balance = $wallet_g->pv - $pv_tranfer;


        if ($pv_balance < 0) {
            return redirect('jp_clarify')->withError('PV ไม่พอสำหรับการโอน');
        }
        $customer_update_use->pv = $pv_balance;



        $jang_pv = new Jang_pv();
        // $jang_pv_recive = new Jang_pv();
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
        //$jang_pv_recive->code = $code;
        $jang_pv->customer_username = Auth::guard('c_user')->user()->user_name;
        //$jang_pv_recive->customer_username =  $data_user->user_name;
        $jang_pv->customers_username_tranfer = Auth::guard('c_user')->user()->user_name;
        //$jang_pv_recive->customers_username_tranfer = Auth::guard('c_user')->user()->user_name;
        $jang_pv->to_customer_username = $data_user->user_name;
        //$jang_pv_recive->to_customer_username = $data_user->user_name;

        // $jang_pv->position = $data_user->qualification_id;

        // $jang_pv->bonus_percen = 100;
        $jang_pv->pv_old =  $wallet_g->pv;
        $jang_pv->pv_old_recive =  $data_user->pv;


        $jang_pv->pv = $pv_tranfer;
        //$jang_pv_recive->pv =  $pv_tranfer;

        $jang_pv->pv_balance =  $pv_balance;
        $jang_pv->pv_balance_recive =  $data_user->pv + $pv_tranfer;
        $customer_update->pv = $data_user->pv + $pv_tranfer;
        // $jang_pv->date_active =  date('Y-m-d',$mt_mount_new);
        // $pv_to_price =  $data_user->pv_active;//ได้รับ 100%
        $jang_pv->wallet =  $wallet_g->ewallet;
        //$jang_pv_recive->wallet =  $data_user->ewallet;
        $jang_pv->type = 6;
        //$jang_pv_recive->type = 6;


        $jang_pv->status =  'Success';
        //$jang_pv_recive->status =  'Success';


        try {
            DB::BeginTransaction();
            // sleep(1);
            // usleep(2500000);
            $check_jang_pv = DB::table('jang_pv')
                ->where('code', '=', $code)
                ->first();
            if ($check_jang_pv) {
                DB::rollback();
                return redirect('jp_clarify')->withError('โอน PV ไม่สำเร็จกรุณาทำรายการไหม่อีกครั้ง');
            }

            $customer_update->save();
            $customer_update_use->save();
            $jang_pv->save();
            //$jang_pv_recive->save();


            DB::commit();

            return redirect('jp_clarify')->withSuccess('โอน PV สำเร็จ');
        } catch (Exception $e) {
            DB::rollback();
            return redirect('jp_clarify')->withError('โอน PV ไม่สำเร็จกรุณาทำรายการไหม่อีกครั้ง');
        }
    }


    public function datatable(Request $rs)
    {
        $s_date = !empty($rs->s_date) ? date('Y-m-d', strtotime($rs->s_date)) : date('Y-01-01');
        $e_date = !empty($rs->e_date) ? date('Y-m-d', strtotime($rs->e_date)) : date('Y-12-t');

        $date_between = [$s_date, $e_date];

        $user_name = Auth::guard('c_user')->user()->user_name;

        $jang_pv = DB::table('jang_pv')
            ->select('jang_pv.*', 'jang_type.type as type_name')
            // ->where('type_tranfer', '!=', 'receive')
            ->where('customer_username', '=', $user_name)
            ->orwhere('to_customer_username', '=', $user_name)
            ->leftjoin('jang_type', 'jang_type.id', '=', 'jang_pv.type');

        // ->when($date_between, function ($query, $date_between) {
        //     return $query->whereBetween('created_at', $date_between);
        // });

        $sQuery = Datatables::of($jang_pv);
        return $sQuery

            ->addColumn('created_at', function ($row) { //วันที่สมัคร
                if ($row->created_at == '0000-00-00 00:00:00') {
                    return '-';
                } else {
                    return date('Y/m/d H:i:s', strtotime($row->created_at));
                }
            })


            ->addColumn('code_order', function ($row) { //วันที่สมัคร
                if ($row->type == 5) {
                    $data = '<a href="' . route('order_detail', ['code_order' => $row->code_order]) . '" class="btn btn-sm btn-outline-primary">' . $row->code_order . '</a>';
                    return $data;
                } else {
                    return '';
                }
            })

            ->addColumn('type', function ($row) { //การรักษาสภำพ
                $resule = $row->type_name;
                return $resule;
            })
            ->addColumn('name_use', function ($row) {
                $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->customer_username);
                if ($upline) {
                    $html = @$upline->name . ' ' . @$upline->last_name . '(' . $upline->user_name . ')';
                } else {
                    $html = '-';
                }

                return $html;
            })

            ->addColumn('name', function ($row) {
                $upline = \App\Http\Controllers\Frontend\FC\AllFunctionController::get_upline($row->to_customer_username);
                if ($upline) {
                    $html = @$upline->name . ' ' . @$upline->last_name . '(' . $upline->user_name . ')';
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

            ->addColumn('pv', function ($row) use ($user_name) {
                if ($row->customer_username == $user_name) {
                    if ($row->type == 5 || $row->type == 6) {
                        $html = number_format($row->pv);
                        return  $html;
                    } else {
                        $html = number_format($row->pv);
                        return  '-' . $html;
                    }
                } else {
                    if ($row->type == 6) {
                        $html = number_format($row->pv);
                        return  $html;
                    } else {
                        return '-';
                    }
                }
            })

            ->addColumn('date_active', function ($row) {
                if ($row->date_active == '0000-00-00 00:00:00' || empty($row->date_active)) {
                    return '-';
                } else {
                    return date('Y/m/d', strtotime($row->date_active));
                }
            })

            ->addColumn('wallet', function ($row) use ($user_name) {
                if ($row->customer_username == $user_name) {
                    if ($row->type == 5) {
                        $html = number_format($row->wallet);
                        return  '-' . $html;
                    } else {
                        $html = number_format($row->wallet);
                        return  $html;
                    }
                } else {
                    return '-';
                }
            })
            ->addColumn('pv_balance', function ($row) use ($user_name) {
                if ($row->customer_username == $user_name) {
                    $html = number_format($row->pv_balance);
                    return  $html;
                } else {
                    if ($row->type == 6) {
                        $html = number_format($row->pv_balance_recive);
                        return  $html;
                    } else {
                        return '-';
                    }
                }
            })

            ->addColumn('status', function ($row) {
                $html =  $row->status;
                return  $html;
            })


            ->rawColumns(['status_active', 'view', 'action', 'code_order'])
            ->make(true);
    }
}
