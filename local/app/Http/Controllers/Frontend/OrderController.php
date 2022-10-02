<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Cart;
use PhpParser\Node\Stmt\Return_;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }
    public function index()
    {

        $categories = DB::table('dataset_categories')
            ->where('lang_id', '=', 1)
            ->where('status', '=', 1)
            ->get();

            $product_all = OrderController::product_list();

        return view('frontend/order',compact('product_all','categories'));
    }


    public static function product_list(){

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
        ->where('products_cost.business_location_id','=', 1)
        ->orderby('products.id')
        ->get();
        //->Paginate(4);
        //dd($product);

        $data = array(
            'product' => $product);
        return $data;

    }
    public static function get_product(Request $rs){
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
        ->where('products.id','=',$rs->product_id)
        ->where('products_images.image_default', '=', 1)
        ->where('products_details.lang_id', '=', 1)
        ->where('products.status', '=', 1)
        ->where('products_cost.business_location_id','=', 1)
        ->first();

        $data = array(
            'product' => $product);
        return $data;

    }


    public function add_cart(Request $request)
    {

        $product = DB::table('products')
        ->select(
            'products.id as products_id',
            'products_details.*',
            'products_images.*',
            'products_cost.*',
            'dataset_currency.*',
            'dataset_product_unit.product_unit as product_unit_name',
        )
        ->leftjoin('products_details', 'products.id', '=', 'products_details.product_id_fk')
        ->leftjoin('products_images', 'products.id', '=', 'products_images.product_id_fk')
        ->leftjoin('products_cost', 'products.id', '=', 'products_cost.product_id_fk')
        ->leftjoin('dataset_currency', 'dataset_currency.id', '=', 'products_cost.currency_id')
        ->leftjoin('dataset_product_unit', 'dataset_product_unit.product_unit_id', '=', 'products.unit_id')
        ->where('products.id','=',$request->id)
        ->where('products_images.image_default', '=', 1)
        ->where('products_details.lang_id', '=', 1)
        ->where('products.status', '=', 1)
        ->where('products_cost.business_location_id','=', 1)
        ->first();

        dd();
        if( $product){


            Cart::session(1)->add(array(
                'id' => $product->id, // inique row ID
                'name' => $product->product_name,
                'price' => $product->member_price,
                'quantity' => $request->qty,
                'attributes' => array(
                    'pv' => $product->pv,
                    'img' => asset($product->img_url . '' . $product->product_img),
                    // 'product_unit_id'=>$product->unit_id,
                    'product_unit_name'=>$product->product_unit_name,
                    // 'promotion' => $product->promotion,
                    // 'promotion_id' => $request->id,
                    // 'promotion_detail' => '',
                    // 'category_id' => $product->category_id,
                ),
            ));
            $getTotalQuantity = Cart::session(1)->getTotalQuantity();

            // $item = Cart::session($request->type)->getContent();
            $data = ['status'=>'success','qty'=>$getTotalQuantity];

        }else{
            $data = ['status'=>'fail','ms'=>'ไม่พบสินค้าในระบบกรุณาทำรยการไหม่อีกครั้ง'];

        }

        return $data;


    }



    // ประวัติการสั่งซื้อ
    public function order_history()
    {
        return view('frontend/order-history');
    }

    // รายละเอียดของ ออเดอร์
    public function order_detail()
    {
        return view('frontend/order-detail');
    }
}
