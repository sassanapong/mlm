<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{


    public function index()
    {
<<<<<<< Updated upstream
        return view('frontend/register');
=======

        // BEGIN  data year   ::: age_min 20 age_max >= 80
        $yeay = date('Y');
        $age_min = 20;
        $yeay_thai = date("Y", strtotime($yeay)) + 543 - $age_min;
        $arr_year = [];
        $age_max = 80;
        for ($i = 0; $i < $age_max; $i++) {
            $arr_year[] = date("Y", strtotime($yeay_thai)) + $i;
        }
        // END  data year   ::: age_min 20 age_max >= 80


        // BEGIN Day 
        $day = [];
        for ($i = 1; $i < 32; $i++) {
            $day[] = $i;
        }
        // END Day

        return view('frontend/register')
            ->with('day', $day)
            ->with('arr_year', $arr_year);
    }


    public function store_register(Request $request)
    {


        $validator = Validator::make(
            $request->all(),
            [
                // BEGIN ข้อมูลส่วนตัว
                'prefix_name' => 'required',
                'customers_name' => 'required',
                'gender' => 'required',
                'business_name' => 'required',
                'id_card' => 'required',
                'phone' => 'required|numeric',
                'day' => 'required',
                'month' => 'required',
                'year' => 'required',
                // END ข้อมูลส่วนตัว

                // BEGIN ที่อยู่ตามบัตรประชาชน
                'file_card' => 'required'

                // END ที่อยู่ตามบัตรประชาชน
            ],
            [
                // BEGIN ข้อมูลส่วนตัว
                'prefix_name.required' => 'กรุณากรอกข้อมูล',
                'customers_name.required' => 'กรุณากรอกข้อมูล',
                'gender.required' => 'กรุณากรอกข้อมูล',
                'business_name.required' => 'กรุณากรอกข้อมูล',
                'id_card.required' => 'กรุณากรอกข้อมูล',
                'phone.required' => 'กรุณากรอกข้อมูล',
                'phone.numeric' => 'เป็นตัวเลขเท่านั้น',
                'day.required' => 'กรุณากรอกข้อมูล',
                'month.required' => 'กรุณากรอกข้อมูล',
                'year.required' => 'กรุณากรอกข้อมูล',
                // END ข้อมูลส่วนตัว

                // BEGIN ที่อยู่ตามบัตรประชาชน
                'file_card.required' => 'กรุณากรอกข้อมูล'
                // END ที่อยู่ตามบัตรประชาชน

            ]
        );

        if (!$validator->fails()) {
            dd('success');
        }
        return response()->json(['error' => $validator->errors()]);
>>>>>>> Stashed changes
    }
}
