<?php

namespace App\Http\Controllers\Frontend;

use App\AddressProvince;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{


    public function index()
    {

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


        $province = AddressProvince::orderBy('province_name', 'ASC')->get();


        return view('frontend/register')
            ->with('day', $day)
            ->with('arr_year', $arr_year)
            ->with('province', $province);
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
                'nation_id' => 'required',
                'id_card' => 'required:min:13',
                'phone' => 'required',
                // END ข้อมูลส่วนตัว

                // BEGIN ที่อยู่ตามบัตรประชาชน
                // 'file_card' => 'required',
                // 'card_address' => 'required',
                // 'card_moo' => 'required',
                // 'card_soi' => 'required',
                // 'card_road' => 'required',
                // 'card_province' => 'required',
                // 'card_district' => 'required',
                // 'card_tambon' => 'required',
                // 'card_zipcode' => 'required',
                // END ที่อยู่ตามบัตรประชาชน

                //  BEGIN ที่อยู่จัดส่ง
                // 'same_address' => 'required',
                // 'same_moo' => 'required',
                // 'same_soi' => 'required',
                // 'same_road' => 'required',
                // 'same_province' => 'required',
                // 'same_district' => 'required',
                // 'same_tambon' => 'required',
                // 'same_zipcode' => 'required',
                // END ที่อยู่จัดส่ง
            ],
            [
                // BEGIN ข้อมูลส่วนตัว
                'prefix_name.required' => 'กรุณากรอกข้อมูล',
                'customers_name.required' => 'กรุณากรอกข้อมูล',
                'gender.required' => 'กรุณากรอกข้อมูล',
                'business_name.required' => 'กรุณากรอกข้อมูล',
                'id_card.required' => 'กรุณากรอกข้อมูล',
                'id_card.min' => 'กรุณากรอกให้ครบ 13 หลัก',
                'phone.required' => 'กรุณากรอกข้อมูล',
                'phone.numeric' => 'เป็นตัวเลขเท่านั้น',
                'day.required' => 'กรุณากรอกข้อมูล',
                'month.required' => 'กรุณากรอกข้อมูล',
                'year.required' => 'กรุณากรอกข้อมูล',
                'nation_id.required' => 'กรุณากรอกข้อมูล',
                'id_card.required' => 'กรุณากรอกข้อมูล',
                'phone.required' => 'กรุณากรอกข้อมูล',
                // END ข้อมูลส่วนตัว

                // BEGIN ที่อยู่ตามบัตรประชาชน
                'file_card.required' => 'กรุณากรอกข้อมูล',
                'card_address.required' => 'กรุณากรอกข้อมูล',
                'card_moo.required' => 'กรุณากรอกข้อมูล',
                'card_soi.required' => 'กรุณากรอกข้อมูล',
                'card_road.required' => 'กรุณากรอกข้อมูล',
                'card_province.required' => 'กรุณากรอกข้อมูล',
                'card_district.required' => 'กรุณากรอกข้อมูล',
                'card_tambon.required' => 'กรุณากรอกข้อมูล',
                'card_zipcode.required' => 'กรุณากรอกข้อมูล',
                // END ที่อยู่ตามบัตรประชาชน

                // BEGIN ที่อยู่จัดส่ง
                'same_address.required' => 'กรุณากรอกข้อมูล',
                'same_moo.required' => 'กรุณากรอกข้อมูล',
                'same_soi.required' => 'กรุณากรอกข้อมูล',
                'same_road.required' => 'กรุณากรอกข้อมูล',
                'same_province.required' => 'กรุณากรอกข้อมูล',
                'same_district.required' => 'กรุณากรอกข้อมูล',
                'same_tambon.required' => 'กรุณากรอกข้อมูล',
                'same_zipcode.required' => 'กรุณากรอกข้อมูล',
                // END ที่อยู่จัดส่ง

            ]
        );

        if (!$validator->fails()) {
            //BEGIN วันเกิด
            $day = $request->day;
            $month = $request->month;
            $year = $request->year;

            $YMD = $year . "-" . $month . "-" . $day;

            $birth_day = date('Y-m-d', strtotime($YMD));
            // END วันเกิด
            $password = substr($request->id_card, -4);


            // BEGIN generatorusername เอา 7 หลัก
            $user_name = Auth::guard('c_user')->user()->count();

            // END generatorusername เอา 7 หลัก
            $dataPrepare = [
                'user_name' => '000' . ($user_name + 1),

                'password' => md5($password),
                'prefix_name' => $request->prefix_name,
                'customers_name' => $request->customers_name,
                'gender' => $request->gender,
                'business_name' => $request->business_name,
                'id_card' => $request->id_card,
                'phone' => $request->phone,
                'birth_day' => $birth_day,
                'nation_id' => $request->nation_id,
                'id_card' => $request->id_card,
                'phone' => $request->phone,
            ];




            $query_customers = Auth::guard('c_user')->user()->create($dataPrepare);

            //BEGIN ข้อมูล บัตรประชาชน

            $customers_id = $query_customers->id;

            if ($request->file_card) {

                $url = 'local/public/images/customers_card/' . date('Ym');
                $imageName = $request->file_card->extension();
                $filenametostore =  date("YmdHis") . '.' . $customers_id . "." . $imageName;
                $request->file_card->move($url,  $filenametostore);
            }


            //END ข้อมูล บัตรประชาชน



            if ($query_customers) {
                return response()->json(['status' => 'success'], 200);
            }
        }
        return response()->json(['error' => $validator->errors()]);
    }
}
