<?php

namespace App\Http\Controllers\Frontend;

use App\AddressProvince;
use App\CustomersAddressCard;
use App\CustomersAddressDelivery;
use App\CustomersBank;
use App\CustomersBenefit;
use App\Http\Controllers\Controller;
use App\Models\CUser;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Ui\Presets\React;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('customer');
    }

    public function edit_profile()
    {

        $province = AddressProvince::orderBy('province_name', 'ASC')->get();

        //BEGIN ข้อมูลส่วนตัวของ customers
        $customers_id = Auth::guard('c_user')->user()->id;
        $customers_info = Auth::guard('c_user')->user()->where('id', $customers_id)->first();
        //END ข้อมูลส่วนตัวของ customers

        //BEGIN แยกวันเกิดจากที่ดึงมาใน DB จาก Y-m-d แยก ออกวันอันๆ
        $customers_day = date('d', strtotime($customers_info->birth_day));
        $customers_month = date('m', strtotime($customers_info->birth_day));
        $customers_year = date('Y', strtotime($customers_info->birth_day));
        //END แยกวันเกิดจากที่ดึงมาใน DB จาก Y-m-d แยก ออกวันอันๆ


        // BEGIN ข้อมูลบัตรประชาชน
        $address_card = CustomersAddressCard::where('customers_id', $customers_id)->first();
        // END ข้อมูลบัตรประชาชน

        // BEGIN ที่อยู่จัดส่ง
        $address_delivery = CustomersAddressDelivery::where('customers_id', $customers_id)->first();
        // END ที่อยู่จัดส่ง

        // BEGIN ข้อมูลธนาคาร
        $info_bank = CustomersBank::where('customers_id', $customers_id)->first();
        // END ข้อมูลธนาคาร

        // BEGIN ผู้รับผลประโยชน์
        $info_benefit = CustomersBenefit::where('customers_id', $customers_id)->first();
        // END ผู้รับผลประโยชน์


        return view('frontend/editprofile')
            ->with('province', $province) //จังหวัด
            ->with('customers_info', $customers_info) //ข้อมูลส่วนตัว
            ->with('customers_day', $customers_day) //วันเกิด
            ->with('customers_month', $customers_month) //เดือนเกิด
            ->with('customers_year', $customers_year) //ปีเกิด
            ->with('address_card', $address_card) //ข้อมูลบัตรประชาชน
            ->with('address_delivery', $address_delivery) //ข้อมูลที่อยู่จัดส่ง
            ->with('info_bank', $info_bank) //ข้อมูลธนาคาร
            ->with('info_benefit', $info_benefit); //ผู้รับผลประโยชน์
    }


    public function update_customers_info(Request $request)
    {



        $validator = Validator::make(
            $request->all(),
            [
                'email' => 'email',
            ],
            [
                'email.email' => 'กรุณากรอกอีเมลให้ถูกต้อง',
            ]
        );

        if (!$validator->fails()) {

            $dataprepare = [
                'email' => $request->email,
                'line_id' => $request->line_id,
                'facebook' => $request->facebook,
            ];


            $customers_id = Auth::guard('c_user')->user()->id;
            $query_customers_info = Auth::guard('c_user')->user()->where('id', $customers_id)->update($dataprepare);
            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['error' => $validator->errors()]);
    }


    public function update_same_address(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'same_address' => 'required',
                'same_moo' => 'required',
                'same_soi' => 'required',
                'same_road' => 'required',
                'same_province' => 'required',
                'same_district' => 'required',
                'same_tambon' => 'required',
                'same_zipcode' => 'required',
            ],
            [
                'same_address.required' => 'กรุณากรอกข้อมูล',
                'same_moo.required' => 'กรุณากรอกข้อมูล',
                'same_soi.required' => 'กรุณากรอกข้อมูล',
                'same_road.required' => 'กรุณากรอกข้อมูล',
                'same_province.required' => 'กรุณากรอกข้อมูล',
                'same_district.required' => 'กรุณากรอกข้อมูล',
                'same_tambon.required' => 'กรุณากรอกข้อมูล',
                'same_zipcode.required' => 'กรุณากรอกข้อมูล',
            ]
        );

        if (!$validator->fails()) {
            $customers_id = Auth::guard('c_user')->user()->id;
            $user_name = Auth::guard('c_user')->user()->user_name;

            //BEGIN สถานะว่า เอาข้อมูลมาจากไหน 1= บปช , 2= กรอกมาเอง
            if ($request->status_address) {
                $status_address = 1;
            } else {
                $status_address = 2;
            }
            //END สถานะว่า เอาข้อมูลมาจากไหน 1= บปช , 2= กรอกมาเอง

            $dataPrepare = [
                'address' => $request->same_address,
                'moo' => $request->same_moo,
                'soi' => $request->same_soi,
                'road' => $request->same_road,
                'tambon' => $request->same_tambon,
                'district' => $request->same_district,
                'province' => $request->same_province,
                'zipcode' => $request->same_zipcode,
                'phone' => $request->same_phone,
                'status' => $status_address
            ];


            $query_customers_info = CustomersAddressDelivery::where('customers_id', $customers_id)->updateOrInsert(['customers_id'=>$customers_id,'user_name'=>$user_name],$dataPrepare);
            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['error' => $validator->errors()]);
    }



    public function cerate_info_bank_last(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'file_bank' => 'required|mimes:jpeg,jpg,png',
                'bank_name' => 'required',
                'bank_branch' => 'required',
                'bank_no' => 'required|numeric',
                'account_name' => 'required',
            ],
            [
                'file_bank.required' => 'กรุณาแนบเอกสาร',
                'file_bank.mimes' => 'รองรับไฟล์นามสกุล jpeg,jpg,png เท่านั้น',
                'bank_name.required' => 'กรุณากรอกข้อมูล',
                'bank_branch.required' => 'กรุณากรอกข้อมูล',
                'bank_no.required' => 'กรุณากรอกข้อมูล ',
                'bank_no.numeric' => 'ใส่เฉพาะตัวเลขเท่านั้น',
                'account_name.required' => 'กรุณากรอกข้อมูล',
            ]
        );

        if (!$validator->fails()) {
            $customers_id = Auth::guard('c_user')->user()->id;
            $customers_user_name = Auth::guard('c_user')->user()->user_name;
            $url = 'local/public/images/customers_bank/' . date('Ym');
            $imageName = $request->file_bank->extension();
            $filenametostore =  date("YmdHis") . '.' . $customers_id . "." . $imageName;
            $request->file_bank->move($url,  $filenametostore);

            $dataPrepare = [
                'customers_id' => $customers_id,
                'user_name' => $customers_user_name,
                'url' => $url,
                'img_bank' => $filenametostore,
                'bank_name' => $request->bank_name,
                'bank_branch' => $request->bank_branch,
                'bank_no' => $request->bank_no,
                'account_name' => $request->account_name,
            ];

            $rquery_bamk = CustomersBank::create($dataPrepare);

            return response()->json(['status' => 'success'], 200);
        }
        return response()->json(['error' => $validator->errors()]);
    }





    // เปลี่ยนรหัสผ่าน
    public function change_password(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'password' => 'required',
                'password_new' => 'required',
                'password_new_comfirm' => 'required',
                'check_comfirm' => 'required',
            ],
            [
                'password.required' => 'กรุณากรอกรหัสผ่านเดิม',
                'password_new.required' => 'กรุณากรอกรหัสผ่านใหม่',
                'password_new_comfirm.required' => 'กรุณากรอกยืนยันรหัสผ่านใหม่ ',
                'check_comfirm.required' => 'กรุณากดยืนยันการเปลี่ยนรหัสผ่าน',
            ]
        );

        if (!$validator->fails()) {


            $password = md5($request->password);
            $password_new = md5($request->password_new);
            $password_new_comfirm = md5($request->password_new_comfirm);

            $user_id = Auth::guard('c_user')->user()->id;

            $Cuser = CUser::where('id', $user_id)->first();

            // Check รหัสผ่านเดิมที่กรอกมาตรงกันของเดิมหรือไม่
            if (($password == $Cuser->password)) {

                // Check รหัสผ่านใหม่ ต้องตรงกันทั้ง 2 อัน
                if ($password_new == $password_new_comfirm) {
                    $Cuser->password = md5($request->password_new);
                    $Cuser->save();
                    return redirect('logout');
                }
                return response()->json(['error' => ['password_new_comfirm' => 'รหัสผ่านใหม่ไม่ตรงกัน']]);
            } else {
                return response()->json(['error' => ['password' => 'รหัสผ่านเดิมไม่ถูกต้อง']]);
            }
        }
        return response()->json(['error' => $validator->errors()]);
    }
}
