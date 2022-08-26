<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\CUser;
use Illuminate\Http\Request;
use Auth;
use illuminate\support\facades\validator;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function edit_profile()
    {
        return view('frontend/editprofile');
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
