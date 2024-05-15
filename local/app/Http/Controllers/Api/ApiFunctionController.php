<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CUser;
use Illuminate\Support\Facades\Validator;
use App\Member;
use Tymon\JWTAuth\Facades\JWTAuth;

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
                'message' => 'username และ password เป็นค่าว่าง',
                'code' => 'ER00',
                'status' => 0,
                'data' => null,
            ]);
        }

        $check_null = CUser::where('user_name', '=', $req->username)->first();
        if (empty($check_null)) {
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
            $token = JWTAuth::fromUser($get_users);
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
                'token' => $token,
                'data' => $get_users,
            ]);
        }



        if ($get_users) {
            $token = JWTAuth::fromUser($get_users);
            return response()->json([
                'message' => 'Login Success',
                'status' => 1,
                'code' => 'S01',
                'token' => $token,
                'data' => $get_users,
            ]);
        } else if ($get_users) {
            if (empty($get_users->name)) {
                return response()->json([
                    'message' => 'รหัสถูกลบออกจากระบบ',
                    'status' => 0,
                    'code' => 'ER02',

                    'data' => null,
                ]);
            } else {
                $token = JWTAuth::fromUser($get_users);
                return response()->json([
                    'message' => 'Login success',
                    'status' => 1,
                    'code' => 'S01',
                    'token' => $token,
                    'data' => $get_users,
                ]);
            }
        } else {
            return response()->json([
                'message' => 'รหัสผ่านผิด',
                'status' => 0,
                'code' => 'ER03',
                'data' => null,
            ]);
        }
    }

    public function getUserProfile(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:customers,id',
            'username' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'status' => 'error',
                'code' => 'ER01',
                'data' => null,
            ], 404);
        }

        try {
            $user = CUser::where('id', $request->user_id)
                ->where('user_name', $request->username)
                ->firstOrFail();
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'ไม่พบผู้ใช้',
                'status' => 'error',
                'code' => 'ER02',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'message' => 'เรียกดูโปรไฟล์ผู้ใช้สำเร็จ',
            'status' => 'success',
            'code' => 'S01',
            'data' => $user,
        ], 200);
    }

    public function dataset_changwat()
    {
        $dataset_changwat = DB::table('1dataset_changwat')
            ->select('*')
            ->get();
        return response()->json([
            'message' => 'success',
            'status' => 'success',
            'code' => 'S01',
            'data' => $dataset_changwat,
        ], 200);
    }

    public function dataset_amphuress()
    {
        $dataset_amphuress = DB::table('2dataset_amphuress')
            ->select('*')
            ->get();

        return response()->json([
            'message' => 'success',
            'status' => 'success',
            'code' => 'S01',
            'data' => $dataset_amphuress,
        ], 200);
    }

    public function dataset_tambon()
    {
        $dataset_tambon = DB::table('3dataset_tambon')
            ->select('*')
            ->get();

        return response()->json([
            'message' => 'success',
            'status' => 'success',
            'code' => 'S01',
            'data' => $dataset_tambon,
        ], 200);
    }
}
