<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
class OrderController extends Controller
{
    public function index()
    {

        $categories = DB::table('dataset_categories')
            ->where('lang_id', '=', 1)
            ->where('status', '=', 1)
            ->orderby('order')
            ->get();

            $product_all = OrderController::product_list('1');

        return view('frontend/order',compact('product_all','categories'));
    }


    public static function product_list($type){

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
        ->where('products.orders_type_id','LIKE','%'.$type.'%')
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
