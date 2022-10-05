<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Cart;
use Auth;
use PhpParser\Node\Stmt\Return_;
use App\Orders;
use App\Order_products_list;
use Haruncpi\LaravelIdGenerator\IdGenerator;

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
        $insert_db_orders = new Orders();
        $insert_order_products_list= new Order_products_list();
        $quantity = Cart::session(1)->getTotalQuantity();
        $insert_db_orders->quantity = $quantity;
        $customer_id = Auth::guard('c_user')->user()->id;

        $y = date('Y')+543;
        $y = substr($y,-2);

        $code_order =  IdGenerator::generate([
            'table' => 'db_orders',
            'field' => 'code_order',
            'length' => 15,
            'prefix' => 'NM'.$y.''.date("m").'-',
            'reset_on_prefix_change' => true
        ]);


        $insert_db_orders->customers_id_fk = $customer_id;
        $user_name = Auth::guard('c_user')->user()->user_name;
        $insert_db_orders->customers_user_name = $user_name;
        $business_location_id = Auth::guard('c_user')->user()->business_location_id;
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
            $insert_db_orders->delivery_province_id = $rs->province;
            $insert_db_orders->house_no = $rs->house_no;
            // $insert_db_orders->house_name = 'system';
            $insert_db_orders->moo = $rs->moo;
            $insert_db_orders->soi = $rs->soi;
            $insert_db_orders->tambon_id = $rs->tambon_id;
            $insert_db_orders->district_id = $rs->district_id;
            $insert_db_orders->province_id = $rs->province_id;
            $insert_db_orders->zipcode = $rs->zipcode;

            $insert_db_orders->tel = $rs->phone;
            $insert_db_orders->name = $rs->name;

        }else{
            $insert_db_orders->address_sent = 'other';
            $insert_db_orders->delivery_province_id = $rs->province;
            $insert_db_orders->house_no = $rs->same_address;
            // $insert_db_orders->house_name = 'system';
            $insert_db_orders->moo = $rs->same_moo;
            $insert_db_orders->soi = $rs->same_soi;
            $insert_db_orders->tambon_id = $rs->same_tambon;
            $insert_db_orders->district_id = $rs->district_id;
            $insert_db_orders->province_id = $rs->province_id;
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


                $pv[] = $value['quantity'] * $value['attributes']['pv'];
            }
            $pv_total = array_sum($pv);
        } else {
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
        $shipping = 0;
        $insert_db_orders->shipping_price = $shipping;
        $insert_db_orders->shipping_free = 1;//ส่งฟรี
        $insert_db_orders->sum_price = $price;
        $total_price = $price + $shipping;

        if(Auth::guard('c_user')->user()->ewallet <  $total_price){
            return redirect('cart')->withWarning('ไม่สามารถชำระเงินได้เนื่องจาก Ewallet ไม่พอสำหรับการจ่าย');

        }
        $insert_db_orders->total_price = $total_price;
        $insert_db_orders->tax = $vat;
        $insert_db_orders->tax_total = $p_vat;
        $insert_db_orders->order_status_id_fk = 2;
        $insert_db_orders->quantity = $quantity ;



        $insert_db_orders->code_order = $code_order;

        try {
            DB::BeginTransaction();

        $insert_db_orders->save();
        $insert_order_products_list::insert($insert_db_products_list);

        DB::commit();

        $resule = ['status' => 'success', 'message' => 'ทำรายการสั่งซื้อสำเร็จ', 'id' => $insert_db_orders->id];
        Cart::session(1)->clear();
        return redirect('Order')->withSuccess('ทำรายการสั่งซื้อสำเร็จ');
         } catch (\Exception $e) {
        DB::rollback();
        // info($e->getMessage());

        $resule = ['status' => 'fail', 'message' => 'Order Update Fail', 'id' => $insert_db_orders->id];
        return redirect('Order')->withError('Order Update Fail');
        }





    }




}
