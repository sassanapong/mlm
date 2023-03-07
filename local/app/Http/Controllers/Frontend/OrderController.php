<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Cart;
use PhpParser\Node\Stmt\Return_;
use Auth;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }
    public function index()
    {

        // Cart::session(1)->clear();

        $categories = DB::table('dataset_categories')
            ->where('lang_id', '=', 1)
            ->where('status', '=', 1)
            ->get();

        $product_all = OrderController::product_list();


        return view('frontend/order', compact('product_all', 'categories'));
    }


    public function cancel_order()
    {

         Cart::session(1)->clear();
         return redirect('Order')->withSuccess('ยกเลิกรายการสั่งซื้อเรียบร้อย');

    }


    public static function product_list($categories = '')
    {
        if(empty($categories)){
            $product = DB::table('products')
            ->select(
                'products.id as products_id',
                'products_details.*',
                'products_images.*',
                'products_cost.*',
                'dataset_currency.*',
            )
            ->leftjoin('products_details', 'products.id', '=', 'products_details.product_id_fk')
            ->leftjoin('products_images', 'products.id', '=', 'products_images.product_id_fk')
            ->leftjoin('products_cost', 'products.id', '=', 'products_cost.product_id_fk')
            ->leftjoin('dataset_currency', 'dataset_currency.id', '=', 'products_cost.currency_id')
            ->where('products_images.image_default', '=', 1)
            ->where('products_details.lang_id', '=', 1)
            ->where('products.status', '=', 1)
            ->where('products_cost.business_location_id', '=', 1)
            ->orderby('products.id')
            ->get();
        }else{


            $product = DB::table('products')
            ->select(
                'products.id as products_id',
                'products_details.*',
                'products_images.img_url',
                'products_images.product_img',
                'products_images.image_default',

                'products_cost.*',
                'dataset_currency.*',
            )
            ->leftjoin('products_details', 'products.id', '=', 'products_details.product_id_fk')
            ->leftjoin('products_images', 'products.id', '=', 'products_images.product_id_fk')
            ->leftjoin('products_cost', 'products.id', '=', 'products_cost.product_id_fk')
            ->leftjoin('dataset_currency', 'dataset_currency.id', '=', 'products_cost.currency_id')
            ->where('products.category_id','=',$categories)
            ->where('products_images.image_default', '=', 1)
            ->where('products_details.lang_id', '=', 1)
            ->where('products.status', '=', 1)
            ->where('products_cost.business_location_id', '=', 1)
            ->orderby('products.id')
            ->get();

        }


        //->Paginate(4);
        //dd($product);

        $data = array(
            'product' => $product
        );
        return $data;
    }
    public static function get_product(Request $rs)
    {
        $product = DB::table('products')
            ->select(
                'products.id as products_id',
                'products_details.*',
                'products_images.*',
                'products_cost.*',
                'dataset_currency.*',
            )
            ->leftjoin('products_details', 'products.id', '=', 'products_details.product_id_fk')
            ->leftjoin('products_images', 'products.id', '=', 'products_images.product_id_fk')
            ->leftjoin('products_cost', 'products.id', '=', 'products_cost.product_id_fk')
            ->leftjoin('dataset_currency', 'dataset_currency.id', '=', 'products_cost.currency_id')
            ->where('products.id', '=', $rs->product_id)
            ->where('products_images.image_default', '=', 1)
            ->where('products_details.lang_id', '=', 1)
            ->where('products.status', '=', 1)
            ->where('products_cost.business_location_id', '=', 1)
            ->first();

        $data = array(
            'product' => $product
        );
        return $data;
    }


    public function add_cart(Request $rs)
    {


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
            ->where('products.id', '=', $rs->id)
            ->where('products_images.image_default', '=', 1)
            ->where('products_details.lang_id', '=', 1)
            ->where('products.status', '=', 1)
            ->where('products_cost.business_location_id', '=', 1)
            ->first();


        if ($product) {
            Cart::session(1)->add(array(
                'id' => $product->products_id, // inique row ID
                'name' => $product->product_name,
                'price' => $product->member_price,
                'quantity' => $rs->quantity,
                'attributes' => array(
                    'pv' => $product->pv,
                    'img' => asset($product->img_url . '' . $product->product_img),
                    'product_unit_id'=>$product->product_unit_id,
                    'product_unit_name' => $product->product_unit_name,
                    'descriptions' => $product->descriptions,
                    // 'promotion_id' => $rs->id,
                    'detail' => '',
                    // 'category_id' => $product->category_id,
                ),
            ));

            $getTotalQuantity = Cart::session(1)->getTotalQuantity();

            // $item = Cart::session($request->type)->getContent();
            $data = ['status' => 'success', 'qty' => $getTotalQuantity];
        } else {
            $data = ['status' => 'fail', 'ms' => 'ไม่พบสินค้าในระบบกรุณาทำรยการไหม่อีกครั้ง'];
        }


        return $data;
    }

    public function cart()
    {


        $cartCollection = Cart::session(1)->getContent();
        $data = $cartCollection->toArray();


        $quantity = Cart::session(1)->getTotalQuantity();

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
                    //$pv_shipping_arr[] = $value['quantity'] * $product_shipping->pv;
                    $pv_shipping_arr[] = $value['quantity'] * 20;
                }else{
                    $pv_shipping_arr[] = 0;
                }

            }


            $pv_shipping = array_sum($pv_shipping_arr);
            $pv_total = array_sum($pv);
        } else {
            $pv_total = 0;
            $pv_shipping = 0;
        }



        $data_user =  DB::table('customers')
        ->select('dataset_qualification.business_qualifications as qualification_name','dataset_qualification.bonus')
        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
        ->where('user_name','=',Auth::guard('c_user')->user()->user_name)
        ->first();



        $price = Cart::session(1)->getTotal();
        $shipping = \App\Http\Controllers\Frontend\ShippingController::fc_shipping($pv_shipping);
        $price_total = number_format($price+$shipping, 2);

        $discount = floor($pv_total * $data_user->bonus/100);

        $bill = array(
            'price_total' => $price_total,
            'shipping'=>$shipping,
            'pv_total' => $pv_total,
            'data' => $data,
            'bonus'=>$data_user->bonus,
            'discount'=>$discount,
            'position'=>$data_user->qualification_name,
            'quantity' => $quantity,
            'status' => 'success',

        );


        return view('frontend/cart', compact('bill'));
    }

    public function cart_delete(Request $request)
    {
        //dd($request->all());
        Cart::session(1)->remove($request->data_id);
        return redirect('cart')->withSuccess('Deleted Success');
    }




    public function quantity_change(Request $request){
        if ($request->product_id) {
            Cart::session(1)->update($request->product_id, array(
                'quantity' => array(
                    'relative' => false,
                    'value' => $request->productQty,
                ),
            ));
            return redirect('cart')->withSuccess('แก้ไขจำนวนสำเร็จ');
        }else{
            return redirect('cart')->withError('ไม่สามารถแก้ไขจำนวนสินค้าได้');

        }


    }





    // ประวัติการสั่งซื้อ
    public function order_history()
    {
        return view('frontend/order-history');
    }

    // รายละเอียดของ ออเดอร์
    public function order_detail($code_order)
    {

        $orders_detail = DB::table('db_orders')
        ->select(

            'customers.user_name',
            'customers.name',
            'customers.last_name',
            'dataset_order_status.detail',
            'dataset_order_status.css_class',
            'db_orders.*',
        )
        ->leftjoin('customers', 'customers.id','db_orders.customers_id_fk')
        ->leftjoin('dataset_order_status', 'dataset_order_status.orderstatus_id','db_orders.order_status_id_fk')
        ->where('code_order', $code_order)
        ->get()

        ->map(function ($item) use ($code_order) {
            $item->address = DB::table('db_orders')
                ->select(
                    'house_no',
                    'house_name',
                    'moo',
                    'soi',
                    'road',
                    'district_name as district',
                    'province_name as province',
                    'tambon_name as tambon',
                    'db_orders.zipcode',
                    'email',
                    'tel',
                )
                ->leftjoin('address_districts', 'address_districts.district_id', 'db_orders.district_id')
                ->leftjoin('address_provinces', 'address_provinces.province_id', 'db_orders.province_id')
                ->leftjoin('address_tambons', 'address_tambons.tambon_id', 'db_orders.tambon_id')
                ->GroupBy('house_no')
                ->where('code_order', $code_order)
                ->get();
            return $item;
        })

        // เอาข้อมูลสินค้าที่อยู่ในรายการ order
        ->map(function ($item) use ($code_order) {
            $item->product_detail = DB::table('db_order_products_list')
                ->leftjoin('products_details', 'products_details.product_id_fk', 'db_order_products_list.product_id_fk')
                ->leftjoin('products_images', 'products_images.product_id_fk', 'db_order_products_list.product_id_fk')
                ->where('products_details.lang_id', 1)
                ->where('code_order', $code_order)
                ->GroupBy('products_details.product_name')
                ->get();
            return $item;
        });

        // dd($orders_detail);

        if(count($orders_detail) <= 0){
            return redirect('order_history')->withWarning('ไม่มีข้อมูลการสั่งซื้อเลขบิลนี้');
        }

        return view('frontend/order-detail',compact('orders_detail'));
    }
}
