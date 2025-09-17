<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Cart;
use Auth;
use PhpParser\Node\Stmt\Return_;
use App\Orders;
use App\Customers;
use App\Order_products_list;
use App\Jang_pv;
use App\eWallet;
use App\Log_insurance;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Arr;
use Phattarachai\LineNotify\Facade\Line;

class ConfirmCartController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }
    public function index()
    {



        $business_location_id = 1;
        // $location = Location::location($business_location_id, $business_location_id);
        $location = '';
        $cartCollection = Cart::session(1)->getContent();
        $data = $cartCollection->toArray();
        $quantity = Cart::session(1)->getTotalQuantity();
        $customer_id = Auth::guard('c_user')->user()->id;
        $user_name = Auth::guard('c_user')->user()->user_name;

        if ($quantity  == 0) {
            return redirect('Order')->withWarning('ไม่มีสินค้าในตะกร้าสินค้า กรุณาเลือกสินค้า');
        }
        $statsu_open_100 = 'open';

        if ($data) {

            foreach ($data as $value) {
                $pv[] = $value['quantity'] * $value['attributes']['pv'];
                $product_shipping = DB::table('products_cost')
                    ->where('product_id_fk', $value['id'])
                    ->where('status_shipping', 'Y')
                    ->first();


                $product = DB::table('products')
                    ->select(
                        'wallet',
                    )
                    ->where('id', $value['id'])
                    ->where('wallet', '>', 0)
                    ->first();

                if ($product) {
                    $wallet_arr[] = $product->wallet * $value['quantity'];
                } else {
                    $wallet_arr[] = 0;
                }


                if ($value['id'] == 72 || $value['id'] == 71  || ($value['id'] >= 75 and $value['id'] <= 96)) {
                    $statsu_open_100 = 'closs';
                }

                if ($product_shipping) {
                    // $pv_shipping_arr[] = $value['quantity'] * $product_shipping->pv;
                    $pv_shipping_arr[] = $value['quantity'] * 20;
                } else {
                    $pv_shipping_arr[] = 0;
                }
            }
            $pv_shipping = array_sum($pv_shipping_arr);
            $pv_total = array_sum($pv);
        } else {
            $pv_shipping = 0;
            $pv_total = 0;
        }



        //ราคาสินค้า
        $price = Cart::session(1)->getTotal();

        // $province_data = DB::table('customers_address_delivery')
        //     ->select('province_id_fk')
        //     ->where('customer_id', '=', $customer_id)
        //     ->first();
        // if($province_data){
        //   $data_shipping = ShippingCosController::fc_check_shipping_cos($business_location_id, $province_data->province_id_fk, $price);
        //   if($type == '3'){
        // $shipping = 0;
        //   }else{
        //     $shipping = $data_shipping['data']->shipping_cost;
        //   }

        // }else{
        //   $shipping = 0;
        // }

        $address = DB::table('customers_address_delivery')
            ->select('customers_address_delivery.*', 'address_provinces.province_id', 'address_provinces.province_name', 'address_tambons.tambon_name', 'address_tambons.tambon_id', 'address_districts.district_id', 'address_districts.district_name')
            ->leftjoin('address_provinces', 'address_provinces.province_id', '=', 'customers_address_delivery.province')
            ->leftjoin('address_districts', 'address_districts.district_id', '=', 'customers_address_delivery.district')
            ->leftjoin('address_tambons', 'address_tambons.tambon_id', '=', 'customers_address_delivery.tambon')
            ->where('user_name', '=', $user_name)
            ->first();

        $shipping = \App\Http\Controllers\Frontend\ShippingController::fc_shipping($pv_shipping);

        if ($address) {
            $shipping_zipcode = \App\Http\Controllers\Frontend\ShippingController::fc_shipping_zip_code($address->zipcode);
        } else {
            $shipping_zipcode = ['status' => 'fail', 'price' => 0, 'ms' => ''];
        }


        $vat = DB::table('dataset_vat')
            ->where('business_location_id_fk', '=', $business_location_id)
            ->first();

        $vat = $vat->vat;


        //vatใน 7%
        $p_vat = $price * ($vat / (100 + $vat));

        //มูลค่าสินค้า
        $price_vat = $price - $p_vat;


        $data_user =  DB::table('customers')
            ->select('customers.*', 'dataset_qualification.business_qualifications as qualification_name', 'dataset_qualification.bonus')
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', Auth::guard('c_user')->user()->user_name)
            ->first();

        if ($data_user->pv_upgrad >= 1200) {
            $discount = floor($pv_total * 50 / 100);
            $p_bonus = 50;
        } else {

            $discount = floor($pv_total * 30 / 100);
            $p_bonus = 30;
        }


        $price_total = $price + ($shipping + $shipping_zipcode['price']) - $discount;

        $bill = array(
            'vat' => $vat,
            'shipping' => $shipping + $shipping_zipcode['price'],
            'price' => $price,
            'p_vat' => $p_vat,
            'price_vat' => $price_vat,
            'price_total' => $price_total,
            'pv_total' => $pv_total,
            'data' => $data,
            'price_shipping_pv' => $shipping,
            'bonus' =>  $p_bonus,
            'price_discount' => $price - $discount,
            'discount' => $discount,
            'position' => $data_user->qualification_name,
            'quantity' => $quantity,
            'location_id' => $business_location_id,
            'status' => 'success',
            'wallet_arr' => array_sum($wallet_arr)
        );

        $customer = DB::table('customers')
            ->where('id', '=', Auth::guard('c_user')->user()->id)
            ->first();


        $province = DB::table('address_provinces')
            ->select('*')
            ->get();
        return view('frontend/confirm_cart', compact('customer', 'address', 'location', 'province', 'bill', 'shipping_zipcode', 'statsu_open_100'));
    }

    public static function check_custome_unline(Request $rs)
    {


        if (empty(@$rs->user_name)) {
            $data = array('status' => 'fail', 'ms' => 'กรุณากรอกข้อมูลรหัสที่สั่งซื้อให้ลูกทีม');
            return $data;
        } else {
            $sent_user_name = $rs->user_name;
        }

        $user_name = Auth::guard('c_user')->user()->user_name;

        if (strtoupper($user_name) == strtoupper($rs->user_name)) {
            $data = array('status' => 'fail', 'ms' => 'สั่งซื้อให้เฉพาะลูกทีมเท่านั้น');
            return $data;
        }

        $data_user =  DB::table('customers')
            ->select(
                'customers.id',
                'customers.upline_id',
                'customers.user_name',
                'customers.name',
                'customers.last_name',
                'customers.pv',
                'dataset_qualification.business_qualifications as qualification_name',
                'business_name'
            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', $sent_user_name)
            ->first();




        if ($data_user) {
            $data = array('status' => 'success', 'data' => $data_user);
        } else {
            $data = array('status' => 'fail', 'data' => '', 'ms' => 'ไม่มีข้อมูลรหัส ' . $sent_user_name);
        }

        return $data;
    }

    public function payment_submit(Request $rs)
    {



        $insert_db_orders = new Orders();
        $insert_order_products_list = new Order_products_list();
        $quantity = Cart::session(1)->getTotalQuantity();
        $insert_db_orders->quantity = $quantity;
        $customer_id = Auth::guard('c_user')->user()->id;



        $insert_db_orders->customers_id_fk = $customer_id;
        $insert_db_orders->tracking_type = $rs->tracking_type;
        $user_name = Auth::guard('c_user')->user()->user_name;
        $insert_db_orders->customers_user_name = $user_name;
        //$business_location_id = Auth::guard('c_user')->user()->business_location_id;
        $business_location_id = 1;
        $insert_db_orders->business_location_id_fk =  $business_location_id;



        if ($rs->sent_type_to_customer == 'sent_type_other') {


            $check_customers_sent_user_name = DB::table('customers')
                ->select(
                    'customers.id',
                    'customers.user_name',
                    'customers.name',
                )
                ->where('user_name', '!=', Auth::guard('c_user')->user()->use)
                ->where('user_name', '=', $rs->customers_sent_user_name)
                ->first();

            if (!$check_customers_sent_user_name) {

                return redirect('confirm_cart')->withError('ไม่มีข้อมูลรหัส ' . $rs->customers_sent_user_name);
            }

            dd('sdsd');
            $insert_db_orders->customers_sent_id_fk = $rs->customers_sent_id_fk;
            $insert_db_orders->customers_sent_user_name = $rs->customers_sent_user_name;
            $insert_db_orders->status_payment_sent_other = 1;
        } else {
            $insert_db_orders->status_payment_sent_other = 0;
            //ถ้าซื้อให้ตัวเองต้องซื้อได้แต่ 1 ครั้งเท่านั้น
            $cartCollection = Cart::session(1)->getContent();
            $data = $cartCollection->toArray();
            $check_pro_2 = false;
            foreach ($data as $value) {
                $products_detail_pro2 = DB::table('products_details')
                    ->select(
                        'id',
                    )
                    ->where('product_id_fk', $value['id'])
                    ->where('products_details.product_name', 'like', '$%')
                    ->first();

                if ($products_detail_pro2) {
                    if (count($data) > 1) {


                        return redirect('confirm_cart')->withError('ไม่สามารถซื้อโปรโมชั่นนี้รวมกับสินค้าอื่นได้');
                    } else {
                        if ($value['quantity'] > 1) {
                            return redirect('confirm_cart')->withError('ไม่สามารถซื้อโปรโมชั่นนี้เกิน 1 ชิ้นได้');
                        }


                        $db_order_products_list = DB::table('db_order_products_list')
                            ->where('product_name', 'like', '$%') // ขึ้นต้นด้วย #
                            ->where('customers_username', Auth::guard('c_user')->user()->user_name)
                            ->count();

                        if ($db_order_products_list > 0) {
                            return redirect('confirm_cart')->withError('สามารถซื้อโปรโมชั่นนี้ได้ 1 ครั้งเท่านั้น');
                        }
                    }

                    $db_position = DB::table('dataset_qualification')
                        ->select('code', 'id')
                        ->where('code', Auth::guard('c_user')->user()->qualification_id)
                        ->first();
                    if ($db_position->id >= 4) {
                        return redirect('confirm_cart')->withError('ตำแหน่งของคุณไม่สามารถซื้อโปรโมชั่นนี้ได้');
                    }

                    $check_pro_2 = true;
                    $check_sponsor = ConfirmCartController::check_sponsor(Auth::guard('c_user')->user()->introduce_id);
                    if ($check_sponsor['status'] == 'fail') {
                        return redirect('confirm_cart')->withError('ไม่พบผู้แนะนำ กรุณาติดต่อเจ้าหน้าที่');
                    }
                }
            }
        }
        $code_order = \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_order();
        if ($rs->receive == 'sent_address') {
            $insert_db_orders->address_sent = 'system';

            if (empty($rs->province_id) || empty($rs->zipcode)) {
                return redirect('confirm_cart')->withError('กรุณากรอกที่อยู่ก่อนทำการซื้อสินค้า');
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
        } else {
            if (empty($rs->same_province) || empty($rs->same_zipcode)) {
                return redirect('confirm_cart')->withError('กรุณากรอกที่อยู่ก่อนทำการซื้อสินค้า');
            }

            $insert_db_orders->address_sent = 'other';
            $insert_db_orders->delivery_province_id = $rs->same_province;
            $insert_db_orders->house_no = $rs->same_address;
            // $insert_db_orders->house_name = 'system';
            $insert_db_orders->moo = $rs->same_moo;
            $insert_db_orders->soi = $rs->same_soi;
            $insert_db_orders->road = $rs->same_road;
            $insert_db_orders->tambon_id = $rs->same_tambon;
            $insert_db_orders->district_id = $rs->same_district;
            $insert_db_orders->province_id = $rs->same_province;
            $insert_db_orders->zipcode = $rs->same_zipcode;
            $insert_db_orders->tel = $rs->same_phone;
            $insert_db_orders->name = $rs->sam_name;
        }
        $insert_db_orders->pay_type = $rs->type_pay;

        // dd($insert_db_orders->toArray());

        // $location = Location::location($business_location_id, $business_location_id);
        // $location = '';
        $cartCollection = Cart::session(1)->getContent();
        $data = $cartCollection->toArray();
        $quantity = Cart::session(1)->getTotalQuantity();

        if ($quantity  == 0) {
            return redirect('Order')->withWarning('สั่งซื้อไม่เสร็จ กรุณาทำรายการไหม่');
        }
        $i = 0;
        $products_list = array();
        if ($data) {
            foreach ($data as $value) {
                $i++;
                $total_pv = $value['attributes']['pv'] * $value['quantity'];
                $total_price = $value['price'] * $value['quantity'];

                $insert_db_products_list[] = [
                    'code_order' => $code_order,
                    'product_id_fk' => $value['id'],
                    'product_unit_id_fk' => @$value['product_unit_id'],
                    'customers_username' =>  $user_name,
                    'selling_price' =>  $value['price'],
                    'product_name' =>  $value['name'],
                    'amt' =>  $value['quantity'],
                    'pv' =>   $value['attributes']['pv'],
                    'total_pv' => $total_pv,
                    'total_price' => $total_price,
                ];
                $product_id[] = $value['id'];

                $pv[] = $value['quantity'] * $value['attributes']['pv'];


                $product_shipping = DB::table('products_cost')
                    ->where('product_id_fk', $value['id'])
                    ->where('status_shipping', 'Y')
                    ->first();


                $product = DB::table('products')
                    ->select(
                        'wallet',
                    )
                    ->where('id', $value['id'])
                    ->where('wallet', '>', 0)
                    ->first();

                if ($product) {
                    $wallet_arr[] = $product->wallet * $value['quantity'];
                } else {
                    $wallet_arr[] = 0;
                }

                if ($product_shipping) {
                    //$pv_shipping_arr[] = $value['quantity'] * $product_shipping->pv;
                    $pv_shipping_arr[] = $value['quantity'] * 20;
                } else {
                    $pv_shipping_arr[] = 0;
                }
            }
            $pv_shipping = array_sum($pv_shipping_arr);
            $pv_total = array_sum($pv);
        } else {
            $pv_shipping = 0;
            $pv_total = 0;
        }



        //ราคาสินค้า
        $price = Cart::session(1)->getTotal();

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
        $insert_db_orders->sum_price = $price;

        $data_user =  DB::table('customers')
            ->select(
                'customers.pv_upgrad',
                'customers.user_name',
                'customers.status_customer',
                'customers.qualification_id',
                'customers.introduce_id',
                'dataset_qualification.business_qualifications as qualification_name',
                'dataset_qualification.bonus'
            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', Auth::guard('c_user')->user()->user_name)
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

        $insert_db_orders->bonus_percent = $p_bonus;

        $insert_db_orders->discount = $discount;
        $total_price = $price + $shipping_total - $discount;

        if (Auth::guard('c_user')->user()->ewallet <  $total_price) {
            return redirect('cart')->withWarning('ไม่สามารถชำระเงินได้เนื่องจาก Ewallet ไม่พอสำหรับการจ่าย');
        }
        $insert_db_orders->shipping_price = $shipping_total;
        $insert_db_orders->total_price = $total_price;
        $insert_db_orders->pv_total = $pv_total;
        $insert_db_orders->tax = $vat;
        $insert_db_orders->tax_total = $p_vat;
        $insert_db_orders->order_status_id_fk = 2;
        $insert_db_orders->quantity = $quantity;
        $insert_db_orders->code_order = $code_order;
        $insert_db_orders->type_order = $rs->type_order;

        try {
            DB::BeginTransaction();

            $insert_db_orders->save();
            $insert_order_products_list::insert($insert_db_products_list);
            $run_payment = ConfirmCartController::run_payment($code_order, $check_pro_2);

            Cart::session(1)->clear();

            if ($run_payment['status'] == 'success') {

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
                            'dataset_qualification.code as qualification_code',
                            'dataset_qualification.bonus'
                        )
                        ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        ->where('user_name', '=', $data_user->introduce_id)
                        // ->where(function ($query) {
                        //     $query->where('customers.expire_date', '>', now());
                        //     // ->orWhere('customers.expire_date_bonus', '>', now());
                        // })
                        ->first();

                    if ($data_user->qualification_id == 'MC') {
                        $arr_position = [
                            'MB',
                            'MO',
                            'VIP',
                            'VVIP',
                            'XVVIP',
                            'SVVIP',
                            'MG',
                            'MR',
                            'ME',
                            'MD',
                            'MC'
                        ];
                    } else {
                        $arr_position =   [
                            'VVIP',
                            'XVVIP',
                            'SVVIP',
                            'MG',
                            'MR',
                            'ME',
                            'MD'
                        ];
                    }

                    if (
                        $introduce_id &&
                        in_array($introduce_id->qualification_code, $arr_position) &&
                        $introduce_id->status_customer != 'cancel'
                    ) {
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

                        if ($ew_total < 0) {

                            $message = "\n" . "รหัส : " . $introduce_id->user_name . "\n";
                            $message .= "ยอดติดลบจาก Web: " . $ew_total . "\n";
                            $message .= "โบนัสส่วนต่าง Easy Cashback ";
                            // Line::send($message);
                        }

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
                            'code_order' => $code_order,
                            'qualification' => $introduce_id->qualification_name,
                            'expire_date' => $introduce_id->expire_date,
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
                }

                if ($check_pro_2) {
                    $runbonus_faststart = ConfirmCartController::runbonus_faststart(Auth::guard('c_user')->user()->user_name, $pv_total);

                    if ($runbonus_faststart['status'] != 'success') {
                        DB::rollBack();
                        return redirect('cart')->withWarning('ไม่สามารถรจ่ายโบนัสได้ กรุณาติดต่อเจ้าหน้าที่');
                    }
                }

                if (array_sum($wallet_arr) > 0) {

                    if ($check_pro_2) {




                        if ($check_sponsor['status'] == 'success') {
                            $el  = array_sum($wallet_arr);
                            $customers = DB::table('customers')
                                ->select('id', 'user_name', 'ewallet', 'ewallet_use')
                                ->where('user_name', $check_sponsor['user_name'])
                                ->first();


                            if (empty($customers->ewallet)) {
                                $ewallet = 0;
                            } else {
                                $ewallet = $customers->ewallet;
                            }

                            if (empty($customers->ewallet_use)) {
                                $ewallet_use = 0;
                            } else {
                                $ewallet_use = $customers->ewallet_use;
                            }

                            $ew_total = $ewallet  +  $el;
                            $ew_use = $ewallet_use +  $el;
                            DB::table('customers')
                                ->where('user_name', $check_sponsor['user_name'])
                                ->update(['ewallet' => $ew_total, 'ewallet_use' => $ew_use]);


                            $count_eWallet =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_wallet();

                            $dataPrepare = [
                                'transaction_code' => $count_eWallet,
                                'customers_id_fk' => $customers->id,
                                'customer_username' => $check_sponsor['user_name'],
                                'tax_total' => 0,
                                'bonus_full' =>  $el,
                                'amt' =>  $el,
                                'old_balance' => $customers->ewallet,
                                'balance' => $ew_total,
                                'note_orther' => 'ได้รับยอดเงินคืนจากการซื้อสินค้า รายการ:' . $code_order . ' จากรหัส:' . Auth::guard('c_user')->user()->user_name,
                                'receive_date' => now(),
                                'receive_time' => now(),
                                'type' => 1,
                                'status' => 2,
                            ];

                            $query =  eWallet::create($dataPrepare);



                            //และขึ้นตำแหน่งทันที


                            //รันโบนัส ฟาสสตาท
                        }
                    } else {
                        $el  = array_sum($wallet_arr);

                        $customers = DB::table('customers')
                            ->select('id', 'user_name', 'ewallet', 'ewallet_use')
                            ->where('user_name', Auth::guard('c_user')->user()->user_name)
                            ->first();


                        if (empty($customers->ewallet)) {
                            $ewallet = 0;
                        } else {
                            $ewallet = $customers->ewallet;
                        }

                        if (empty($customers->ewallet_use)) {
                            $ewallet_use = 0;
                        } else {
                            $ewallet_use = $customers->ewallet_use;
                        }

                        $ew_total = $ewallet  +  $el;
                        $ew_use = $ewallet_use +  $el;
                        DB::table('customers')
                            ->where('user_name', Auth::guard('c_user')->user()->user_name)
                            ->update(['ewallet' => $ew_total, 'ewallet_use' => $ew_use]);


                        $count_eWallet =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_wallet();


                        $dataPrepare = [
                            'transaction_code' => $count_eWallet,
                            'customers_id_fk' => $customers->id,
                            'customer_username' => Auth::guard('c_user')->user()->user_name,
                            'tax_total' => 0,
                            'bonus_full' =>  $el,
                            'amt' =>  $el,
                            'old_balance' => $customers->ewallet,
                            'balance' => $ew_total,
                            'note_orther' => 'ได้รับยอดเงินคืนจากการซื้อสินค้า รายการ:' . $code_order,
                            'receive_date' => now(),
                            'receive_time' => now(),
                            'type' => 1,
                            'status' => 2,
                        ];

                        $query =  eWallet::create($dataPrepare);
                    }
                }

                DB::commit();
                return redirect('order_history')->withSuccess($run_payment['message']);
            } else {
                DB::rollback();
                return redirect('order_history')->withError($run_payment['message']);
            }
        } catch (\Exception $e) {

            DB::rollback();
            // dd($e);
            // info($e->getMessage());
            $resule = ['status' => 'fail', 'message' => 'Order Update Fail', 'id' => $insert_db_orders->id];
            return redirect('Order')->withError('Order Update Fail' . $e->getMessage());
        }
    }

    public function run_payment($code_order, $check_pro_2)
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


                $customer_update->bonus_total = $bonus_total + $order->discount;
                $pv_old = $customer_update->pv;
                $order_update->pv_old = $customer_update->pv;
                $ewallet_old = $customer_update->ewallet;
                $order_update->ewallet_old = $ewallet_old;
                $order_update->ewallet_price = $order->total_price;

                $customer_update->pv_all = $pv_all + $order->pv_total;


                if ($order_update->type_order == 'hold') {
                    if (!$check_pro_2) {

                        $pv_balance = $customer_update->pv + $order->pv_total;
                        $customer_update->pv = $pv_balance;
                    } else { //ทำการเเจ้งปรับตำแหน่งทันที

                    }
                } elseif ($order_update->type_order == 'pv') {
                } else {
                    DB::rollback();
                    $resule = ['status' => 'fail', 'message' => 'สั่งซื้อสินค้าไม่สำเร็จกรุณาเลือกประเภทรายการของบิล'];
                    return $resule;
                }




                $ewallet = $ewallet_old - $order->total_price;


                if ($ewallet < 0) {

                    $message = "\n" . "รหัส : " . $customer_update->user_name . "\n";
                    $message .= "ยอดติดลบจาก Web: " . $ewallet . "\n";
                    $message .= "สั่งซื้อสินค้า";
                    // Line::send($message);
                }


                if ($ewallet < 0) {
                    DB::rollback();
                    $resule = ['status' => 'fail', 'message' => 'สั่งซื้อสินค้าไม่สำเร็จ ewallet ของคุณมีไม่เพียงพอ'];
                    return $resule;
                } else {
                    $customer_update->ewallet =  $ewallet;

                    $ewallet_use =   round(floatval($customer_update->ewallet_use)) -  round(floatval($order->total_price));

                    if ($ewallet_use < 0) {
                        $customer_update->ewallet_use = 0;
                        $ewallet_tranfer =  round(floatval($customer_update->ewallet_tranfer)) + $ewallet_use;
                        if ($ewallet_tranfer < 0) {

                            $resule = ['status' => 'fail', 'message' => 'ยอดเงินฝากและโบนัสของคุณไม่เพียงต่อการโอนเงิน'];
                            return $resule;
                        } else {
                            $customer_update->ewallet_tranfer = $ewallet_tranfer;
                        }
                    } else {
                        $customer_update->ewallet_use = $ewallet_use;
                    }
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
                $jang_pv->to_customer_username = $order->customers_user_name;
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

                    $jang_pv_upgrad = $this->jang_pv_upgrad($input_user_name_upgrad, $pv_upgrad_input, $order->code_order, $order);

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


    public function jang_pv_upgrad($input_user_name_upgrad, $pv_upgrad_input, $code_order, $order)
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


        // $pv_upgrad_total = $data_user->pv_upgrad + $pv_upgrad_input;

        // if ($data_user->qualification_id == 'MC') {
        //     if ($pv_upgrad_total >= 20 and $pv_upgrad_total < 400) { //อัพ MO
        //         if ($pv_upgrad_input >=  20) {
        //             if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
        //                 $start_month = date('Y-m-d');
        //                 $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
        //                 $expire_date = date('Y-m-d', $mt_mount_new);
        //             } else {
        //                 $start_month = $data_user->expire_date;
        //                 $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
        //                 $expire_date = date('Y-m-d', $mt_mount_new);
        //             }
        //         }

        //         $position_update = 'MB';
        //     } elseif ($pv_upgrad_total >= 400 and $pv_upgrad_total < 800) { //อัพ MO
        //         if ($pv_upgrad_input >=  400) {
        //             if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
        //                 $start_month = date('Y-m-d');
        //                 $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
        //                 $expire_date = date('Y-m-d', $mt_mount_new);
        //             } else {
        //                 $start_month = $data_user->expire_date;
        //                 $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
        //                 $expire_date = date('Y-m-d', $mt_mount_new);
        //             }
        //         }

        //         $position_update = 'MO';
        //     } elseif ($pv_upgrad_total >= 800 and $pv_upgrad_total  < 1200) { //vip
        //         if ($pv_upgrad_input >=  400) {
        //             if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
        //                 $start_month = date('Y-m-d');
        //                 $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
        //                 $expire_date = date('Y-m-d', $mt_mount_new);
        //             } else {
        //                 $start_month = $data_user->expire_date;
        //                 $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
        //                 $expire_date = date('Y-m-d', $mt_mount_new);
        //             }
        //         }
        //         $position_update = 'VIP';
        //     } elseif ($pv_upgrad_total >= 1200) { //vvip
        //         if ($pv_upgrad_input >=  400) {
        //             if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
        //                 $start_month = date('Y-m-d');
        //                 $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
        //                 $expire_date = date('Y-m-d', $mt_mount_new);
        //             } else {
        //                 $start_month = $data_user->expire_date;
        //                 $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
        //                 $expire_date = date('Y-m-d', $mt_mount_new);
        //             }
        //         }
        //         $position_update = 'VVIP';
        //     } else { //อัพ pv_upgrad
        //         $position_update = $data_user->qualification_id;
        //     }
        // } elseif ($data_user->qualification_id == 'MO') {

        //     if ($pv_upgrad_total >= 800 and $pv_upgrad_total  < 1200) { //vip
        //         if ($pv_upgrad_input >=  400) {
        //             if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
        //                 $start_month = date('Y-m-d');
        //                 $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
        //                 $expire_date = date('Y-m-d', $mt_mount_new);
        //             } else {
        //                 $start_month = $data_user->expire_date;
        //                 $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
        //                 $expire_date = date('Y-m-d', $mt_mount_new);
        //             }
        //         }
        //         $position_update = 'VIP';
        //     } elseif ($pv_upgrad_total >= 1200) { //vvip
        //         if ($pv_upgrad_input >=  400) {
        //             if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
        //                 $start_month = date('Y-m-d');
        //                 $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
        //                 $expire_date = date('Y-m-d', $mt_mount_new);
        //             } else {
        //                 $start_month = $data_user->expire_date;
        //                 $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
        //                 $expire_date = date('Y-m-d', $mt_mount_new);
        //             }
        //         }
        //         $position_update = 'VVIP';
        //     } else { //อัพ pv_upgrad
        //         $position_update = $data_user->qualification_id;
        //     }
        // } elseif ($data_user->qualification_id == 'VIP') {
        //     if ($pv_upgrad_total >= 1200) { //vvip
        //         if ($pv_upgrad_input >=  400) {
        //             if (empty($data_user->expire_date) || strtotime($data_user->expire_date) < strtotime(date('Ymd'))) {
        //                 $start_month = date('Y-m-d');
        //                 $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
        //                 $expire_date = date('Y-m-d', $mt_mount_new);
        //             } else {
        //                 $start_month = $data_user->expire_date;
        //                 $mt_mount_new = strtotime("+33 Day", strtotime($start_month));
        //                 $expire_date = date('Y-m-d', $mt_mount_new);
        //             }
        //         }
        //         $position_update = 'VVIP';
        //         // เพิ่ม 33 วัน
        //     } else { //อัพ pv_upgrad 
        //         $position_update = $data_user->qualification_id;
        //     }
        // } else {
        //     $resule = ['status' => 'success', 'message' => 'ทำรายการไม่สำเร็จกรุณาทำรายการไหม่ ER:01'];

        //     $position_update =   $data_user->qualification_id;
        //     // return $resule;
        // }

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
                            $report_bonus_register[$i]['percen'] = 125;

                            $arr_user[$i]['pv'] = $pv_upgrad_input;
                            $arr_user[$i]['position'] = $qualification_id;
                            if ($qualification_id == 'MC') {
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } else {
                                $wallet_total = $pv_upgrad_input * 125 / 100;
                                $arr_user[$i]['bonus'] = $wallet_total;
                                $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                            }
                        } elseif ($i == 2) {
                            $report_bonus_register[$i]['percen'] = 17;
                            $arr_user[$i]['pv'] = $pv_upgrad_input;
                            $arr_user[$i]['position'] = $qualification_id;
                            if ($qualification_id == 'MC') {
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } else {

                                $wallet_total = $pv_upgrad_input * 17 / 100;
                                $arr_user[$i]['bonus'] = $wallet_total;
                                $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                            }
                        } elseif ($i == 3) {
                            $report_bonus_register[$i]['percen'] = 8;
                            $arr_user[$i]['pv'] = $pv_upgrad_input;
                            $arr_user[$i]['position'] = $qualification_id;
                            if ($qualification_id == 'MC') {
                                $report_bonus_register[$i]['tax_total'] = 0;
                                $report_bonus_register[$i]['bonus_full'] = 0;
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } else {

                                $wallet_total = $pv_upgrad_input * 8 / 100;
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
                    // 'position' => $position_update,
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
                    // 'position' => $position_update,
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

    public static function check_sponsor($user_name)
    {
        $max_attempts = 20;
        $attempt = 0;
        $current_user = $user_name;

        while ($attempt < $max_attempts) {
            $data_all = DB::table('customers')
                ->select('id', 'user_name', 'introduce_id', 'status_customer')
                ->where('user_name', $current_user)
                ->first();

            if ($current_user == '6135984') {
                return ['status' => 'fail_not_sponsor', 'data' => $current_user];
            }

            if (!$data_all) {
                // ถ้า user_name ไม่พบ ให้ fail
                return ['status' => 'fail', 'data' => null];
            }

            if ($data_all->status_customer != 'cancel') {
                // เจอผู้ที่สถานะไม่ใช่ cancel
                return ['status' => 'success', 'data' => $current_user, 'user_name' => $data_all->user_name];
            }

            // ถ้ายัง cancel ให้วนต่อไปยังผู้แนะนำ
            $current_user = $data_all->introduce_id;
            $attempt++;
        }

        // ถ้าเกิน 20 รอบแล้วยังไม่เจอ
        return ['status' => 'fail', 'data' => $current_user];
    }


    public static function up_lv($customers_user_name)
    {
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
                'customers.expire_date_bonus',

                'dataset_qualification.id as qualification_id_fk',

                'pv_upgrad',
                'qualification_id',
                'pv_today_downline_a',
                'pv_today_downline_b'
            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            ->where('user_name', '=', $customers_user_name)
            ->first();

        if ($data_user_upposition->qualification_id_fk <= 3) {
            $position =  'VVIP';
            $update_position = DB::table('customers')
                ->where('user_name', $data_user_upposition->user_name)
                ->update(['qualification_id' => $position]);

            DB::table('log_up_vl')->insert([
                'user_name' => $data_user_upposition->user_name,
                'introduce_id' => $data_user_upposition->introduce_id,
                'bonus_total' => $data_user_upposition->bonus_total,
                'old_lavel' => $data_user_upposition->qualification_id,
                'new_lavel' => $position,
                'status' => 'success'
            ]);
            $ms = $data_user_upposition->user_name
                . ' อัพตำแหน่งจาก ' . $data_user_upposition->qualification_id . ' เป็น VVIP จากการซื้อโปรโมชั่น สำเร็จ';
            @app(\App\Http\Controllers\Frontend\FC\LineController::class)->sendText($ms);

            return ['status' => 'success', 'message' => 'อัพตำแหน่งสำเร็จ'];
        } else {
            return ['status' => 'fail', 'message' => 'ไม่มีการอัพตำแหน่ง'];
        }
    }
    //รันโบนัส ฟาสสตาท
    public static function runbonus_faststart($user_name, $pv)
    {

        $pv_upgrad_total = $pv;
        $user_action = Customers::lockForUpdate()
            ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'pv', 'bonus_total', 'pv_upgrad', 'name', 'last_name')
            ->where('user_name', $user_name)
            ->first();

        $input_user_name_upgrad = $user_name;

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
            ->where('user_name', '=', $user_name)
            ->first();

        $old_position = $data_user->qualification_id;


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

                        $report_bonus_register[$i]['regis_user_name'] = $input_user_name_upgrad;
                        $report_bonus_register[$i]['regis_user_introduce_id'] = $data_user->introduce_id;
                        $report_bonus_register[$i]['regis_name'] = $data_user->name . ' ' . $data_user->last_name;
                        $report_bonus_register[$i]['user_name_g'] = $run_data_user->user_name;
                        $report_bonus_register[$i]['old_position'] = $data_user->qualification_id;
                        $report_bonus_register[$i]['new_position'] = 'VVIP';
                        $report_bonus_register[$i]['name_g'] = $run_data_user->name . ' ' . $run_data_user->last_name;
                        $report_bonus_register[$i]['qualification'] = $qualification_id;
                        $report_bonus_register[$i]['g'] = $i;
                        $report_bonus_register[$i]['pv'] = $pv;
                        $report_bonus_register[$i]['code_bonus'] = $code_bonus;
                        $report_bonus_register[$i]['type'] = 'jangpv';

                        $arr_user[$i]['user_name'] = $run_data_user->user_name;
                        $arr_user[$i]['lv'] = [$i];
                        if ($i == 1) {
                            $report_bonus_register[$i]['percen'] = 125;

                            $arr_user[$i]['pv'] = $pv;
                            $arr_user[$i]['position'] = $qualification_id;


                            if ($qualification_id == 'MC') {
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } else {
                                $wallet_total = $pv * 125 / 100;
                                $arr_user[$i]['bonus'] = $wallet_total;
                                $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                            }
                        } elseif ($i == 2) {
                            $report_bonus_register[$i]['percen'] = 17;
                            $arr_user[$i]['pv'] = $pv;
                            $arr_user[$i]['position'] = $qualification_id;
                            if ($qualification_id == 'MC') {
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } else {

                                $wallet_total = $pv * 17 / 100;
                                $arr_user[$i]['bonus'] = $wallet_total;
                                $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                            }
                        } elseif ($i == 3) {
                            $report_bonus_register[$i]['percen'] = 8;
                            $arr_user[$i]['pv'] = $pv;
                            $arr_user[$i]['position'] = $qualification_id;
                            if ($qualification_id == 'MC') {
                                $report_bonus_register[$i]['tax_total'] = 0;
                                $report_bonus_register[$i]['bonus_full'] = 0;
                                $report_bonus_register[$i]['bonus'] = 0;
                                $arr_user[$i]['bonus'] = 0;
                            } else {

                                $wallet_total = $pv * 8 / 100;
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
            $position_update = 'VVIP';
            DB::BeginTransaction();
            foreach ($report_bonus_register as $value) {
                DB::table('report_bonus_register')
                    ->updateOrInsert(
                        ['code_bonus' => $value['code_bonus'], 'user_name' => $value['user_name'], 'regis_user_name' => $value['regis_user_name'], 'g' => $value['g'], 'type' => $value['type']],
                        $value
                    );
            }
            DB::commit();

            $db_bonus_register = DB::table('report_bonus_register')
                ->where('status', '=', 'panding')
                ->where('bonus', '>', 0)
                ->where('code_bonus', '=', $code_bonus)
                ->where('regis_user_name', '=', $input_user_name_upgrad)
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
                'to_customer_username' => $user_name,
                'old_position' => $data_user->qualification_id,
                'position' => $position_update,
                'pv_old' => $user_action->pv,
                'pv' => $pv,
                'pv_balance' => $user_action->pv,
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



            // DB::table('customers')
            //     ->where('user_name', $data_user->user_name)
            //     ->update(['qualification_id' => $position_update, 'pv_upgrad' => $pv_upgrad_total]);

            $pv_upgrad = 1200;
            DB::table('customers')
                ->where('user_name', $data_user->user_name)
                ->update([
                    'qualification_id' => $position_update,
                    'pv_upgrad' => DB::raw('pv_upgrad + ' . (int) $pv_upgrad),
                ]);


            $user_action->save();

            $check_upline =  DB::table('customers')
                ->where('user_name', $data_user->user_name)
                ->first();

            if ($check_upline and empty($check_upline->upline_id) and empty($check_upline->uni_id) and $old_position == 'MC') {

                $data_upline = \App\Http\Controllers\Frontend\FC\UplineController::uplineAB($check_upline->introduce_id);

                if ($data_upline['status'] == 'fail') {

                    DB::rollback();
                    return ['status' => 'fail', 'message' => 'ลงทะเบียนไม่สำเร็จไม่สามารถหาสายงาน Upline ได้'];
                    // return redirect('jp_clarify')->withError('ลงทะเบียนไม่สำเร็จไม่สามารถหาสายงาน Upline ได้');
                }
                $data_uni = \App\Http\Controllers\Frontend\FC\UnilevelController::uplineAB($check_upline->introduce_id);

                if ($data_uni['status'] == 'fail') {
                    DB::rollback();
                    return ['status' => 'fail', 'message' => 'ลงทะเบียนไม่สำเร็จไม่สามารถหาสายงานได้'];
                    // return redirect('jp_clarify')->withError('ลงทะเบียนไม่สำเร็จไม่สามารถหาสายงานได้');
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
            return ['status' => 'success', 'message' => 'เแจงอัพเกรดรหัส' . $data_user->user_name . 'สำเร็จ'];
        } catch (Exception $e) {
            DB::rollback();

            return ['status' => 'fail', 'message' => 'Error' . $e->getMessage()];
        }
    }
}
