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
use Haruncpi\LaravelIdGenerator\IdGenerator;

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

        if($quantity  == 0){
            return redirect('Order')->withWarning('ไม่มีสินค้าในตะกร้าสินค้า กรุณาเลือกสินค้า');
        }

        if ($data) {

            foreach ($data as $value) {
                $pv[] = $value['quantity'] * $value['attributes']['pv'];
                $product_shipping = DB::table('products_cost')
                ->where('product_id_fk',$value['id'])
                ->where('status_shipping','Y')
                ->first();
                if($product_shipping){
                    // $pv_shipping_arr[] = $value['quantity'] * $product_shipping->pv;
                    $pv_shipping_arr[] = $value['quantity'] * 20;
                }else{
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

    if($address){
        $shipping_zipcode = \App\Http\Controllers\Frontend\ShippingController::fc_shipping_zip_code($address->zipcode);
    }else{
        $shipping_zipcode = ['status'=>'fail','price'=>0,'ms'=>''];
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
        ->select('customers.*','dataset_qualification.business_qualifications as qualification_name','dataset_qualification.bonus')
        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
        ->where('user_name','=',Auth::guard('c_user')->user()->user_name)
        ->first();
        //$discount = floor($pv_total * $data_user->bonus/100);

        $price_total = $price + ($shipping+$shipping_zipcode['price']);

        $bill = array(
            'vat' => $vat,
            'shipping' => $shipping+$shipping_zipcode['price'],
            'price' => $price,
            'p_vat' => $p_vat,
            'price_vat' => $price_vat,
            'price_total' => $price_total,
            'pv_total' => $pv_total,
            'data' => $data,
            'price_shipping_pv'=>$shipping,
            'bonus'=>$data_user->bonus,
            'price_discount' => $price,
            // 'discount'=>$discount,
            'position'=>$data_user->qualification_name,
            'quantity' => $quantity,
            'location_id' => $business_location_id,
            'status' => 'success',
        );

        $customer = DB::table('customers')
            ->where('id', '=', Auth::guard('c_user')->user()->id)
            ->first();


        $province = DB::table('address_provinces')
            ->select('*')
            ->get();
        return view('frontend/confirm_cart', compact('customer', 'address', 'location', 'province', 'bill','shipping_zipcode'));
    }

    public static function check_custome_unline(Request $rs){


        if(empty(@$rs->user_name)){
            $data = array('status' => 'fail','ms'=>'กรุณากรอกข้อมูลรหัสที่สั่งซื้อให้ลูกทีม');
            return $data;
        }else{
            $sent_user_name = $rs->user_name ;
        }

        $user_name = Auth::guard('c_user')->user()->user_name;

        if(strtoupper($user_name) == strtoupper($rs->user_name) ){
            $data = array('status' => 'fail','ms'=>'สั่งซื้อให้เฉพาะลูกทีมเท่านั้น');
            return $data;
        }

        $data_user =  DB::table('customers')
        ->select('customers.id','customers.upline_id','customers.user_name','customers.name','customers.last_name','customers.pv','dataset_qualification.business_qualifications as qualification_name',
      'business_name')
        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
        ->where('user_name','=',$sent_user_name)
        ->first();




        if ($data_user) {
          $data = array('status' => 'success', 'data' => $data_user);
        } else {
          $data = array('status' => 'fail', 'data' => '','ms'=>'ไม่มีข้อมูลรหัส '.$sent_user_name);
        }


        return $data;

    }
    public function payment_submit(Request $rs)
    {
        $insert_db_orders = new Orders();
        $insert_order_products_list= new Order_products_list();
        $quantity = Cart::session(1)->getTotalQuantity();
        $insert_db_orders->quantity = $quantity;
        $customer_id = Auth::guard('c_user')->user()->id;



        $code_order = \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_order();


        $insert_db_orders->customers_id_fk = $customer_id;
        $insert_db_orders->tracking_type =$rs->tracking_type;
        $user_name = Auth::guard('c_user')->user()->user_name;
        $insert_db_orders->customers_user_name = $user_name;
        //$business_location_id = Auth::guard('c_user')->user()->business_location_id;
        $business_location_id = 1;
        $insert_db_orders->business_location_id_fk =  $business_location_id;

        if($insert_db_orders->sent_type_to_customer =='sent_type_other'){
            $insert_db_orders->customers_sent_id_fk = $rs->customers_sent_id_fk;
            $insert_db_orders->customers_sent_user_name = $rs->customers_sent_user_name;
            $insert_db_orders->status_payment_sent_other = 1;
        }else{
            $insert_db_orders->status_payment_sent_other = 0;
        }

        if($rs->receive == 'sent_address'){
            $insert_db_orders->address_sent = 'system';

            if(empty($rs->province_id) || empty($rs->zipcode)){
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

        }else{
            if(empty($rs->same_province) || empty($rs->same_zipcode)){
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

        if($quantity  == 0){
            return redirect('Order')->withWarning('สั่งซื้อไม่เสร็จ กรุณาทำรายการไหม่');
        }
        $i=0;
        $products_list = array();
        if ($data) {
            foreach ($data as $value) {
                $i++;
                $total_pv = $value['attributes']['pv'] * $value['quantity'];
				$total_price = $value['price'] * $value['quantity'];

                $insert_db_products_list[] = [
                    'code_order'=>$code_order,
                    'product_id_fk'=>$value['id'],
                    'product_unit_id_fk'=>@$value['product_unit_id'],
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
                ->where('product_id_fk',$value['id'])
                ->where('status_shipping','Y')
                ->first();
                if($product_shipping){
                    //$pv_shipping_arr[] = $value['quantity'] * $product_shipping->pv;
                    $pv_shipping_arr[] = $value['quantity'] * 20;
                }else{
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
        $insert_db_orders->product_value = $price_vat ;

        $shipping = \App\Http\Controllers\Frontend\ShippingController::fc_shipping($pv_shipping);
        $shipping_zipcode = \App\Http\Controllers\Frontend\ShippingController::fc_shipping_zip_code($insert_db_orders->zipcode);
        $shipping_total = $shipping+$shipping_zipcode['price'];



        if($shipping_total == 0){
            $insert_db_orders->shipping_free = 1;//ส่งฟรี
            $insert_db_orders->shipping_cost_id_fk = 1;
            $shipping_cost_name = DB::table('dataset_shipping_cost')
            ->where('id',1)
            ->first();


        }else{
            if( $shipping_zipcode['status'] == 'success'){
                $insert_db_orders->shipping_cost_id_fk = 3;
                $shipping_cost_name = DB::table('dataset_shipping_cost')
                ->where('id',3)
                ->first();
            }else{
                $insert_db_orders->shipping_cost_id_fk = 2;
                $shipping_cost_name = DB::table('dataset_shipping_cost')
                ->where('id',2)
                ->first();
            }
        }
        $insert_db_orders->shipping_cost_name = $shipping_cost_name->shipping_name;

        $insert_db_orders->sum_price = $price;

        $data_user =  DB::table('customers')
        ->select('dataset_qualification.business_qualifications as qualification_name','dataset_qualification.bonus')
        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
        ->where('user_name','=',Auth::guard('c_user')->user()->user_name)
        ->first();

        $insert_db_orders->position = $data_user->qualification_name;
        $insert_db_orders->bonus_percent = $data_user->bonus;

        //$discount = floor($pv_total * $data_user->bonus/100);
        $insert_db_orders->discount = 0;
        $total_price = $price + $shipping_total;

        if(Auth::guard('c_user')->user()->ewallet <  $total_price){
            return redirect('cart')->withWarning('ไม่สามารถชำระเงินได้เนื่องจาก Ewallet ไม่พอสำหรับการจ่าย');

        }
        $insert_db_orders->shipping_price = $shipping_total;
        $insert_db_orders->total_price = $total_price;
        $insert_db_orders->pv_total = $pv_total;
        $insert_db_orders->tax = $vat;
        $insert_db_orders->tax_total = $p_vat;
        $insert_db_orders->order_status_id_fk = 2;
        $insert_db_orders->quantity = $quantity ;
        $insert_db_orders->code_order = $code_order;

        try {
            DB::BeginTransaction();

        $insert_db_orders->save();
        $insert_order_products_list::insert($insert_db_products_list);
         $run_payment = ConfirmCartController::run_payment($code_order);

         Cart::session(1)->clear();

         if($run_payment['status'] == 'success'){
            DB::commit();
            return redirect('order_history')->withSuccess($run_payment['message']);
         }else{
            DB::rollback();
            return redirect('order_history')->withError($run_payment['message']);
         }

         } catch (\Exception $e) {

        DB::rollback();
        // dd($e);
        // info($e->getMessage());
        $resule = ['status' => 'fail', 'message' => 'Order Update Fail', 'id' => $insert_db_orders->id];
        return redirect('Order')->withError('Order Update Fail');
        }


    }

    public function run_payment($code_order){
        $order = DB::table('db_orders')
        ->where('code_order', '=',$code_order)
        ->where('order_status_id_fk', '=',2)
        ->first();

        if($order){

            $order_update = Orders::find($order->id);

            if ($order->status_payment_sent_other == 1) {
                $customer_id = $order->customers_sent_id_fk;
            } else {
                $customer_id = $order->customers_id_fk;
            }


            $customer_update = Customers::find($customer_id);

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

            $customer_update->ewallet_use = $ewallet_use;
            $customer_update->bonus_total = $bonus_total;
            $pv_old = $customer_update->pv;
            $order_update->pv_old = $customer_update->pv;
            $ewallet_old = $customer_update->ewallet;
            $order_update->ewallet_old =$ewallet_old;
            $order_update->ewallet_price = $order->total_price;

            $customer_update->pv_all = $pv_all+$order->pv_total;
            $pv_balance = $customer_update->pv+$order->pv_total;
            $customer_update->pv = $pv_balance;
            $ewallet = $ewallet_old-$order->total_price;

            if($ewallet < 0){
                $resule = ['status' => 'fail', 'message' => 'สั่งซื้อสินค้าไม่สำเร็จ ewallet ของคุณมีไม่เพียงพอ'];
                return $resule;
            }else{
                $customer_update->ewallet =  $ewallet;
            }


            $pv_banlance = $customer_update->pv+$order->pv_total;
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
            $jang_pv->pv_balance =  $pv_balance;
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
            return $resule;

        }else{
            $resule = ['status' => 'fail', 'message' => 'สั่งซื้อสินค้าไม่สำเร็จ กรุณาเช็ครายการสินค้าในหน้าประวิตสินค้า'];
            return $resule;
        }

    }




}
