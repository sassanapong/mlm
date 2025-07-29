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

            $customer_update = Customers::lockForUpdate()->find($user->id);



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

            if ($user->pv_upgrad >= 1200) {
                $customer_update->pv_upgrad = $user->pv_upgrad +  $rs->pv;
            }


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


                            $wallet_g = Customers::lockForUpdate()
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

                            $wallet_g->ewallet = $wallet_g_total;
                            $wallet_g->ewallet_use = $ewallet_use_total;
                            $wallet_g->save();

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
                return redirect('jp_clarify')->withError($e->getMessage());
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
                'customers.pv_upgrad',
                'customers.last_name',
                'customers.user_name',
                'customers.qualification_id',
                'customers.expire_date',
                'customers.expire_date_bonus',
                'dataset_qualification.pv_active',
                'customers.status_customer',

            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', $rs->input_user_name_active)
            ->first();



        if (empty($data_user)) {
            return redirect('jp_clarify')->withError('เแจง PV ไม่สำเร็จกรุณาทำรายการไหม่อีกครั้ง');
        }

        if ($data_user->status_customer == 'cancel') {
            return redirect('jp_clarify')->withError('รหัสนี้ถูกยกเลิกเเล้วไม่สามารถทำรายการได้');
        }
        $customer_update_use = Customers::lockForUpdate()->find($wallet_g->id);
        $customer_update = Customers::lockForUpdate()->find($data_user->id);
        if ($data_user->qualification_id == '' || $data_user->qualification_id == null || $data_user->qualification_id == '-') {
            $qualification_id = 'MC';
        } else {
            $qualification_id = $data_user->qualification_id;
        }

        $pv_balance = $wallet_g->pv - $rs->pv_active;


        if ($pv_balance < 0) {
            return redirect('jp_clarify')->withError('PV ไม่พอสำหรับการแจง');
        }


        if ($data_user->pv_upgrad >= 1200) {
            $customer_update->pv_upgrad = $data_user->pv_upgrad +  $rs->pv_active;
        }


        $customer_update_use->pv = $pv_balance;

        if ($rs->pv_active == 20) {
            if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
                $start_month = date('Y-m-d');
                $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                $customer_update->expire_date = date('Y-m-d', $mt_mount_new);
            } else {
                $start_month = $data_user->expire_date;
                $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                $customer_update->expire_date = date('Y-m-d', $mt_mount_new);
            }
        }


        if ($rs->pv_active == 80) {
            if (empty($data_user->expire_date_bonus) || strtotime($data_user->expire_date_bonus) < strtotime(date('Ymd'))) {
                $start_month = date('Y-m-d');
                $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                $customer_update->expire_date_bonus = date('Y-m-d', $mt_mount_new);
            } else {
                $start_month = $data_user->expire_date_bonus;
                $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                $customer_update->expire_date_bonus = date('Y-m-d', $mt_mount_new);
            }
        }



        $code =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_pv();

        $jang_pv['code'] = $code;
        $jang_pv['customer_username'] = Auth::guard('c_user')->user()->user_name;
        $jang_pv['to_customer_username'] = $data_user->user_name;
        $jang_pv['position'] = $data_user->qualification_id;
        $jang_pv['date_active'] =  date('Y-m-d', $mt_mount_new);
        $jang_pv['bonus_percen'] = 100;
        $jang_pv['pv_old'] = $data_user->pv;
        $jang_pv['pv'] = $rs->pv_active;
        $jang_pv['pv_balance'] =  $pv_balance;

        $bonusfull = $rs->pv_active * (100 / 100);
        $pv_to_price =  $bonusfull - ($bonusfull * (3 / 100));

        $jang_pv['wallet'] =  $pv_to_price;
        $jang_pv['type'] =  '1';
        $jang_pv['status'] =  'Success';

        $eWallet = new eWallet();
        $eWallet->transaction_code = $code;
        $eWallet->customers_id_fk = Auth::guard('c_user')->user()->id;
        $eWallet->customer_username = Auth::guard('c_user')->user()->user_name;
        $eWallet->customers_id_receive =  $data_user->id;
        $eWallet->customers_name_receive =  $data_user->user_name;
        $eWallet->tax_total =  $bonusfull  * 3 / 100;
        $eWallet->bonus_full =  $bonusfull;
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

            $RunBonusActive = \App\Http\Controllers\Frontend\BonusActiveController::RunBonusActive($code, $customer_username, $to_customer_username);

            if ($RunBonusActive == true) {
                $report_bonus_active = DB::table('report_bonus_active')
                    ->where('code', '=', $code)
                    ->get();

                foreach ($report_bonus_active as $value) {

                    if ($value->bonus > 0) {

                        $wallet_g = Customers::lockForUpdate()
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

                        $wallet_g_total = $wallet_g_user +  $value->bonus;
                        $ewallet_use_total =  $ewallet_use + $value->bonus;

                        $eWallet_active = new eWallet();
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

                        $wallet_g->ewallet = $wallet_g_total;
                        $wallet_g->ewallet_use = $ewallet_use_total;
                        $wallet_g->save();


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
            return redirect('jp_clarify')->withError($e->getMessage());
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
            ->select(
                'customers.pv',
                'customers.id',
                'customers.name',
                'customers.last_name',
                'customers.user_name',
                'ewallet',
                'customers.status_customer',
            )
            ->where('user_name', '=', $username_pv_tranfer_recive)
            ->first();

        if (Auth::guard('c_user')->user()->user_name == $username_pv_tranfer_recive) {
            return redirect('jp_clarify')->withError('ไม่สามารถทำรายการให้ตัวเองได้');
        }

        if ($data_user->status_customer == 'cancel') {
            return redirect('jp_clarify')->withError('รหัสนี้ถูกยกเลิกเเล้วไม่สามารถทำรายการได้');
        }

        $pv_tranfer = $rs->pv_tranfer;
        if ($pv_tranfer <= 0) {
            return redirect('jp_clarify')->withError('PV เป็น 0 กรุณาทำรายการไหม่');
        }

        if (empty($data_user)) {
            return redirect('jp_clarify')->withError('โอน PV ไม่สำเร็จกรุณาทำรายการไหม่อีกครั้ง');
        }
        $customer_update_use = Customers::lockForUpdate()->find($wallet_g->id);
        $customer_update = Customers::lockForUpdate()->find($data_user->id);




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



        $user_action = Customers::lockForUpdate()
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
                'customers.status_customer',

            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', $rs->input_user_name_upgrad)
            ->first();

        $expire_date = $data_user->expire_date;



        $old_position = $data_user->qualification_id;



        if (empty($data_user)) {
            return redirect('jp_clarify')->withError(' แจง PV ไม่สำเร็จกรุณาทำรายการไหม่อีกครั้ง');
        }

        if ($data_user->status_customer == 'cancel') {
            return redirect('jp_clarify')->withError('รหัสนี้ถูกยกเลิกเเล้วไม่สามารถทำรายการได้');
        }

        $pv_balance = $user_action->pv - $rs->pv_upgrad_input;
        if ($pv_balance < 0) {
            return redirect('jp_clarify')->withError('PV ไม่พอสำหรับการแจงอัพตำแหน่ง');
        }


        // $customer_update_use = Customers::find($user_action->id);
        // $customer_update = Customers::find($data_user->id);
        if ($data_user->qualification_id == '' || $data_user->qualification_id == null || $data_user->qualification_id == '-') {
            $qualification_id = 'MC';
        } else {
            $qualification_id = $data_user->qualification_id;
        }


        $pv_upgrad_total = $data_user->pv_upgrad + $rs->pv_upgrad_input;
        if ($data_user->qualification_id == 'MC') {
            if ($pv_upgrad_total >= 20 and $pv_upgrad_total < 400) { //อัพ MO
                if ($rs->pv_upgrad_input >=  20) {
                    if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
                        $start_month = date('Y-m-d');
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    } else {
                        $start_month = $data_user->expire_date;
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    }
                }

                $position_update = 'MB';
            } elseif ($pv_upgrad_total >= 400 and $pv_upgrad_total < 800) { //อัพ MO
                if ($rs->pv_upgrad_input >=  400) {
                    if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
                        $start_month = date('Y-m-d');
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    } else {
                        $start_month = $data_user->expire_date;
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    }
                }

                $position_update = 'MO';
            } elseif ($pv_upgrad_total >= 800 and $pv_upgrad_total  < 1200) { //vip
                if ($rs->pv_upgrad_input >=  400) {
                    if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
                        $start_month = date('Y-m-d');
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    } else {
                        $start_month = $data_user->expire_date;
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    }
                }
                $position_update = 'VIP';
            } elseif ($pv_upgrad_total >= 1200) { //vvip
                if ($rs->pv_upgrad_input >=  400) {
                    if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
                        $start_month = date('Y-m-d');
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    } else {
                        $start_month = $data_user->expire_date;
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    }
                }
                $position_update = 'VVIP';
            } else { //อัพ pv_upgrad
                $position_update = $data_user->qualification_id;
            }
        } elseif ($data_user->qualification_id == 'MB') {

            if ($pv_upgrad_total >= 400 and $pv_upgrad_total < 800) { //อัพ MO
                if ($rs->pv_upgrad_input >=  400) {
                    if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
                        $start_month = date('Y-m-d');
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    } else {
                        $start_month = $data_user->expire_date;
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    }
                }

                $position_update = 'MO';
            } elseif ($pv_upgrad_total >= 800 and $pv_upgrad_total  < 1200) { //vip
                if ($rs->pv_upgrad_input >=  400) {
                    if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
                        $start_month = date('Y-m-d');
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    } else {
                        $start_month = $data_user->expire_date;
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    }
                }
                $position_update = 'VIP';
            } elseif ($pv_upgrad_total >= 1200) { //vvip
                if ($rs->pv_upgrad_input >=  400) {
                    if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
                        $start_month = date('Y-m-d');
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    } else {
                        $start_month = $data_user->expire_date;
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    }
                }
                $position_update = 'VVIP';
            } else { //อัพ pv_upgrad
                $position_update = $data_user->qualification_id;
            }
        } elseif ($data_user->qualification_id == 'MO') {

            if ($pv_upgrad_total >= 800 and $pv_upgrad_total  < 1200) { //vip
                if ($rs->pv_upgrad_input >=  400) {
                    if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
                        $start_month = date('Y-m-d');
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    } else {
                        $start_month = $data_user->expire_date;
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    }
                }
                $position_update = 'VIP';
            } elseif ($pv_upgrad_total >= 1200) { //vvip
                if ($rs->pv_upgrad_input >=  400) {
                    if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
                        $start_month = date('Y-m-d');
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    } else {
                        $start_month = $data_user->expire_date;
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    }
                }
                $position_update = 'VVIP';
            } else { //อัพ pv_upgrad
                $position_update = $data_user->qualification_id;
            }
        } elseif ($data_user->qualification_id == 'VIP') {
            if ($pv_upgrad_total >= 1200) { //vvip
                if ($rs->pv_upgrad_input >=  400) {
                    if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
                        $start_month = date('Y-m-d');
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    } else {
                        $start_month = $data_user->expire_date;
                        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
                        $expire_date = date('Y-m-d', $mt_mount_new);
                    }
                }
                $position_update = 'VVIP';
                // เพิ่ม 33 วัน
            } else { //อัพ pv_upgrad
                $position_update = $data_user->qualification_id;
            }
        } else {

            $position_update =   $data_user->qualification_id;
            // return $resule;
        }


        // dd($expire_date);

        $customer_username = $data_user->introduce_id;
        $arr_user = array();
        $report_bonus_register = array();

        $code_bonus = \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_bonus(2);

        for ($i = 1; $i <= 3; $i++) {
            $x = 'start';
            $run_data_user =  DB::table('customers')
                ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
                // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                ->where('user_name', '=', $customer_username)
                ->first();


            // dd($customer_username);

            if (empty($run_data_user)) {
                $i = 3;
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
                            $qualification_id = 'MC';
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
                            $report_bonus_register[$i]['percen'] = 125;

                            $arr_user[$i]['pv'] = $rs->pv_upgrad_input;
                            $arr_user[$i]['position'] = $qualification_id;


                            if ($qualification_id == 'MC') {
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } else {
                                $wallet_total = $rs->pv_upgrad_input * 125 / 100;
                                $arr_user[$i]['bonus'] = $wallet_total;
                                $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                            }
                        } elseif ($i == 2) {
                            $report_bonus_register[$i]['percen'] = 17;
                            $arr_user[$i]['pv'] = $rs->pv_upgrad_input;
                            $arr_user[$i]['position'] = $qualification_id;
                            if ($qualification_id == 'MC') {
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } else {

                                $wallet_total = $rs->pv_upgrad_input * 17 / 100;
                                $arr_user[$i]['bonus'] = $wallet_total;
                                $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                            }
                        } elseif ($i == 3) {
                            $report_bonus_register[$i]['percen'] = 8;
                            $arr_user[$i]['pv'] = $rs->pv_upgrad_input;
                            $arr_user[$i]['position'] = $qualification_id;
                            if ($qualification_id == 'MC') {
                                $report_bonus_register[$i]['tax_total'] = 0;
                                $report_bonus_register[$i]['bonus_full'] = 0;
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } else {

                                $wallet_total = $rs->pv_upgrad_input * 8 / 100;
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

            $b = 0;
            foreach ($db_bonus_register as $value) {
                $b++;
                if ($value->bonus > 0) {

                    $wallet_g = Customers::lockForUpdate()
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


                    $eWallet_register[$b] = new eWallet();
                    $wallet_g_total = $wallet_g_user + $value->bonus;
                    $ewallet_use_total =  $ewallet_use + $value->bonus;

                    $eWallet_register[$b]->transaction_code = $code_bonus;
                    $eWallet_register[$b]->customers_id_fk = $wallet_g->id;
                    $eWallet_register[$b]->customer_username = $value->user_name_g;
                    // $eWallet_register->customers_id_receive = $user->id;
                    // $eWallet_register->customers_name_receive = $user->user_name;
                    $eWallet_register[$b]->tax_total = $value->tax_total;
                    $eWallet_register[$b]->bonus_full = $value->bonus_full;
                    $eWallet_register[$b]->amt = $value->bonus;
                    $eWallet_register[$b]->old_balance = $wallet_g_user;
                    $eWallet_register[$b]->balance = $wallet_g_total;
                    $eWallet_register[$b]->type = 10;
                    $eWallet_register[$b]->note_orther = 'โบนัสขยายธุรกิจ รหัส ' . $value->user_name . ' แนะนำรหัส ' . $value->regis_user_name;
                    $eWallet_register[$b]->receive_date = now();
                    $eWallet_register[$b]->receive_time = now();
                    $eWallet_register[$b]->status = 2;

                    // DB::table('customers')
                    //     ->where('user_name', $value->user_name_g)
                    //     ->update(['ewallet' => $wallet_g_total, 'ewallet_use' => $ewallet_use_total, 'bonus_total' => $bonus_total]);

                    $wallet_g->ewallet = $wallet_g_total;
                    $wallet_g->ewallet_use = $ewallet_use_total;
                    $wallet_g->bonus_total = $bonus_total;
                    $wallet_g->save();


                    DB::table('report_bonus_register')
                        ->where('user_name_g',  $value->user_name_g)
                        ->where('code_bonus', '=', $code_bonus)
                        ->where('regis_user_name', '=', $value->regis_user_name)
                        ->where('g', '=', $value->g)
                        ->update(['status' => 'success']);

                    $eWallet_register[$b]->save();
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
                    'user_name' => $data_user->user_name,
                    'introduce_id' => $data_user->introduce_id,
                    'old_lavel' => $data_user->qualification_id,
                    'new_lavel' => $position_update,
                    'status' => 'success',
                    'type' => 'jangpv'
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

                    if ($old_position == 'MC') {
                        if ($position_update == 'MB') {
                            DB::table('customers')
                                ->where('user_name', $data_user->user_name)
                                ->update([
                                    'qualification_id' => $position_update,
                                    'pv_upgrad' => $pv_upgrad_total,
                                    'expire_date' => $expire_date,
                                    'vvip_register_type' => 'jangpv1200'
                                ]);
                        } else {

                            if (empty($data_user->expire_date_bonus) || strtotime($data_user->expire_date_bonus) < strtotime(date('Ymd'))) {
                                $start_month_bonus = date('Y-m-d');
                                $mt_mount_new_bonus = strtotime("+33 Day", strtotime($start_month_bonus));
                                $expire_date_bonus = date('Y-m-d', $mt_mount_new_bonus);
                            } else {
                                $start_month_bonus = $data_user->expire_date_bonus;
                                $mt_mount_new_bonus = strtotime("+33 Day", strtotime($start_month_bonus));
                                $expire_date_bonus = date('Y-m-d', $mt_mount_new_bonus);
                            }


                            DB::table('customers')
                                ->where('user_name', $data_user->user_name)
                                ->update([
                                    'qualification_id' => $position_update,
                                    'pv_upgrad' => $pv_upgrad_total,
                                    'expire_date' => $expire_date,
                                    'expire_date_bonus' =>  $expire_date_bonus,
                                    'vvip_register_type' => 'jangpv1200'
                                ]);
                        }
                    } else {
                        DB::table('customers')
                            ->where('user_name', $data_user->user_name)
                            ->update([
                                'qualification_id' => $position_update,
                                'pv_upgrad' => $pv_upgrad_total,
                                'vvip_register_type' => 'jangpv1200'
                            ]);
                    }
                } else {
                    $insert_jangpv = Jang_pv::create($jang_pv);

                    if ($old_position == 'MC') {
                        //เพิ่มระบบไหม่ ถ้าตำแหน่ง MC


                        if ($position_update == 'MB') {

                            DB::table('customers')
                                ->where('user_name', $data_user->user_name)
                                ->update([
                                    'qualification_id' => $position_update,
                                    'expire_date' => $expire_date,
                                    'pv_upgrad' => $pv_upgrad_total,
                                    'vvip_register_type' => 'jangpv_vvip',
                                    'pv_upgrad_vvip' => $rs->pv_upgrad_input
                                ]);
                        } else {

                            if (empty($data_user->expire_date_bonus) || strtotime($data_user->expire_date_bonus) < strtotime(date('Ymd'))) {
                                $start_month_bonus = date('Y-m-d');
                                $mt_mount_new_bonus = strtotime("+33 Day", strtotime($start_month_bonus));
                                $expire_date_bonus = date('Y-m-d', $mt_mount_new_bonus);
                            } else {
                                $start_month_bonus = $data_user->expire_date_bonus;
                                $mt_mount_new_bonus = strtotime("+33 Day", strtotime($start_month_bonus));
                                $expire_date_bonus = date('Y-m-d', $mt_mount_new_bonus);
                            }


                            DB::table('customers')
                                ->where('user_name', $data_user->user_name)
                                ->update([
                                    'qualification_id' => $position_update,
                                    'expire_date' => $expire_date,
                                    'expire_date_bonus' => $expire_date_bonus,
                                    'pv_upgrad' => $pv_upgrad_total,
                                    'vvip_register_type' => 'jangpv_vvip',
                                    'pv_upgrad_vvip' => $rs->pv_upgrad_input
                                ]);
                        }
                    } else {
                        DB::table('customers')
                            ->where('user_name', $data_user->user_name)
                            ->update([
                                'qualification_id' => $position_update,
                                'pv_upgrad' => $pv_upgrad_total,
                                'vvip_register_type' => 'jangpv_vvip',
                                'pv_upgrad_vvip' => $rs->pv_upgrad_input
                            ]);
                    }
                }
            } else {
                $insert_jangpv = Jang_pv::create($jang_pv);

                if ($old_position == 'MC') {
                    if ($position_update == 'MB') {
                        DB::table('customers')
                            ->where('user_name', $data_user->user_name)
                            ->update([
                                'qualification_id' => $position_update,
                                'expire_date' => $expire_date,
                                'pv_upgrad' => $pv_upgrad_total
                            ]);
                    } else {

                        if (empty($data_user->expire_date_bonus) || strtotime($data_user->expire_date_bonus) < strtotime(date('Ymd'))) {
                            $start_month_bonus = date('Y-m-d');
                            $mt_mount_new_bonus = strtotime("+33 Day", strtotime($start_month_bonus));
                            $expire_date_bonus = date('Y-m-d', $mt_mount_new_bonus);
                        } else {
                            $start_month_bonus = $data_user->expire_date_bonus;
                            $mt_mount_new_bonus = strtotime("+33 Day", strtotime($start_month_bonus));
                            $expire_date_bonus = date('Y-m-d', $mt_mount_new_bonus);
                        }

                        DB::table('customers')
                            ->where('user_name', $data_user->user_name)
                            ->update([
                                'qualification_id' => $position_update,
                                'expire_date' => $expire_date,
                                'expire_date_bonus' => $expire_date_bonus,
                                'pv_upgrad' => $pv_upgrad_total
                            ]);
                    }
                } else {
                    DB::table('customers')
                        ->where('user_name', $data_user->user_name)
                        ->update(['qualification_id' => $position_update, 'pv_upgrad' => $pv_upgrad_total]);
                }
            }


            $user_action->pv = $pv_balance;
            $user_action->save();

            $check_upline =  DB::table('customers')
                ->where('user_name', $data_user->user_name)
                ->first();

            if ($check_upline and empty($check_upline->upline_id) and empty($check_upline->uni_id) and $old_position == 'MC') {

                $data_upline = \App\Http\Controllers\Frontend\FC\UplineController::uplineAB($check_upline->introduce_id);

                if ($data_upline['status'] == 'fail') {

                    DB::rollback();
                    return redirect('jp_clarify')->withError('ลงทะเบียนไม่สำเร็จไม่สามารถหาสายงาน Upline ได้');
                }
                $data_uni = \App\Http\Controllers\Frontend\FC\UnilevelController::uplineAB($check_upline->introduce_id);

                if ($data_uni['status'] == 'fail') {
                    DB::rollback();
                    return redirect('jp_clarify')->withError('ลงทะเบียนไม่สำเร็จไม่สามารถหาสายงานได้');
                }

                DB::table('customers')
                    ->where('user_name', $data_user->user_name)
                    ->update([
                        'upline_id' => $data_upline['upline_id'],
                        'uni_id' => $data_uni['uni_id'],
                        'type_upline_uni' => $data_uni['type_upline_uni'],
                        'type_upline' => $data_upline['type'],
                    ]);
            }

            DB::commit();

            return redirect('jp_clarify')->withSuccess('เแจงอัพเกรดรหัส' . $data_user->user_name . 'สำเร็จ');
        } catch (Exception $e) {
            DB::rollback();
            return redirect('jp_clarify')->withError($e->getMessage());
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
            ->orderby('jang_pv.id', 'DESC');

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
                if ($row->type == 5 || $row->type == 3) {
                    if ($row->code_order) {
                        $data = '<a href="' . route('order_detail', ['code_order' => $row->code_order]) . '" class="btn btn-sm btn-outline-primary">' . $row->code_order . '</a>';
                        return $data;
                    } else {
                        return '';
                    }
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
                'customers.expire_date_bonus',
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
                'customers.expire_date_bonus',
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

            // if (empty($data_user_name_upgrad->expire_date) || strtotime($data_user_name_upgrad->expire_date) < strtotime(date('Ymd'))) {
            //     if (empty($data_user_name_upgrad->expire_date)) {
            //         $date_mt_active = 'Not Active';
            //     } else {
            //         //$date_mt_active= date('d/m/Y',strtotime(Auth::guard('c_user')->user()->expire_date));
            //         $date_mt_active = 'Not Active';
            //     }
            //     $status = 'danger';
            //     $data = ['status' => 'fail', 'ms' => 'รหัสสมาชิกยังไม่ Active ไม่สามารถอัพตำแหน่งได้'];
            //     return $data;
            // } else {
            //     $date_mt_active = 'Active ' . date('d/m/Y', strtotime(Auth::guard('c_user')->user()->expire_date));
            //     $status = 'success';
            // }

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
                if ($data_user_name_upgrad->position_id == 0) {
                    $pv_upgrad_total_mb = 20 - $data_user_name_upgrad->pv_upgrad;
                    $pv_upgrad_total_mo = $pv_mo - $data_user_name_upgrad->pv_upgrad;
                    $pv_upgrad_total_vip = $pv_vip - $data_user_name_upgrad->pv_upgrad;
                    $pv_upgrad_total_vvip = $pv_vvip - $data_user_name_upgrad->pv_upgrad;

                    $html = '
                    <p class="small text-danger mb-0"> ' . $pv_upgrad_total_mb . ' PV ขึ้นตำแหน่ง  MB</p>
                    <p class="small text-danger mb-0"> ' . $pv_upgrad_total_mo . ' PV ขึ้นตำแหน่ง  MO</p>
                    <p class="small text-danger mb-0"> ' . $pv_upgrad_total_vip . ' PV ขึ้นตำแหน่ง VIP</p>
                    <p class="small text-danger mb-0"> ' . $pv_upgrad_total_vvip . ' PV ขึ้นตำแหน่ง VVIP</p>';
                } elseif ($data_user_name_upgrad->position_id == 1) {

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
                    'status' => 'success',
                    'user_name' => $data_user_name_upgrad->user_name,
                    'pv_upgrad' => $pv_upgrad,
                    'name' => $name,
                    'position' => $data_user_name_upgrad->qualification_id . ' (สะสม ' . $pv_upgrad . ' PV)',
                    'pv_active' => $data_user_name_upgrad->pv_active,
                    'rs' => $rs,
                    'ms' => 'Success',
                    'html' => $html
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
