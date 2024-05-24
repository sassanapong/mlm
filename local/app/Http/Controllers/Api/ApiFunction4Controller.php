<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use DB;

class ApiFunction4Controller extends Controller
{
    public static $arr = array();


    public function get_sponser(Request $request)
    {

        $validator = Validator::make($request->all(), [

            'username' => 'required|exists:customers,user_name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'status' => 'error',
                'code' => 'ER01',
                'data' => null,
            ], 404);
        }

        $user = DB::table('customers')
            ->select(
                'customers.id',
                'customers.user_name',
                'customers.name',
                'customers.introduce_id',
                'customers.qualification_id',
                'customers.status_customer',
            )
            ->where('customers.user_name', '=', $request->username)
            ->first();
    }
}
