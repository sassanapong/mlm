<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return view('frontend/order');
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
