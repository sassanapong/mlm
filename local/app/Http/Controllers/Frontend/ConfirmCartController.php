<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Cart;
use PhpParser\Node\Stmt\Return_;

class ConfirmCartController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }
    public function index()
    {

        $cartCollection = Cart::session(1)->getContent();
        $data = $cartCollection->toArray();

        $quantity = Cart::session(1)->getTotalQuantity();
        if ($data) {
            foreach ($data as $value) {
                $pv[] = $value['quantity'] * $value['attributes']['pv'];
            }
            $pv_total = array_sum($pv);
        } else {
            $pv_total = 0;
        }


        $price = Cart::session(1)->getTotal();
        $price_total = number_format($price, 2);

        $bill = array(
            'price_total' => $price_total,
            'pv_total' => $pv_total,
            'data' => $data,
            'quantity' => $quantity,
            'status' => 'success',

        );


        return view('frontend/confirm_cart', compact('bill'));

    }



}
