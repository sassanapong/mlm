<?php

namespace App\Http\Controllers\Frontend;

use App\AddressProvince;
use App\Http\Controllers\Controller;
use App\Models\CUser;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
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



        return view('frontend/editprofile')
            ->with('province', $province) //จังหวัด
            ->with('customers_info', $customers_info) //ข้อมูลส่วนตัว
            ->with('customers_day', $customers_day) //วันเกิด
            ->with('customers_month', $customers_month) //เดือนเกิด
            ->with('customers_year', $customers_year); //ปีเกิด
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
