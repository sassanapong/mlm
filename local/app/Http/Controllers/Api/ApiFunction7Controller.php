<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use App\Orders;
use App\Customers;
use App\Order_products_list;
use App\Jang_pv;
use App\eWallet;
use App\Log_insurance;

class ApiFunction7Controller extends Controller
{


    public function payment_submit(Request $rs)
    {
        $insert_db_orders = new Orders();
        $insert_order_products_list = new Order_products_list();

        $quantity = $rs->quantity;
        $insert_db_orders->quantity = $quantity;

        $customer_id = $rs->customer_id_fk;

        $code_order = \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_order();

        $insert_db_orders->customers_id_fk = $customer_id;
        $insert_db_orders->tracking_type = $rs->tracking_type;
        $user_name = $rs->customer_user_name;
        $insert_db_orders->customers_user_name = $user_name;
        //$business_location_id = Auth::guard('c_user')->user()->business_location_id;
        $business_location_id = 1;
        $insert_db_orders->business_location_id_fk =  $business_location_id;

        if ($insert_db_orders->sent_type_to_customer == 'sent_type_other') {
            $insert_db_orders->customers_sent_id_fk = $rs->customers_sent_id_fk;
            $insert_db_orders->customers_sent_user_name = $rs->customers_sent_user_name;
            $insert_db_orders->status_payment_sent_other = 1;
        } else {
            $insert_db_orders->status_payment_sent_other = 0;
        }


        $insert_db_orders->address_sent = 'system';

        if (empty($rs->province_id) || empty($rs->zipcode)) {


            return response()->json([
                'message' => 'กรุณากรอกที่อยู่ก่อนทำการซื้อสินค้า',
                'status' => 'error',
                'code' => 'ER02',
                'data' => null,
            ], 500);
        }
        $insert_db_orders->delivery_province_id = $rs->province_id;
        $insert_db_orders->house_no = $rs->house_no;
        // $insert_db_orders->house_name = 'system';
        $insert_db_orders->moo = $rs->moo;
        $insert_db_orders->soi = $rs->soi;
        $insert_db_orders->road = $rs->road;
        $insert_db_orders->tambon_id = $rs->tambon_id;
        $insert_db_orders->district_id = $rs->district_id;
        $insert_db_orders->province_id = $rs->province_id;
        $insert_db_orders->zipcode = $rs->zipcode;


        $insert_db_orders->tel = $rs->phone;
        $insert_db_orders->name = $rs->name;
        // $insert_db_orders->pay_type = $rs->type_pay;
        // $insert_db_orders->pay_type = 'e-wallet';

        // dd($insert_db_orders->toArray());

        // $location = Location::location($business_location_id, $business_location_id);
        // $location = '';
        $insert_db_orders->api = 'yes';

        $data = $rs->product_id_fk;


        if ($quantity  == 0) {

            return response()->json([
                'message' => 'สั่งซื้อไม่เสร็จ กรุณาทำรายการไหม่',
                'status' => 'error',
                'code' => 'ER02',
                'data' => null,
            ], 500);
        }
        $i = 0;
        $products_list = array();
        $total_price_all =  array();

        $newAmt = [];
        foreach ($rs->amt as $item) {
            foreach ($item as $key => $value) {
                $newAmt[$key] = $value;
            }
        }


        if ($data) {
            foreach ($data as $value) {


                $product = DB::table('products')
                    ->select(
                        'products.id as products_id',
                        'products_details.*',
                        'products_images.*',
                        'products_cost.*',
                        'dataset_currency.*',
                        'dataset_product_unit.product_unit as product_unit_name',
                        'dataset_product_unit.id as product_unit_id'
                    )
                    ->leftjoin('products_details', 'products.id', '=', 'products_details.product_id_fk')
                    ->leftjoin('products_images', 'products.id', '=', 'products_images.product_id_fk')
                    ->leftjoin('products_cost', 'products.id', '=', 'products_cost.product_id_fk')
                    ->leftjoin('dataset_currency', 'dataset_currency.id', '=', 'products_cost.currency_id')
                    ->leftjoin('dataset_product_unit', 'dataset_product_unit.product_unit_id', '=', 'products.unit_id')
                    ->where('products.id', '=', $value)
                    ->where('products_images.image_default', '=', 1)
                    ->where('products_details.lang_id', '=', 1)
                    ->where('products.status', '=', 1)
                    ->where('products_cost.business_location_id', '=', 1)
                    ->first();



                $i++;
                $total_pv = $product->pv * $newAmt[$value];
                $total_price = $product->member_price * $newAmt[$value];

                $insert_db_products_list[] = [
                    'code_order' => $code_order,
                    'product_id_fk' => $value,
                    'product_unit_id_fk' => $product->product_unit_id,
                    'customers_username' =>  $user_name,
                    'selling_price' =>  $product->member_price,
                    'product_name' => $product->product_name,
                    'amt' => $newAmt[$value],
                    'pv' =>   $product->pv,
                    'total_pv' => $total_pv,
                    'total_price' => $total_price,
                ];
                $product_id[] = $value;

                $pv[] = $newAmt[$value] * $product->pv;


                $product_shipping = DB::table('products_cost')
                    ->where('product_id_fk', $value)
                    ->where('status_shipping', 'Y')
                    ->first();

                $total_price_all[] =  $total_price;


                if ($product_shipping) {
                    //$pv_shipping_arr[] = $value['quantity'] * $product_shipping->pv;
                    $pv_shipping_arr[] = $newAmt[$value] * 20;
                } else {
                    $pv_shipping_arr[] = 0;
                }
            }
            $pv_shipping = array_sum($pv_shipping_arr);
            $pv_total = array_sum($pv);
            $price = array_sum($total_price_all);
        } else {
            $pv_shipping = 0;
            $pv_total = 0;
            $price = 0;
        }

        //ราคาสินค้า

        $vat = DB::table('dataset_vat')
            ->where('business_location_id_fk', '=', $business_location_id)
            ->first();

        $vat = $vat->vat;
        //vatใน 7%
        $p_vat = $price * ($vat / (100 + $vat));
        //มูลค่าสินค้า
        $price_vat = $price - $p_vat;
        $insert_db_orders->product_value = $price_vat;

        $shipping = \App\Http\Controllers\Frontend\ShippingController::fc_shipping($pv_shipping);
        $shipping_zipcode = \App\Http\Controllers\Frontend\ShippingController::fc_shipping_zip_code($insert_db_orders->zipcode);
        $shipping_total = $shipping + $shipping_zipcode['price'];

        if ($shipping_total == 0) {
            $insert_db_orders->shipping_free = 1; //ส่งฟรี
            $insert_db_orders->shipping_cost_id_fk = 1;
            $shipping_cost_name = DB::table('dataset_shipping_cost')
                ->where('id', 1)
                ->first();
        } else {
            if ($shipping_zipcode['status'] == 'success') {
                $insert_db_orders->shipping_cost_id_fk = 3;
                $shipping_cost_name = DB::table('dataset_shipping_cost')
                    ->where('id', 3)
                    ->first();
            } else {
                $insert_db_orders->shipping_cost_id_fk = 2;
                $shipping_cost_name = DB::table('dataset_shipping_cost')
                    ->where('id', 2)
                    ->first();
            }
        }
        $insert_db_orders->shipping_cost_name = $shipping_cost_name->shipping_name;

        $insert_db_orders->sum_price = $price;

        $data_user =  DB::table('customers')
            ->select(
                'customers.pv_upgrad',
                'ewallet',
                'customers.user_name',
                'customers.status_customer',
                'customers.introduce_id',
                'dataset_qualification.business_qualifications as qualification_name',
                'dataset_qualification.bonus'
            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', $user_name)
            ->first();



        $insert_db_orders->position = $data_user->qualification_name;
        $insert_db_orders->bonus_percent = $data_user->bonus;




        if ($data_user->pv_upgrad >= 1200) {
            $discount = floor($pv_total * 50 / 100);
            $p_bonus = 50;
            $status_es = 0;
        } else {
            $discount = floor($pv_total * 30 / 100);
            $p_bonus = 30;
            $status_es = 1;
        }


        if ($status_es == 1) {
            $introduce_id =  DB::table('customers')
                ->select(
                    'customers.id',
                    'customers.user_name',
                    'customers.pv_upgrad',
                    'customers.status_customer',
                    'customers.ewallet',
                    'customers.ewallet_use',
                    'customers.expire_date',
                    'customers.expire_date_bonus',
                    'dataset_qualification.business_qualifications as qualification_name',
                    'dataset_qualification.bonus'
                )
                ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                ->where('user_name', '=', $data_user->introduce_id)
                ->where(function ($query) {
                    $query->where('customers.expire_date', '>', now());
                    // ->orWhere('customers.expire_date_bonus', '>', now());
                })
                ->first();

            if (
                $introduce_id &&
                in_array($introduce_id->qualification_name, ['VVIP', 'XVVIP', 'SVVIP', 'MG', 'MR', 'ME', 'MD', 'MC']) &&
                $introduce_id->status_customer != 'cancel'
            )
                if (empty($introduce_id->ewallet)) {
                    $ewallet = 0;
                } else {
                    $ewallet = $introduce_id->ewallet;
                }

            if (empty($introduce_id->ewallet_use)) {
                $ewallet_use = 0;
            } else {
                $ewallet_use = $introduce_id->ewallet_use;
            }

            $el_full = $pv_total * 20 / 100;
            $tax_total = $el_full * (3 / 100);


            $amt =  $el_full - $tax_total;


            $ew_total = $amt + $introduce_id->ewallet;
            $ew_use = $ewallet_use + $el_full;

            DB::table('customers')
                ->where('user_name', $introduce_id->user_name)
                ->update(['ewallet' => $ew_total, 'ewallet_use' => $ew_use]);

            $count_eWallet =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_wallet();

            $dataPrepare = [
                'transaction_code' => $count_eWallet,
                'customers_id_fk' => $introduce_id->id,
                'customer_username' => $introduce_id->user_name,
                'tax_total' => $tax_total,
                'bonus_full' => $el_full,
                'amt' => $amt,
                'old_balance' => $introduce_id->ewallet,
                'balance' => $ew_total,
                'note_orther' => "โบนัสส่วนต่าง Easy Cashback จากรหัส " . $data_user->user_name . " รายการ:" . $code_order,
                'receive_date' => now(),
                'receive_time' => now(),
                'type' => 15,
                'status' => 2,
            ];
            $query =  eWallet::create($dataPrepare);
            $report_bonus_2024_easy = [
                'user_name' =>  $introduce_id->user_name,
                'qualification' => $introduce_id->qualification_name,
                'expire_date' => $introduce_id->expire_date,
                'code_order' => $code_order,
                'buy_user_name' => $data_user->user_name,
                'buy_qualification' => $data_user->qualification_name,
                'pv' => $pv_total,
                'percen' => 20,
                'tax_percen'   => $tax_total,
                'tax_total' => $tax_total,
                'bonus_full' => $el_full,
                'bonus' => $amt,
                'date_action' => now(),
                'status' => 'success',

            ];

            $query =  DB::table('report_bonus_2024_easy')
                ->insert($report_bonus_2024_easy);
        }



        $insert_db_orders->bonus_percent = $p_bonus;

        $insert_db_orders->discount = $discount;
        $total_price = $price + $shipping_total - $discount;

        if ($data_user->ewallet <  $total_price) {

            return response()->json([
                'message' => 'ไม่สามารถชำระเงินได้เนื่องจาก Ewallet ไม่พอสำหรับการจ่าย',
                'status' => 'error',
                'code' => 'ER02',
                'data' => null,
            ], 500);
        }
        $insert_db_orders->shipping_price = $shipping_total;
        $insert_db_orders->total_price = $total_price;
        $insert_db_orders->pv_total = $pv_total;
        $insert_db_orders->tax = $vat;
        $insert_db_orders->tax_total = $p_vat;
        $insert_db_orders->order_status_id_fk = 2;
        $insert_db_orders->quantity = $quantity;
        $insert_db_orders->code_order = $code_order;
        if ($data_user->qualification_name == 'MC') {
            $insert_db_orders->type_order = 'pv';
        } else {
            $insert_db_orders->type_order = 'hold';
        }


        try {
            DB::BeginTransaction();

            $insert_db_orders->save();
            $insert_order_products_list::insert($insert_db_products_list);
            $run_payment = ApiFunction7Controller::run_payment($code_order);

            if ($run_payment['status'] == 'success') {
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'data' => null,
                    'message' => 'สั่งซื้อสำเร็จ',
                ], 200);
            } else {

                return response()->json([
                    'message' => $run_payment['message'],
                    'status' => 'error',
                    'code' => 'ER02',
                    'data' => null,
                ], 500);
            }
        } catch (\Exception $e) {

            DB::rollback();

            return response()->json([
                'message' => $e->getMessage(),
                'status' => 'error',
                'code' => 'ER02',
                'data' => null,
            ], 500);
        }
    }




    public function run_payment($code_order)
    {
        $order = DB::table('db_orders')
            ->where('code_order', '=', $code_order)
            ->where('order_status_id_fk', '=', 2)
            ->first();
        try {
            DB::BeginTransaction();


            if ($order) {

                $order_update = Orders::find($order->id);

                if ($order->status_payment_sent_other == 1) {
                    $customer_id = $order->customers_sent_id_fk;
                } else {
                    $customer_id = $order->customers_id_fk;
                }


                $customer_update = Customers::lockForUpdate()->find($customer_id);

                if ($customer_update->ewallet_use == '' || empty($customer_update->ewallet_use)) {
                    $ewallet_use = 0;
                } else {

                    $ewallet_use = $customer_update->ewallet_use;
                }


                if ($customer_update->bonus_total == '' || empty($customer_update->bonus_total)) {
                    $bonus_total = 0;
                } else {

                    $bonus_total = $customer_update->bonus_total;
                }

                if ($customer_update->pv_all == '' || empty($customer_update->pv_all)) {
                    $pv_all = 0;
                } else {

                    $pv_all = $customer_update->pv_all;
                }

                $customer_update->ewallet_use = $ewallet_use + $order->discount;
                $customer_update->bonus_total = $bonus_total + $order->discount;
                $pv_old = $customer_update->pv;
                $order_update->pv_old = $customer_update->pv;
                $ewallet_old = $customer_update->ewallet;
                $order_update->ewallet_old = $ewallet_old;
                $order_update->ewallet_price = $order->total_price;

                $customer_update->pv_all = $pv_all + $order->pv_total;


                if ($order_update->type_order == 'hold') {
                    $pv_balance = $customer_update->pv + $order->pv_total;
                    $customer_update->pv = $pv_balance;
                } elseif ($order_update->type_order == 'pv') {
                } else {
                    DB::rollback();
                    $resule = ['status' => 'fail', 'message' => 'สั่งซื้อสินค้าไม่สำเร็จกรุณาเลือกประเภทรายการของบิล'];
                    return $resule;
                }




                $ewallet = $ewallet_old - $order->total_price;

                if ($ewallet < 0) {
                    DB::rollback();
                    $resule = ['status' => 'fail', 'message' => 'สั่งซื้อสินค้าไม่สำเร็จ ewallet ของคุณมีไม่เพียงพอ'];
                    return $resule;
                } else {
                    $customer_update->ewallet =  $ewallet;
                }


                $pv_banlance = $customer_update->pv + $order->pv_total;
                $order_update->pv_banlance = $pv_banlance;




                $order_update->ewallet_banlance = $ewallet;
                $order_update->order_status_id_fk = 5;

                $jang_pv = new Jang_pv();

                $code = \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_pv();


                $jang_pv->code = $code;
                $jang_pv->code_order =  $order->code_order;
                $jang_pv->customer_username = $order->customers_user_name;
                // $jang_pv->to_customer_username = $data_user->user_name;
                $jang_pv->position = $order->position;
                $jang_pv->bonus_percen = $order->bonus_percent;
                $jang_pv->pv_old = $pv_old;
                $jang_pv->pv = $order->pv_total;
                $jang_pv->pv_balance =  $pv_old;
                // $jang_pv->date_active =  date('Y-m-d',$mt_mount_new);
                // $pv_to_price =  $data_user->pv_active;//ได้รับ 100%
                $jang_pv->wallet =   $order->total_price;
                $jang_pv->old_wallet = $ewallet_old;
                $jang_pv->wallet_balance = $ewallet;
                $jang_pv->type =  '5';
                $jang_pv->status =  'Success';


                $resule = ['status' => 'success', 'message' => 'สั่งซื้อสินค้าสำเร็จ'];

                $jang_pv->save();
                $order_update->save();
                $customer_update->save();

                $dataPrepare = [
                    'transaction_code' => $order->code_order,
                    'customers_id_fk' => $order->customers_id_fk,
                    'customer_username' => $order->customers_user_name,
                    'amt' => $order->total_price,
                    'old_balance' => $customer_update->ewallet,
                    'balance' => $ewallet,
                    'type' => 4,
                    'receive_date' => now(),
                    'receive_time' => now(),
                    'status' => 2,
                ];

                $query =  eWallet::create($dataPrepare);

                if ($order_update->type_order == 'pv') {
                    $input_user_name_upgrad = $order->customers_user_name;
                    $pv_upgrad_input =  $order->pv_total;

                    $jang_pv_upgrad = ApiFunction7Controller::jang_pv_upgrad($input_user_name_upgrad, $pv_upgrad_input, $order->code_order, $order);

                    if ($jang_pv_upgrad['status'] == 'fail') {
                        DB::rollback();
                        return $jang_pv_upgrad;
                    }
                }

                DB::commit();
                return $resule;
            } else {
                DB::rollback();
                $resule = ['status' => 'fail', 'message' => 'สั่งซื้อสินค้าไม่สำเร็จ กรุณาเช็ครายการสินค้าในหน้าประวิตสินค้า'];
                return $resule;
            }
        } catch (\Exception $e) {

            DB::rollback();

            $resule = ['status' => 'fail', 'message' => 'Order Update Fail' . $e->getMessage()];
            return $resule;
        }
    }


    public  function jang_pv_upgrad($input_user_name_upgrad, $pv_upgrad_input, $code_order, $order)
    {

        $user_action = DB::table('customers')
            ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'pv', 'bonus_total', 'pv_upgrad', 'name', 'last_name')
            ->where('user_name', $input_user_name_upgrad)
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
            ->where('user_name', '=', $input_user_name_upgrad)
            ->first();

        $expire_date = $data_user->expire_date;



        $old_position = $data_user->qualification_id;



        if (empty($data_user)) {
            $resule = ['status' => 'fail', 'message' => 'แจง PV ไม่สำเร็จกรุณาทำรายการไหม่อีกครั้ง'];
            return $resule;
        }


        // $customer_update_use = Customers::find($user_action->id);
        // $customer_update = Customers::find($data_user->id);
        if ($data_user->qualification_id == '' || $data_user->qualification_id == null || $data_user->qualification_id == '-') {
            $qualification_id = 'MC';
        } else {
            $qualification_id = $data_user->qualification_id;
        }


        // dd($expire_date);

        $customer_username = $data_user->introduce_id;
        $arr_user = array();
        $report_bonus_register = array();

        $code_bonus = \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_bonus(2);

        for ($i = 1; $i <= 8; $i++) {
            $x = 'start';
            $run_data_user =  DB::table('customers')
                ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
                // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                ->where('user_name', '=', $customer_username)
                ->first();
            // dd($customer_username);
            if (empty($run_data_user)) {
                $i = 8;
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

                        $report_bonus_register[$i]['regis_user_name'] = $input_user_name_upgrad;
                        $report_bonus_register[$i]['regis_user_introduce_id'] = $data_user->introduce_id;
                        $report_bonus_register[$i]['regis_name'] = $data_user->name . ' ' . $data_user->last_name;
                        $report_bonus_register[$i]['user_name_g'] = $run_data_user->user_name;
                        $report_bonus_register[$i]['old_position'] = $data_user->qualification_id;
                        // $report_bonus_register[$i]['new_position'] = $position_update;
                        $report_bonus_register[$i]['name_g'] = $run_data_user->name . ' ' . $run_data_user->last_name;
                        $report_bonus_register[$i]['qualification'] = $qualification_id;
                        $report_bonus_register[$i]['g'] = $i;
                        $report_bonus_register[$i]['pv'] = $pv_upgrad_input;
                        $report_bonus_register[$i]['code_bonus'] = $code_bonus;
                        $report_bonus_register[$i]['code_order'] = $code_order;
                        $report_bonus_register[$i]['type'] = 'jangpv';

                        $arr_user[$i]['user_name'] = $run_data_user->user_name;
                        $arr_user[$i]['lv'] = [$i];
                        if ($i == 1) {
                            $report_bonus_register[$i]['percen'] = 180;

                            $arr_user[$i]['pv'] = $pv_upgrad_input;
                            $arr_user[$i]['position'] = $qualification_id;
                            $wallet_total = $pv_upgrad_input * 180 / 100;
                            $arr_user[$i]['bonus'] = $wallet_total;
                            $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                            $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                            $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                        } elseif ($i == 2) {
                            $report_bonus_register[$i]['percen'] = 10;
                            $arr_user[$i]['pv'] = $pv_upgrad_input;
                            $arr_user[$i]['position'] = $qualification_id;
                            if ($qualification_id == 'MC' || $qualification_id == 'MB') {
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } else {

                                $wallet_total = $pv_upgrad_input * 10 / 100;
                                $arr_user[$i]['bonus'] = $wallet_total;
                                $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                            }
                        } elseif ($i == 3) {
                            $report_bonus_register[$i]['percen'] = 5;
                            $arr_user[$i]['pv'] = $pv_upgrad_input;
                            $arr_user[$i]['position'] = $qualification_id;
                            if ($qualification_id == 'MC' || $qualification_id == 'MB' || $qualification_id == 'MO') {
                                $report_bonus_register[$i]['tax_total'] = 0;
                                $report_bonus_register[$i]['bonus_full'] = 0;
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } else {

                                $wallet_total = $pv_upgrad_input * 5 / 100;
                                $arr_user[$i]['bonus'] = $wallet_total;
                                $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                            }
                        } elseif ($i == 4) {
                            $report_bonus_register[$i]['percen'] = 5;
                            $arr_user[$i]['pv'] = $pv_upgrad_input;
                            $arr_user[$i]['position'] = $qualification_id;

                            if ($qualification_id == 'MC' || $qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP') {
                                $report_bonus_register[$i]['tax_total'] = 0;
                                $report_bonus_register[$i]['bonus_full'] = 0;
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } else {

                                $wallet_total = $pv_upgrad_input * 5 / 100;
                                $arr_user[$i]['bonus'] = $wallet_total;
                                $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                            }
                        } elseif ($i >= 5 and $i <= 8) {
                            $report_bonus_register[$i]['percen'] = 5;
                            $arr_user[$i]['pv'] = $pv_upgrad_input;
                            $arr_user[$i]['position'] = $qualification_id;

                            if ($qualification_id == 'MC' || $qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP') {
                                $report_bonus_register[$i]['tax_total'] = 0;
                                $report_bonus_register[$i]['bonus_full'] = 0;
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } else {
                                $wallet_total = $pv_upgrad_input * 5 / 100;
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
                ->where('regis_user_name', '=', $input_user_name_upgrad)
                ->get();

            foreach ($db_bonus_register as $value) {


                if ($value->bonus > 0) {


                    // $wallet_g = DB::table('customers')
                    //     ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'bonus_total')
                    //     ->where('user_name', $value->user_name_g)
                    //     ->first();

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


            if ($order->type_order == 'hold') {
                $jang_pv = [
                    'code' => $code,
                    'code_order' => $code_order,
                    'customer_username' => $user_action->user_name,
                    'to_customer_username' => $input_user_name_upgrad,
                    'old_position' => $data_user->qualification_id,

                    'pv_old' => $user_action->pv,
                    'pv' =>  $pv_upgrad_input,
                    'pv_balance' => $user_action->pv + $pv_upgrad_input,
                    'type' => '3',
                    'status' => 'Success'
                ];
            } else {
                $jang_pv = [
                    'code' => $code,
                    'code_order' => $code_order,
                    'customer_username' => $user_action->user_name,
                    'to_customer_username' => $input_user_name_upgrad,
                    'old_position' => $data_user->qualification_id,

                    'pv_old' => $user_action->pv,
                    'pv' =>  $pv_upgrad_input,
                    'pv_balance' => $user_action->pv,
                    'type' => '3',
                    'status' => 'Success'
                ];
            }


            DB::commit();
            $resule = ['status' => 'success', 'message' => 'เแจงอัพเกรดรหัสสำเร็จ'];
            return $resule;
        } catch (Exception $e) {
            DB::rollback();
            $resule = ['fail' => 'success', 'message' => 'เแจง PV ไม่สำเร็จกรุณาทำรายการไหม่อีกครั้ง'];
            return $resule;
        }
    }
}
