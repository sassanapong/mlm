<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Cart;
use Auth;
use PhpParser\Node\Stmt\Return_;
use App\Orders;

class ConfirmCartController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }
    public function index()
    {
        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
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
            }
            $pv_total = array_sum($pv);
        } else {
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
        $shipping = 0;


        $vat = DB::table('dataset_vat')
            ->where('business_location_id_fk', '=', $business_location_id)
            ->first();

        $vat = $vat->vat;


        //vatใน 7%
        $p_vat = $price * ($vat / (100 + $vat));

        //มูลค่าสินค้า
        $price_vat = $price - $p_vat;

        $price_total = $price + $shipping;




        $bill = array(
            'vat' => $vat,
            'shipping' => $shipping,
            'price' => $price,
            'p_vat' => $p_vat,
            'price_vat' => $price_vat,
            'price_total' => $price_total,
            'pv_total' => $pv_total,
            'data' => $data,
            'quantity' => $quantity,
            'location_id' => $business_location_id,
            'status' => 'success',
        );

        $customer = DB::table('customers')
            ->where('id', '=', Auth::guard('c_user')->user()->id)
            ->first();

        $address = DB::table('customers_address_delivery')
            ->select('customers_address_delivery.*', 'address_provinces.province_id', 'address_provinces.province_name', 'address_tambons.tambon_name', 'address_tambons.tambon_id', 'address_districts.district_id', 'address_districts.district_name')
            ->leftjoin('address_provinces', 'address_provinces.province_id', '=', 'customers_address_delivery.province')
            ->leftjoin('address_districts', 'address_districts.district_id', '=', 'customers_address_delivery.district')
            ->leftjoin('address_tambons', 'address_tambons.tambon_id', '=', 'customers_address_delivery.tambon')
            ->where('user_name', '=', $user_name)
            ->first();

        $province = DB::table('address_provinces')
            ->select('*')
            ->get();
        return view('frontend/confirm_cart', compact('customer', 'address', 'location', 'province', 'bill'));
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
        ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=','customers.qualification_id')
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
        dd($rs->all());
        $insert_db_orders = new Db_Orders();
        $quantity = Cart::session(1)->getTotalQuantity();
        $insert_db_orders->quantity = $quantity;
        $customer_id = Auth::guard('c_user')->user()->id;
        $insert_db_orders->customers_id_fk = $customer_id;
        $user_name = Auth::guard('c_user')->user()->user_name;
        $insert_db_orders->customers_user_name = $user_name;

        if($insert_db_orders->sent_type_to_customer =='sent_type_other'){
            $insert_db_orders->customers_sent_id_fk = $user_name;
            $insert_db_orders->customers_sent_user_name = $user_name;



        }

        $insert_db_orders->customers_username = $user_name;
        $insert_db_orders->code_order = '';


        // id


        // customers_sent_id_fk
        // customers_sent_user_name
        // address_sent_id_fk
        // business_location_id_fk
        // sentto_branch_id
        // delivery_location
        // delivery_location_frontend
        // delivery_province_id
        // code_order
        // pay_type_id_fk
        // transfer_price
        // credit_price
        // account_bank_id
        // account_bank_name_customer
        // transfer_money_datetime
        // file_slip
        // note
        // tracking_type
        // tracking_no
        // product_value
        // tax
        // fee
        // shipping_price
        // shipping_free
        // shipping_cost_id_fk
        // quantity
        // sum_price
        // total_price
        // pv_total
        // pv_banlance
        // pv_old
        // active_mt_date
        // active_tv_date
        // status_pv_mt_old
        // aistockist
        // agency
        // house_no
        // house_name
        // moo
        // soi
        // tambon_id
        // district_id
        // province_id
        // road
        // zipcode
        // email
        // tel
        // name
        // status_payment_sent_other
        // action_date
        // approve_status
        // order_status_id_fk
        // approver
        // approve_date
        // created_at
        // updated_at
        // deleted_at


        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
        // $location = Location::location($business_location_id, $business_location_id);
        $location = '';
        $cartCollection = Cart::session(1)->getContent();
        $data = $cartCollection->toArray();
        $quantity = Cart::session(1)->getTotalQuantity();



        if($quantity  == 0){
            return redirect('Order')->withWarning('สั่งซื้อไม่เสร็จ กรุณาทำรายการไหม่');
        }

        if ($data) {
            foreach ($data as $value) {
                $pv[] = $value['quantity'] * $value['attributes']['pv'];
            }
            $pv_total = array_sum($pv);
        } else {
            $pv_total = 0;

        }

        //ราคาสินค้า
        $price = Cart::session(1)->getTotal();


        $shipping = 0;


        $vat = DB::table('dataset_vat')
            ->where('business_location_id_fk', '=', $business_location_id)
            ->first();

        $vat = $vat->vat;


        //vatใน 7%
        $p_vat = $price * ($vat / (100 + $vat));

        //มูลค่าสินค้า
        $price_vat = $price - $p_vat;

        $price_total = $price + $shipping;

        $bill = array(
            'vat' => $vat,
            'shipping' => $shipping,
            'price' => $price,
            'p_vat' => $p_vat,
            'price_vat' => $price_vat,
            'price_total' => $price_total,
            'pv_total' => $pv_total,
            'data' => $data,
            'quantity' => $quantity,
            'location_id' => $business_location_id,
        );




    }




}
