<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Customers;
use App\Jang_pv;
use DB;
use App\Log_insurance;
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

            $code =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_pv();

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
            $pv_to_price_tax = $pv_to_price - ($pv_to_price * 3 / 100);
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
            $eWallet->tax_total = $pv_to_price * 3 / 100;
            $eWallet->bonus_full = $pv_to_price;
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
                                ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'bonus_total')
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
            ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'pv', 'bonus_total', 'bonus_total')
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

        $code =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_pv();

        $jang_pv['code'] = $code;
        $jang_pv['customer_username'] = Auth::guard('c_user')->user()->user_name;
        $jang_pv['to_customer_username'] = $data_user->user_name;
        $jang_pv['position'] = $data_user->qualification_id;

        $jang_pv['bonus_percen'] = 100;
        $jang_pv['pv_old'] = $data_user->pv;
        $jang_pv['pv'] = $data_user->pv_active;
        $jang_pv['pv_balance'] =  $pv_balance;
        $jang_pv['date_active'] =  date('Y-m-d', $mt_mount_new);
        $pv_to_price =  $data_user->pv_active - $data_user->pv_active * (3 / 100); //ได้รับ 100%
        $jang_pv['wallet'] =  $pv_to_price;
        $jang_pv['type'] =  '1';
        $jang_pv['status'] =  'Success';

        $eWallet = new eWallet();
        $eWallet->transaction_code = $code;
        $eWallet->customers_id_fk = Auth::guard('c_user')->user()->id;
        $eWallet->customer_username = Auth::guard('c_user')->user()->user_name;
        $eWallet->customers_id_receive =  $data_user->id;
        $eWallet->customers_name_receive =  $data_user->user_name;
        $eWallet->tax_total = $data_user->pv_active * 3 / 100;
        $eWallet->bonus_full = $data_user->pv_active;
        $eWallet->amt = $pv_to_price;

        if (empty($wallet_g->ewallet)) {
            $ewallet_user = 0;
        } else {
            $ewallet_user = $wallet_g->ewallet;
        }

        if (empty($wallet_g->bonus_total)) {
            $bonus_total = 0 + $pv_to_price;
        } else {
            $bonus_total = $wallet_g->bonus_total + $pv_to_price;
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
                    ['code' => $jang_pv['code'], 'to_customer_username' => $jang_pv['to_customer_username']],
                    $jang_pv
                );

            $eWallet->save();
            $customer_update_use->save();
            $customer_username = Auth::guard('c_user')->user()->user_name;
            $to_customer_username = $data_user->user_name;
            $RunBonusActive = \App\Http\Controllers\Frontend\BonusActiveController::RunBonusActive($code,$customer_username,$to_customer_username);

            if ($RunBonusActive == true) {
                $report_bonus_active = DB::table('report_bonus_active')
                    ->where('code', '=', $code)
                    ->get();

                foreach ($report_bonus_active as $value) {

                    if ($value->bonus > 0) {
                        $wallet_g = DB::table('customers')
                            ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'bonus_total')
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
                        $eWallet_active->tax_total =  $value->tax_total;
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
            ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'pv', 'bonus_total')
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

        $code =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_pv();

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


    public function jang_pv_upgrad(Request $rs)
    {



        $user_action = DB::table('customers')
            ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'pv', 'bonus_total', 'pv_upgrad', 'name', 'last_name')
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
                'customers.pv_upgrad',
                'customers.expire_date',
                'customers.introduce_id',
                'dataset_qualification.id as position_id',
                'dataset_qualification.pv_active',
                'customers.expire_insurance_date',
            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', $rs->input_user_name_upgrad)
            ->first();

        $old_position = $data_user->qualification_id;



        if (empty($data_user)) {
            return redirect('jp_clarify')->withError('แจง PV ไม่สำเร็จกรุณาทำรายการไหม่อีกครั้ง');
        }

        $pv_balance = $user_action->pv - $rs->pv_upgrad_input;
        if ($pv_balance < 0) {
            return redirect('jp_clarify')->withError('PV ไม่พอสำหรับการแจงอัพตำแหน่ง');
        }



        $pv_mo = 400;
        $pv_vip = 800;
        $pv_vvip = 1200;
        // $pv_upgrad_total_mo = $pv_mo - $data_user->pv_upgrad;
        // $pv_upgrad_total_vip = $pv_vip - $data_user->pv_upgrad;

        $pv_upgrad_total_vvip = $pv_vvip - $data_user->pv_upgrad;
        if ($rs->pv_upgrad_input >  1200) {
            return redirect('jp_clarify')->withError('ไม่สามารถใส่ค่า PV เกิน 12,000 กรุณาทำรายการไหม่อีกครั้ง');
        }


        $customer_update_use = Customers::find($user_action->id);
        $customer_update = Customers::find($data_user->id);
        if ($data_user->qualification_id == '' || $data_user->qualification_id == null || $data_user->qualification_id == '-') {
            $qualification_id = 'MB';
        } else {
            $qualification_id = $data_user->qualification_id;
        }


        $pv_upgrad_total = $data_user->pv_upgrad + $rs->pv_upgrad_input;

        if ($data_user->qualification_id == 'MB') {

            if ($pv_upgrad_total >= 400 and $pv_upgrad_total < 800) { //อัพ MO
                $position_update = 'MO';
            } elseif ($pv_upgrad_total >= 800 and $pv_upgrad_total  < 1200) { //vip
                $position_update = 'VIP';
            } elseif ($pv_upgrad_total >= 1200) { //vvip
                $position_update = 'VVIP';
            } else { //อัพ pv_upgrad
                $position_update = $data_user->qualification_id;
            }
        } elseif ($data_user->qualification_id == 'MO') {

            if ($pv_upgrad_total >= 800 and $pv_upgrad_total  < 1200) { //vip
                $position_update = 'VIP';
            } elseif ($pv_upgrad_total >= 1200) { //vvip
                $position_update = 'VVIP';
            } else { //อัพ pv_upgrad
                $position_update = $data_user->qualification_id;
            }
        } elseif ($data_user->qualification_id == 'VIP') {
            if ($pv_upgrad_total >= 1200) { //vvip
                $position_update = 'VVIP';
            } else { //อัพ pv_upgrad
                $position_update = $data_user->qualification_id;
            }
        } elseif ($data_user->qualification_id == 'VVIP') {
            return redirect('jp_clarify')->withError('รหัสนี้เป็น VVIP แล้ว ไม่สามารถอัพเกรดขึ้นได้อีก');
        } else {
            return redirect('jp_clarify')->withError('ทำรายการไม่สำเร็จกรุณาทำรายการไหม่');
        }

        $customer_username = $data_user->introduce_id;
        $arr_user = array();
        $report_bonus_register = array();

        $code_bonus = \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_bonus(2);

        for ($i = 1; $i <= 10; $i++) {
            $x = 'start';
            $run_data_user =  DB::table('customers')
                ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
                // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                ->where('user_name', '=', $customer_username)
                ->first();


            // dd($customer_username);

            if (empty($run_data_user)) {
                $i = 10;
                //$rs = Report_bonus_register::insert($report_bonus_register);

            } else {

                while ($x = 'start') {
                    if (empty($run_data_user->name)) {

                        $customer_username = $run_data_user->introduce_id;

                        $run_data_user =  DB::table('customers')
                            ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
                            // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                            ->where('user_name', '=', $customer_username)
                            ->first();
                    } else {

                        if ($run_data_user->qualification_id == '' || $run_data_user->qualification_id == null || $run_data_user->qualification_id == '-') {
                            $qualification_id = 'MB';
                        } else {
                            $qualification_id = $run_data_user->qualification_id;
                        }

                        $report_bonus_register[$i]['user_name'] = $user_action->user_name;
                        $report_bonus_register[$i]['name'] = $user_action->name . ' ' . $user_action->last_name;

                        $report_bonus_register[$i]['regis_user_name'] = $rs->input_user_name_upgrad;
                        $report_bonus_register[$i]['regis_user_introduce_id'] = $data_user->introduce_id;
                        $report_bonus_register[$i]['regis_name'] = $data_user->name . ' ' . $data_user->last_name;
                        $report_bonus_register[$i]['user_name_g'] = $run_data_user->user_name;
                        $report_bonus_register[$i]['old_position'] = $data_user->qualification_id;
                        $report_bonus_register[$i]['new_position'] = $position_update;
                        $report_bonus_register[$i]['name_g'] = $run_data_user->name . ' ' . $run_data_user->last_name;
                        $report_bonus_register[$i]['qualification'] = $qualification_id;
                        $report_bonus_register[$i]['g'] = $i;
                        $report_bonus_register[$i]['pv'] = $rs->pv_upgrad_input;
                        $report_bonus_register[$i]['code_bonus'] = $code_bonus;
                        $report_bonus_register[$i]['type'] = 'jangpv';

                        $arr_user[$i]['user_name'] = $run_data_user->user_name;
                        $arr_user[$i]['lv'] = [$i];
                        if ($i == 1) {
                            $report_bonus_register[$i]['percen'] = 250;

                            $arr_user[$i]['pv'] = $rs->pv_upgrad_input;
                            $arr_user[$i]['position'] = $qualification_id;
                            $wallet_total = $rs->pv_upgrad_input * 250 / 100;
                            $arr_user[$i]['bonus'] = $wallet_total;
                            $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                            $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                            $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                        } elseif ($i == 2) {
                            $report_bonus_register[$i]['percen'] = 20;
                            $arr_user[$i]['pv'] = $rs->pv_upgrad_input;
                            $arr_user[$i]['position'] = $qualification_id;
                            if ($qualification_id == 'MB') {
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } else {

                                $wallet_total = $rs->pv_upgrad_input * 20 / 100;
                                $arr_user[$i]['bonus'] = $wallet_total;
                                $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                            }
                        } elseif ($i == 3) {
                            $report_bonus_register[$i]['percen'] = 10;
                            $arr_user[$i]['pv'] = $rs->pv_upgrad_input;
                            $arr_user[$i]['position'] = $qualification_id;
                            if ($qualification_id == 'MB') {
                                $report_bonus_register[$i]['tax_total'] = 0;
                                $report_bonus_register[$i]['bonus_full'] = 0;
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } else {

                                $wallet_total = $rs->pv_upgrad_input * 10 / 100;
                                $arr_user[$i]['bonus'] = $wallet_total;
                                $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                            }
                        } elseif ($i == 4) {
                            $report_bonus_register[$i]['percen'] = 5;
                            $arr_user[$i]['pv'] = $rs->pv_upgrad_input;
                            $arr_user[$i]['position'] = $qualification_id;

                            if ($qualification_id == 'MB' || $qualification_id == 'MO') {
                                $report_bonus_register[$i]['tax_total'] = 0;
                                $report_bonus_register[$i]['bonus_full'] = 0;
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } else {

                                $wallet_total = $rs->pv_upgrad_input * 5 / 100;
                                $arr_user[$i]['bonus'] = $wallet_total;
                                $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                            }
                        } elseif ($i >= 5 and $i <= 10) {
                            $report_bonus_register[$i]['percen'] = 5;
                            $arr_user[$i]['pv'] = $rs->pv_upgrad_input;
                            $arr_user[$i]['position'] = $qualification_id;

                            if (($i == 5 || $i == 6 || $i == 7) and $qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP') {
                                $report_bonus_register[$i]['tax_total'] = 0;
                                $report_bonus_register[$i]['bonus_full'] = 0;
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } elseif (($i == 8 || $i == 9 || $i == 10) and $qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP' || $qualification_id == 'VVIP') {

                                $report_bonus_register[$i]['tax_total'] = 0;
                                $report_bonus_register[$i]['bonus_full'] = 0;
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } else {
                                $wallet_total = $rs->pv_upgrad_input * 5 / 100;
                                $arr_user[$i]['bonus'] = $wallet_total;
                                $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                            }
                        }

                        $customer_username = $run_data_user->introduce_id;
                        $x = 'stop';
                        break;
                    }
                }
            }
        }


        try {


            DB::BeginTransaction();
            foreach ($report_bonus_register as $value) {
                DB::table('report_bonus_register')
                    ->updateOrInsert(
                        ['code_bonus' => $value['code_bonus'], 'user_name' => $value['user_name'], 'regis_user_name' => $value['regis_user_name'], 'g' => $value['g'], 'type' => $value['type']],
                        $value
                    );
            }



            $db_bonus_register = DB::table('report_bonus_register')
                ->where('status', '=', 'panding')
                ->where('bonus', '>', 0)
                ->where('code_bonus', '=', $code_bonus)
                ->where('regis_user_name', '=', $rs->input_user_name_upgrad)
                ->get();

            foreach ($db_bonus_register as $value) {


                if ($value->bonus > 0) {


                    $wallet_g = DB::table('customers')
                        ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'bonus_total')
                        ->where('user_name', $value->user_name_g)
                        ->first();

                    if ($wallet_g->ewallet == '' || empty($wallet_g->ewallet)) {
                        $wallet_g_user = 0;
                    } else {

                        $wallet_g_user = $wallet_g->ewallet;
                    }

                    if ($wallet_g->bonus_total == '' || empty($wallet_g->bonus_total)) {
                        $bonus_total = 0 + $value->bonus;
                    } else {

                        $bonus_total = $wallet_g->bonus_total + $value->bonus;
                    }

                    if ($wallet_g->ewallet_use == '' || empty($wallet_g->ewallet_use)) {
                        $ewallet_use = 0;
                    } else {

                        $ewallet_use = $wallet_g->ewallet_use;
                    }
                    $eWallet_register = new eWallet();
                    $wallet_g_total = $wallet_g_user + $value->bonus;
                    $ewallet_use_total =  $ewallet_use + $value->bonus;

                    $eWallet_register->transaction_code = $code_bonus;
                    $eWallet_register->customers_id_fk = $wallet_g->id;
                    $eWallet_register->customer_username = $value->user_name_g;
                    // $eWallet_register->customers_id_receive = $user->id;
                    // $eWallet_register->customers_name_receive = $user->user_name;
                    $eWallet_register->tax_total = $value->tax_total;
                    $eWallet_register->bonus_full = $value->bonus_full;
                    $eWallet_register->amt = $value->bonus;
                    $eWallet_register->old_balance = $wallet_g_user;
                    $eWallet_register->balance = $wallet_g_total;
                    $eWallet_register->type = 10;
                    $eWallet_register->note_orther = 'โบนัสขยายธุรกิจ รหัส ' . $value->user_name . ' แนะนำรหัส ' . $value->regis_user_name;
                    $eWallet_register->receive_date = now();
                    $eWallet_register->receive_time = now();
                    $eWallet_register->status = 2;

                    DB::table('customers')
                        ->where('user_name', $value->user_name_g)
                        ->update(['ewallet' => $wallet_g_total, 'ewallet_use' => $ewallet_use_total, 'bonus_total' => $bonus_total]);

                    DB::table('report_bonus_register')
                        ->where('user_name_g',  $value->user_name_g)
                        ->where('code_bonus', '=', $code_bonus)
                        ->where('regis_user_name', '=', $value->regis_user_name)
                        ->where('g', '=', $value->g)
                        ->update(['status' => 'success']);

                    $eWallet_register->save();
                } else {
                    DB::table('report_bonus_register')
                        ->where('user_name_g',  $value->user_name_g)
                        ->where('code_bonus', '=', $code_bonus)
                        ->where('regis_user_name', '=', $value->regis_user_name)
                        ->where('g', '=', $value->g)
                        ->update(['status' => 'success']);
                }
            }



            $code =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_pv();


            $jang_pv = [
                'code' => $code,
                'customer_username' => $user_action->user_name,
                'to_customer_username' => $rs->input_user_name_upgrad,
                'old_position' => $data_user->qualification_id,
                'position' => $position_update,
                'pv_old' => $user_action->pv,
                'pv' =>  $rs->pv_upgrad_input,
                'pv_balance' => $pv_balance,
                'type' => '3',
                'status' => 'Success'
            ];


            if ($data_user->qualification_id  != $position_update) {
                DB::table('log_up_vl')->insert([
                    'user_name' => $data_user->user_name,'introduce_id' => $data_user->introduce_id,
                    'old_lavel' => $data_user->qualification_id, 'new_lavel' => $position_update, 'status' => 'success', 'type' => 'jangpv'
                ]);
            }

            if ($position_update == 'VVIP') {
                if ($rs->pv_upgrad_input >= 1200) {

                    $insert_jangpv = Jang_pv::create($jang_pv);
                    if ($data_user->expire_insurance_date == null ||  $data_user->expire_insurance_date == '' ||  $data_user->expire_insurance_date == '0000-00-00 00:00:00') {
                        $start_month = date('Y-m-d');
                    } else {
                        $start_month = $data_user->expire_insurance_date;
                    }
                    $insurance_date = date('Y-m-d', strtotime("+1 years", strtotime($start_month)));
                    $log_insurance_data = [
                        'user_name' => $data_user->user_name,
                        'old_exprie_date' => $data_user->expire_insurance_date,
                        'new_exprie_date' => $insurance_date,
                        'position' => 'VVIP',
                        'pv' => $rs->pv_upgrad_input,
                        'status' => 'success',
                        'type' => 'jangpv',
                    ];
                    Log_insurance::create($log_insurance_data);


                    DB::table('customers')
                        ->where('user_name', $data_user->user_name)
                        ->update(['qualification_id' => $position_update, 'pv_upgrad' => $pv_upgrad_total, 'vvip_register_type' => 'jangpv1200']);
                } else {
                    $insert_jangpv = Jang_pv::create($jang_pv);
                    DB::table('customers')
                        ->where('user_name', $data_user->user_name)
                        ->update(['qualification_id' => $position_update, 'pv_upgrad' => $pv_upgrad_total, 'vvip_register_type' => 'jangpv_vvip', 'pv_upgrad_vvip' => $rs->pv_upgrad_input]);
                }
            } else {
                $insert_jangpv = Jang_pv::create($jang_pv);
                DB::table('customers')
                    ->where('user_name', $data_user->user_name)
                    ->update(['qualification_id' => $position_update, 'pv_upgrad' => $pv_upgrad_total]);
            }




            if ($position_update == 'VVIP') {

                $data_user_upposition =  DB::table('customers')
                    ->select(
                        'customers.name',
                        'customers.last_name',
                        'bonus_total',
                        'customers.user_name',
                        'customers.upline_id',
                        'customers.introduce_id',
                        'customers.qualification_id',
                        'customers.expire_date',
                        'dataset_qualification.id as qualification_id_fk'
                    )
                    ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                    ->where('user_name', '=',  $data_user->introduce_id)
                    // ->where('dataset_qualification.id', '=', 6)// 4 - 7
                    ->first();
                // $data_user =  DB::table('customers')
                //     ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                //     ->where('customers.introduce_id', '=', '1384810')
                //     ->where('dataset_qualification.id', '=', 4)
                //     ->count();//

                // dd($data_user_1,$data_user);



                // dd($data_user_1);
                //ขึ้น XVVIP แนะนำ 2 VVIP คะแนน 0ว


                $upline_pv = \App\Http\Controllers\Frontend\FC\PvUpPositionXvvipController::get_pv_upgrade($data_user->introduce_id);//โบนัสสร้างทีม XVVIP


                $data_user_upgrad_vvip =  DB::table('customers')
                    ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                    ->where('customers.introduce_id', '=',  $data_user_upposition->user_name)
                    ->where('dataset_qualification.id', '=', 4)
                    ->count(); //


                // dd($data_user_upgrad_vvip, $data_user_upposition->qualification_id_fk);
                // dd($data_user);
                // dd($data_user, $data_user_upposition->qualification_id, $data_user_upposition->qualification_id_fk);
                //$data_user >= 2 and  $data_user_upposition->qualification_id != 'XVVIP' and   $data_user_upposition->qualification_id_fk< 5
                if ($data_user_upgrad_vvip >= 200 and  $data_user_upposition->qualification_id_fk == 9) { //MD
                    $data_svvip =  DB::table('customers')
                        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        ->where('customers.introduce_id', '=',  $data_user_upposition->user_name)
                        ->where('dataset_qualification.id', '=', 6)
                        ->count();
                    if ($data_svvip >= 21 and $upline_pv >= 200000 and  $data_user_upposition->bonus_total >= 3000000) {


                        DB::table('customers')
                            ->where('user_name',  $data_user_upposition->user_name)
                            ->update(['qualification_id' => 'MD']);
                        DB::table('log_up_vl')->insert([
                            'user_name' =>  $data_user_upposition->user_name,'introduce_id' => $data_user_upposition->introduce_id, 'bonus_total' =>  $data_user_upposition->bonus_total,
                            'old_lavel' => $data_user_upgrad_vvip->code, 'new_lavel' => 'MD', 'vvip' => $data_user_upgrad_vvip, 'svvip' => $data_svvip, 'status' => 'success', 'type' => 'jangpv'
                        ]);
                    }
                }

                if ($data_user_upgrad_vvip >= 150 and   $data_user_upposition->qualification_id_fk == 8) {
                    $data_svvip =  DB::table('customers')
                        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        ->where('customers.introduce_id', '=',  $data_user_upposition->user_name)
                        ->where('dataset_qualification.id', '=', 6)
                        ->count();
                    if ($data_svvip >= 13 and $upline_pv >= 180000 and  $data_user_upposition->bonus_total >= 2000000) {


                        DB::table('customers')
                            ->where('user_name',  $data_user_upposition->user_name)
                            ->update(['qualification_id' => 'ME']);
                        DB::table('log_up_vl')->insert([
                            'user_name' =>  $data_user_upposition->user_name,'introduce_id' => $data_user_upposition->introduce_id, 'bonus_total' =>  $data_user_upposition->bonus_total,
                            'old_lavel' =>  $data_user_upposition->qualification_id, 'new_lavel' => 'ME', 'vvip' => $data_user_upgrad_vvip, 'svvip' => $data_svvip, 'status' => 'success', 'type' => 'jangpv'
                        ]);
                    }
                }



                if ($data_user_upgrad_vvip >= 100 and   $data_user_upposition->qualification_id_fk == 7) {
                    $data_svvip =  DB::table('customers')
                        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        ->where('customers.introduce_id', '=',  $data_user_upposition->user_name)
                        ->where('dataset_qualification.id', '=', 6)
                        ->count();
                    if ($data_svvip >= 7 and $upline_pv >= 120000 and  $data_user_upposition->bonus_total >= 1000000) {


                        DB::table('customers')
                            ->where('user_name',  $data_user_upposition->user_name)
                            ->update(['qualification_id' => 'MR']);
                        DB::table('log_up_vl')->insert([
                            'user_name' =>  $data_user_upposition->user_name,'introduce_id' => $data_user_upposition->introduce_id, 'bonus_total' =>  $data_user_upposition->bonus_total,
                            'old_lavel' =>  $data_user_upposition->qualification_id, 'new_lavel' => 'MR', 'vvip' => $data_user_upgrad_vvip, 'svvip' => $data_svvip, 'status' => 'success', 'type' => 'jangpv'
                        ]);
                    }
                }

                if ($data_user_upgrad_vvip >= 60 and   $data_user_upposition->qualification_id_fk == 6) {
                    $data_svvip =  DB::table('customers')
                        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        ->where('customers.introduce_id', '=',  $data_user_upposition->user_name)
                        ->where('dataset_qualification.id', '=', 6)
                        ->count();
                    if ($data_svvip >= 3 and $upline_pv >= 72000 and  $data_user_upposition->bonus_total >= 100000) {


                        DB::table('customers')
                            ->where('user_name',  $data_user_upposition->user_name)
                            ->update(['qualification_id' => 'MG']);
                        DB::table('log_up_vl')->insert([
                            'user_name' =>  $data_user_upposition->user_name,'introduce_id' => $data_user_upposition->introduce_id, 'bonus_total' =>  $data_user_upposition->bonus_total,
                            'old_lavel' =>  $data_user_upposition->qualification_id, 'new_lavel' => 'MG', 'vvip' => $data_user_upgrad_vvip, 'svvip' => $data_svvip, 'status' => 'success', 'type' => 'jangpv'
                        ]);
                    }
                }

                if (  $data_user_upposition->qualification_id_fk == 5 and $upline_pv >= 48000 and  $data_user_upposition->bonus_total >= 100000) {


                    DB::table('customers')
                        ->where('user_name',  $data_user_upposition->user_name)
                        ->update(['qualification_id' => 'SVVIP']);


                    DB::table('log_up_vl')->insert([
                        'user_name' =>  $data_user_upposition->user_name,'introduce_id' => $data_user_upposition->introduce_id, 'bonus_total' =>  $data_user_upposition->bonus_total,
                        'old_lavel' =>  $data_user_upposition->qualification_id, 'new_lavel' => 'SVVIP', 'vvip' => $data_user_upgrad_vvip, 'status' => 'success', 'type' => 'jangpv'
                    ]);
                }

                $data_user_xvvip =  DB::table('customers')
                    ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                    ->where('customers.introduce_id', '=', $data_user->introduce_id)
                    ->where('dataset_qualification.id', '>=', 4)
                    ->count();

                if ($upline_pv >= 2400  and   $data_user_upposition->qualification_id_fk == 4) {
                    DB::table('customers')
                        ->where('user_name',  $data_user_upposition->user_name)
                        ->update(['qualification_id' => 'XVVIP']);
                    DB::table('log_up_vl')->insert([
                        'user_name' =>  $data_user_upposition->user_name,'introduce_id' => $data_user_upposition->introduce_id, 'old_lavel' =>  $data_user_upposition->qualification_id,
                        'new_lavel' => 'XVVIP', 'bonus_total' =>  $data_user_upposition->bonus_total, 'vvip' => $data_user_upgrad_vvip, 'status' => 'success', 'type' => 'jangpv'
                    ]);
                }
            }

            if ($rs->pv_upgrad_input == 1200) {



                $data_user_bonus_4 =  DB::table('customers')
                    ->select(
                        'customers.id',
                        'customers.name',
                        'customers.last_name',
                        'bonus_total',
                        'customers.user_name',
                        'customers.upline_id',
                        'customers.introduce_id',
                        'customers.qualification_id',
                        'dataset_qualification.id as qualification_id_fk'
                    )
                    ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                    ->where('customers.introduce_id', '=', $data_user->introduce_id)
                    ->where('dataset_qualification.id', '=', 4)
                    ->where('customers.vvip_register_type', '=', 'jangpv1200')
                    ->where('customers.vvip_status_runbonus', '=', 'panding')
                    ->limit(2)
                    ->get();

                $data_check_upline =  DB::table('customers')
                    ->select(
                        'customers.id',
                        'customers.name',
                        'customers.last_name',
                        'customers.user_name',
                        'customers.upline_id',
                        'customers.introduce_id',
                        'customers.qualification_id',

                    )
                    ->where('user_name', '=', $data_user->introduce_id)
                    ->first();

                $data_check_xvvip_bonus =  DB::table('customers')
                    ->select(
                        'customers.id',
                        'customers.name',
                        'customers.last_name',
                        'customers.user_name',
                        'customers.upline_id',
                        'customers.introduce_id',
                        'customers.qualification_id',

                    )
                    ->where('user_name', '=', $data_check_upline->introduce_id)
                    ->first();


                if (count($data_user_bonus_4) >= 2 and ($data_check_xvvip_bonus->qualification_id == 'XVVIP' ||  $data_check_xvvip_bonus->qualification_id == 'SVVIP'
                    ||  $data_check_xvvip_bonus->qualification_id == 'MG' ||  $data_check_xvvip_bonus->qualification_id == 'MR' ||  $data_check_xvvip_bonus->qualification_id == 'ME' ||  $data_check_xvvip_bonus->qualification_id == 'MD')) {

                    $f = 0;
                    foreach ($data_user_bonus_4 as $value_bonus_4) {
                        $f++;
                        DB::table('customers')
                            ->where('user_name', $value_bonus_4->user_name)
                            ->update(['vvip_status_runbonus' => 'success']);
                        $user_runbonus[] =  $value_bonus_4->user_name;
                        if ($f == 2) {


                            $code_b4 =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_bonus(4);


                            if (
                                $data_check_xvvip_bonus->qualification_id == 'XVVIP' || $data_check_xvvip_bonus->qualification_id == 'SVVIP' || $data_check_xvvip_bonus->qualification_id == 'MG'
                                || $data_check_xvvip_bonus->qualification_id == 'MR' || $data_check_xvvip_bonus->qualification_id == 'ME' || $data_check_xvvip_bonus->qualification_id == 'MD'
                            ) {
                                $report_bonus_register_b4['user_name'] = $data_user->introduce_id;
                                $introduce_id =  DB::table('customers')
                                ->select('introduce_id')
                                ->where('user_name', '=',$data_user->introduce_id)
                                ->first();
                                $report_bonus_register_b4['introduce_id'] = $introduce_id->introduce_id;
                                $report_bonus_register_b4['name'] =  $data_user->name . ' ' . $data_user->last_name;
                                $report_bonus_register_b4['regis_user_name'] = $data_user->user_name;
                                $report_bonus_register_b4['regis_user_introduce_id'] = $data_user->introduce_id;
                                $report_bonus_register_b4['regis_name'] = $data_user->name . ' ' . $data_user->last_name;
                                // $report_bonus_register_b4['user_upgrad'] = $data_user_uoposition->user_name;
                                $report_bonus_register_b4['user_name_recive_bonus'] = $data_check_xvvip_bonus->user_name;
                                $report_bonus_register_b4['name_recive_bonus'] =  $data_check_xvvip_bonus->name . ' ' . $data_check_xvvip_bonus->last_name;
                                $report_bonus_register_b4['old_position'] = $old_position;
                                $report_bonus_register_b4['new_position'] = $position_update;
                                $report_bonus_register_b4['code_bonus'] = $code_b4;
                                $report_bonus_register_b4['type'] = 'jangpv_1200';
                                $report_bonus_register_b4['user_name_vvip_1'] = $user_runbonus[0];
                                $report_bonus_register_b4['user_name_vvip_2'] = $user_runbonus[1];
                                $report_bonus_register_b4['tax_total'] =  2000 * 3 / 100;
                                $report_bonus_register_b4['bonus_full'] = 2000;
                                $report_bonus_register_b4['bonus'] =  2000 - (2000 * 3 / 100);
                                $report_bonus_register_b4['pv_vvip_1'] =  '1200';
                                $report_bonus_register_b4['pv_vvip_2'] =  '1200';



                                DB::table('report_bonus_register_xvvip')
                                    ->updateOrInsert(
                                        ['regis_user_name' =>  $data_user->user_name, 'user_name' => $data_user->introduce_id],
                                        $report_bonus_register_b4
                                    );

                                $report_bonus_register_xvvip = DB::table('report_bonus_register_xvvip')
                                    ->where('status', '=', 'panding')
                                    ->where('bonus', '>', 0)
                                    ->where('code_bonus', '=', $code_b4)
                                    ->where('regis_user_name', '=', $data_user->user_name)
                                    ->first();



                                $wallet_b4 = DB::table('customers')
                                    ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'bonus_total')
                                    ->where('user_name', $report_bonus_register_xvvip->user_name_recive_bonus)
                                    ->first();

                                if ($wallet_b4->ewallet == '' || empty($wallet_b4->ewallet)) {
                                    $wallet_b4_user = 0;
                                } else {

                                    $wallet_b4_user = $wallet_b4->ewallet;
                                }

                                if ($wallet_b4->bonus_total == '' || empty($wallet_b4->bonus_total)) {
                                    $bonus_total_b4 = 0 + $report_bonus_register_xvvip->bonus;
                                } else {

                                    $bonus_total_b4 = $wallet_b4->bonus_total + $report_bonus_register_xvvip->bonus;
                                }

                                if ($wallet_g->ewallet_use == '' || empty($wallet_g->ewallet_use)) {
                                    $ewallet_use_b4 = 0;
                                } else {

                                    $ewallet_use_b4 = $wallet_g->ewallet_use;
                                }
                                $eWallet_register_b4 = new eWallet();
                                $wallet_total_b4 = $wallet_b4_user +  $report_bonus_register_xvvip->bonus;
                                $ewallet_use_total_b4 =  $ewallet_use_b4 + $report_bonus_register_xvvip->bonus;

                                $eWallet_register_b4->transaction_code =  $report_bonus_register_xvvip->code_bonus;
                                $eWallet_register_b4->customers_id_fk = $data_check_xvvip_bonus->id;
                                $eWallet_register_b4->customer_username = $report_bonus_register_xvvip->user_name_recive_bonus;
                                // $eWallet_register_b4->customers_id_receive = $user->id;
                                // $eWallet_register_b4->customers_name_receive = $user->user_name;
                                $eWallet_register_b4->tax_total = $report_bonus_register_xvvip->tax_total;
                                $eWallet_register_b4->bonus_full = $report_bonus_register_xvvip->bonus_full;
                                $eWallet_register_b4->amt = $report_bonus_register_xvvip->bonus;
                                $eWallet_register_b4->old_balance = $wallet_b4_user;
                                $eWallet_register_b4->balance = $wallet_total_b4;
                                $eWallet_register_b4->type = 11;
                                $eWallet_register_b4->note_orther = 'โบนัสสร้างทีม รหัส ' . $user_runbonus[0] . ' และรหัส ' . $user_runbonus[0] . ' แจงอัพตำแหน่ง 1,200 PV ';
                                $eWallet_register_b4->receive_date = now();
                                $eWallet_register_b4->receive_time = now();
                                $eWallet_register_b4->status = 2;

                                DB::table('customers')
                                    ->where('user_name', $data_check_xvvip_bonus->user_name)
                                    ->update(['ewallet' => $wallet_total_b4, 'ewallet_use' => $ewallet_use_total_b4, 'bonus_total' => $bonus_total_b4]);

                                DB::table('report_bonus_register_xvvip')
                                    ->where('code_bonus', '=', $report_bonus_register_xvvip->code_bonus)
                                    ->where('regis_user_name', '=', $report_bonus_register_xvvip->regis_user_name)
                                    ->update(['status' => 'success']);

                                $eWallet_register_b4->save();

                                DB::table('report_bonus_register_xvvip')
                                    ->where('code_bonus', '=', $report_bonus_register_xvvip->code_bonus)
                                    ->where('regis_user_name', '=', $report_bonus_register_xvvip->regis_user_name)
                                    ->update(['status' => 'success']);

                                $eWallet_register_b4->save();
                            }
                        }
                    }
                }
            }

            if ($rs->pv_upgrad_input < 1200 and $position_update == 'VVIP') {



                $data_user_bonus_4 =  DB::table('customers')
                    ->select(
                        'customers.id',
                        'customers.name',
                        'customers.last_name',
                        'bonus_total',
                        'customers.user_name',
                        'customers.upline_id',
                        'customers.introduce_id',
                        'customers.qualification_id',
                        'customers.pv_upgrad_vvip',
                        'dataset_qualification.id as qualification_id_fk'
                    )
                    ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                    ->where('customers.introduce_id', '=', $data_user->introduce_id)
                    ->where('dataset_qualification.id', '=', 4)
                    ->where('customers.vvip_register_type', '=', 'jangpv_vvip')
                    ->where('customers.vvip_status_runbonus', '=', 'panding')
                    ->limit(2)
                    ->get();

                $data_check_upline =  DB::table('customers')
                    ->select(
                        'customers.id',
                        'customers.name',
                        'customers.last_name',
                        'customers.user_name',
                        'customers.upline_id',
                        'customers.introduce_id',
                        'customers.qualification_id',

                    )
                    ->where('user_name', '=', $data_user->introduce_id)
                    ->first();

                $data_check_xvvip_bonus =  DB::table('customers')
                    ->select(
                        'customers.id',
                        'customers.name',
                        'customers.last_name',
                        'customers.user_name',
                        'customers.upline_id',
                        'customers.introduce_id',
                        'customers.qualification_id',

                    )
                    ->where('user_name', '=', $data_check_upline->introduce_id)
                    ->first();


                if (count($data_user_bonus_4) >= 2 and ($data_check_xvvip_bonus->qualification_id == 'XVVIP' ||  $data_check_xvvip_bonus->qualification_id == 'SVVIP'
                    ||  $data_check_xvvip_bonus->qualification_id == 'MG' ||  $data_check_xvvip_bonus->qualification_id == 'MR' ||  $data_check_xvvip_bonus->qualification_id == 'ME' ||  $data_check_xvvip_bonus->qualification_id == 'MD')) {

                    $f = 0;
                    foreach ($data_user_bonus_4 as $value_bonus_4) {
                        $f++;
                        DB::table('customers')
                            ->where('user_name', $value_bonus_4->user_name)
                            ->update(['vvip_status_runbonus' => 'success']);
                        $user_runbonus[] =  $value_bonus_4->user_name;
                        $pv_upgrad_vvip[] =  $value_bonus_4->pv_upgrad_vvip;
                        if ($f == 2) {


                            $code_b4 = \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_bonus(4);


                            if (
                                $data_check_xvvip_bonus->qualification_id == 'XVVIP' || $data_check_xvvip_bonus->qualification_id == 'SVVIP' || $data_check_xvvip_bonus->qualification_id == 'MG'
                                || $data_check_xvvip_bonus->qualification_id == 'MR' || $data_check_xvvip_bonus->qualification_id == 'ME' || $data_check_xvvip_bonus->qualification_id == 'MD'
                            ) {
                                $report_bonus_register_b4['user_name'] = $data_user->introduce_id;
                                $report_bonus_register_b4['name'] =  $data_user->name . ' ' . $data_user->last_name;
                                $report_bonus_register_b4['regis_user_name'] = $data_user->user_name;
                                $report_bonus_register_b4['regis_name'] = $data_user->name . ' ' . $data_user->last_name;
                                // $report_bonus_register_b4['user_upgrad'] = $data_user_uoposition->user_name;
                                $report_bonus_register_b4['user_name_recive_bonus'] = $data_check_xvvip_bonus->user_name;
                                $report_bonus_register_b4['name_recive_bonus'] =  $data_check_xvvip_bonus->name . ' ' . $data_check_xvvip_bonus->last_name;
                                $report_bonus_register_b4['old_position'] = $old_position;
                                $report_bonus_register_b4['new_position'] = $position_update;
                                $report_bonus_register_b4['user_name_vvip_1'] = $user_runbonus[0];
                                $report_bonus_register_b4['user_name_vvip_2'] = $user_runbonus[1];

                                $report_bonus_register_b4['pv_vvip_1'] =  $pv_upgrad_vvip[0];
                                $report_bonus_register_b4['pv_vvip_2'] =  $pv_upgrad_vvip[1];

                                $report_bonus_register_b4['code_bonus'] = $code_b4;
                                $report_bonus_register_b4['type'] = 'jangpv_vvip';
                                 $pv_sum = ($pv_upgrad_vvip[0] +  $pv_upgrad_vvip[1])*0.5;
                                $report_bonus_register_b4['tax_total'] =  $pv_sum * 3 / 100;
                                $report_bonus_register_b4['bonus_full'] = $pv_sum;
                                $report_bonus_register_b4['bonus'] = $pv_sum - ($pv_sum * 3 / 100);


                                DB::table('report_bonus_register_xvvip')
                                    ->updateOrInsert(
                                        ['regis_user_name' =>  $data_user->user_name, 'user_name' => $data_user->introduce_id],
                                        $report_bonus_register_b4
                                    );

                                $report_bonus_register_xvvip = DB::table('report_bonus_register_xvvip')
                                    ->where('status', '=', 'panding')
                                    ->where('bonus', '>', 0)
                                    ->where('code_bonus', '=', $code_b4)
                                    ->where('regis_user_name', '=', $data_user->user_name)
                                    ->first();



                                $wallet_b4 = DB::table('customers')
                                    ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'bonus_total')
                                    ->where('user_name', $report_bonus_register_xvvip->user_name_recive_bonus)
                                    ->first();

                                if ($wallet_b4->ewallet == '' || empty($wallet_b4->ewallet)) {
                                    $wallet_b4_user = 0;
                                } else {

                                    $wallet_b4_user = $wallet_b4->ewallet;
                                }

                                if ($wallet_b4->bonus_total == '' || empty($wallet_b4->bonus_total)) {
                                    $bonus_total_b4 = 0 + $report_bonus_register_xvvip->bonus;
                                } else {

                                    $bonus_total_b4 = $wallet_b4->bonus_total + $report_bonus_register_xvvip->bonus;
                                }

                                if ($wallet_g->ewallet_use == '' || empty($wallet_g->ewallet_use)) {
                                    $ewallet_use_b4 = 0;
                                } else {

                                    $ewallet_use_b4 = $wallet_g->ewallet_use;
                                }
                                $eWallet_register_b4 = new eWallet();
                                $wallet_total_b4 = $wallet_b4_user +  $report_bonus_register_xvvip->bonus;
                                $ewallet_use_total_b4 =  $ewallet_use_b4 + $report_bonus_register_xvvip->bonus;

                                $eWallet_register_b4->transaction_code =  $report_bonus_register_xvvip->code_bonus;
                                $eWallet_register_b4->customers_id_fk = $data_check_xvvip_bonus->id;
                                $eWallet_register_b4->customer_username = $report_bonus_register_xvvip->user_name_recive_bonus;
                                // $eWallet_register_b4->customers_id_receive = $user->id;
                                // $eWallet_register_b4->customers_name_receive = $user->user_name;
                                $eWallet_register_b4->tax_total = $report_bonus_register_xvvip->tax_total;
                                $eWallet_register_b4->bonus_full = $report_bonus_register_xvvip->bonus_full;
                                $eWallet_register_b4->amt = $report_bonus_register_xvvip->bonus;
                                $eWallet_register_b4->old_balance = $wallet_b4_user;
                                $eWallet_register_b4->balance = $wallet_total_b4;
                                $eWallet_register_b4->type = 11;
                                $eWallet_register_b4->note_orther = 'โบนัสสร้างทีม รหัส ' . $user_runbonus[0] . ' และรหัส ' . $user_runbonus[1] . ' แจงอัพตำแหน่ง VVIP ';
                                $eWallet_register_b4->receive_date = now();
                                $eWallet_register_b4->receive_time = now();
                                $eWallet_register_b4->status = 2;

                                DB::table('customers')
                                    ->where('user_name', $data_check_xvvip_bonus->user_name)
                                    ->update(['ewallet' => $wallet_total_b4, 'ewallet_use' => $ewallet_use_total_b4, 'bonus_total' => $bonus_total_b4]);

                                DB::table('report_bonus_register_xvvip')
                                    ->where('code_bonus', '=', $report_bonus_register_xvvip->code_bonus)
                                    ->where('regis_user_name', '=', $report_bonus_register_xvvip->regis_user_name)
                                    ->update(['status' => 'success']);

                                $eWallet_register_b4->save();

                                DB::table('report_bonus_register_xvvip')
                                    ->where('code_bonus', '=', $report_bonus_register_xvvip->code_bonus)
                                    ->where('regis_user_name', '=', $report_bonus_register_xvvip->regis_user_name)
                                    ->update(['status' => 'success']);

                                $eWallet_register_b4->save();
                            }
                        }
                    }
                }
            }



            DB::table('customers')
                ->where('user_name', $user_action->user_name)
                ->update(['pv' => $pv_balance]);


            DB::commit();

            return redirect('jp_clarify')->withSuccess('เแจงอัพเกรดรหัส' . $data_user->user_name . 'สำเร็จ');
        } catch (Exception $e) {
            DB::rollback();
            return redirect('jp_clarify')->withError('เแจง PV ไม่สำเร็จกรุณาทำรายการไหม่อีกครั้ง');
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
            ->leftjoin('jang_type', 'jang_type.id', '=', 'jang_pv.type')
            ->orderby('jang_pv.id','DESC');

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

    public static function  checkcustomer_upline_upgrad(Request $request)
    {
        //Request $request

        $user_name_upgrad = trim($request->user_name_upgrad);
        $rs_user_use  = trim($request->user_use);

        // if( $user_name_upgrad == $rs_user_use){
        //     $data = ['status'=>'fail','ms'=>'ไม่สามารถปรับตำแหน่งให้ตัวเองได้'];
        //         return $data;
        // }

        $user =  DB::table('customers')
            ->select(
                'customers.pv',
                'customers.id',
                'customers.name',
                'customers.last_name',
                'customers.user_name',
                'customers.qualification_id',
                'customers.expire_date',
                'dataset_qualification.pv_active',
                'customers.introduce_id'
            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', $rs_user_use)
            ->first();

        // if (empty($user->expire_date) || strtotime($user->expire_date) < strtotime(date('Ymd'))) {
        //     $data = ['status'=>'fail','ms'=>'รหัสของคุณไม่มีการ Active ไม่สามารถแจงให้รหัสอื่นได้'];
        //     return $data;
        // }

        // $rs_user_use  = '7492038';
        // $rs_user_name_active = '9519863';
        $rs = array();

        $data_user_name_upgrad =  DB::table('customers')
            ->select(
                'customers.pv',
                'customers.id',
                'customers.name',
                'customers.last_name',
                'customers.pv_upgrad',
                'customers.user_name',
                'customers.qualification_id',
                'customers.expire_date',
                'dataset_qualification.id as position_id',
                'dataset_qualification.pv_active',
                'customers.introduce_id'
            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', $user_name_upgrad)
            ->first();
        //9519863



        if (!empty($data_user_name_upgrad) and $data_user_name_upgrad->name != '') {

            if ($data_user_name_upgrad->position_id >= 4) {
                $data = ['status' => 'fail', 'rs' => $rs, 'ms' => 'ไม่สามารถอัพตำแหน่ง ' . $user_name_upgrad . ' สูงกว่า VVIP ได้'];
                return $data;
            }

            if (empty($data_user_name_upgrad->expire_date) || strtotime($data_user_name_upgrad->expire_date) < strtotime(date('Ymd'))) {
                if (empty($data_user_name_upgrad->expire_date)) {
                    $date_mt_active = 'Not Active';
                } else {
                    //$date_mt_active= date('d/m/Y',strtotime(Auth::guard('c_user')->user()->expire_date));
                    $date_mt_active = 'Not Active';
                }
                $status = 'danger';
                $data = ['status' => 'fail', 'ms' => 'รหัสสมาชิกยังไม่ Active ไม่สามารถอัพตำแหน่งได้'];
                return $data;
            } else {
                $date_mt_active = 'Active ' . date('d/m/Y', strtotime(Auth::guard('c_user')->user()->expire_date));
                $status = 'success';
            }

            $name = $data_user_name_upgrad->name . ' ' . $data_user_name_upgrad->last_name;


            if ($data_user_name_upgrad->introduce_id == $rs_user_use || $rs_user_use == $user_name_upgrad) {


                if ($data_user_name_upgrad->pv_upgrad) {
                    $pv_upgrad = $data_user_name_upgrad->pv_upgrad;
                } else {
                    $pv_upgrad = 0;
                }
                $pv_mo = 400;
                $pv_vip = 800;
                $pv_vvip = 1200;
                $pv_upgrad_total_mo = $pv_mo - $data_user_name_upgrad->pv_upgrad;
                $pv_upgrad_total_vip = $pv_vip - $data_user_name_upgrad->pv_upgrad;
                $pv_upgrad_total_vvip = $pv_vvip - $data_user_name_upgrad->pv_upgrad;
                if ($data_user_name_upgrad->position_id == 1) {

                    $html = '<p class="small text-danger mb-0"> ' . $pv_upgrad_total_mo . ' PV ขึ้นตำแหน่ง  MO</p>
                    <p class="small text-danger mb-0"> ' . $pv_upgrad_total_vip . ' PV ขึ้นตำแหน่ง VIP</p>
                    <p class="small text-danger mb-0"> ' . $pv_upgrad_total_vvip . ' PV ขึ้นตำแหน่ง VVIP</p>';
                } elseif ($data_user_name_upgrad->position_id == 2) {
                    $html = '<p class="small text-danger mb-0"> ' . $pv_upgrad_total_vip . ' PV ขึ้นตำแหน่ง VIP</p>
                    <p class="small text-danger mb-0"> ' . $pv_upgrad_total_vvip . ' PV ขึ้นตำแหน่ง VVIP</p>';
                } elseif ($data_user_name_upgrad->position_id == 3) {
                    $html = '<p class="small text-danger mb-0"> ' . $pv_upgrad_total_vvip . ' PV ขึ้นตำแหน่ง VVIP</p>';
                } else {
                    $html = '<p class="small text-danger mb-0"> ตำแหน่งไม่ถูกต้องกรุณาติดต่อเจ้าหน้าที่ </p>';
                }

                $data = [
                    'status' => 'success', 'user_name' => $data_user_name_upgrad->user_name, 'pv_upgrad' => $pv_upgrad,
                    'name' => $name, 'position' => $data_user_name_upgrad->qualification_id . ' (สะสม ' . $pv_upgrad . ' PV)', 'pv_active' => $data_user_name_upgrad->pv_active, 'date_active' => $date_mt_active, 'rs' => $rs, 'ms' => 'Success', 'html' => $html
                ];
                return $data;
            } else {
                $data = ['status' => 'fail', 'rs' => $rs, 'ms' => 'ต้องเป็นรหัสสมาชิกที่เป็นสายตรงเท่านั้น'];
                return $data;
            }
        } else {
            $data = ['status' => 'fail', 'ms' => 'รหัสสมาชิกไม่ถูกต้อง'];
            return $data;
        }
    }
}
