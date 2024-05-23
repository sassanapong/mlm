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


        $data = ApiFunction4Controller::all_upline($user->introduce_id, 0);

        return response()->json([
            'message' => 'success',
            'status' => 'success',
            'code' => 'S01',
            'data' => self::$arr,
        ], 200);
    }
    public static function all_upline($user_name, $limt_lv)
    {
        $h = $user_name;
        $introduce = self::tree($user_name, $limt_lv, $h)->flatten();
        // dd(self::$arr);
        $data = ['status' => 'all', 'data' => $introduce];

        return $data;
    }


    public static function tree($user_name, $limt_lv, $h)
    {
        $user = DB::table('customers')
            ->select(
                'customers.id',
                'customers.user_name',
                'customers.name',
                'customers.introduce_id',
                'customers.qualification_id',
                'customers.status_customer',

            )
            ->where('customers.user_name', '=', $user_name)
            ->first();


        $all_upline = self::user_upline($user_name);

        //self::formatTree($c);
        ApiFunction4Controller::formatTree($all_upline, $limt_lv, $h);
        return $all_upline;
    }

    public static function user_upline($user_name)
    {
        $introduce = DB::table('customers')
            ->select(
                'customers.id',
                'customers.user_name',
                'customers.name',
                'customers.introduce_id',
                'customers.qualification_id',
                'customers.status_customer',
                'customers.created_at'
            )
            ->leftjoin('dataset_qualification', 'dataset_qualification.id', '=', 'customers.qualification_id')
            ->where('customers.introduce_id', '=', $user_name);

        return $introduce->get();
    }

    public static function formatTree($uplines, $limt_lv, $h, $num = 0, $i = 0)
    {
        $num += 1;
        foreach ($uplines as $upline) {
            $i++;
            $upline->lv = $num;
            $upline->children = self::user_upline($upline->user_name);

            if ($upline->name != null || $upline->name != '' || $upline->status_customer !=  'cancel') {

                if ($upline->children->isNotEmpty()) {

                    self::$arr[$upline->user_name]['user_name'] = $upline->user_name;
                    self::$arr[$upline->user_name]['name'] = $upline->name;
                    self::$arr[$upline->user_name]['introduce_id'] = $upline->introduce_id;

                    self::$arr[$upline->user_name]['lv'] = $upline->lv;
                    self::$arr[$upline->user_name]['qualification_id'] = $upline->qualification_id;
                    self::$arr[$upline->user_name]['business_qualifications'] = $upline->qualification_id;
                    self::$arr[$upline->user_name]['created_at'] = $upline->created_at;

                    self::formatTree($upline->children, $limt_lv, $h, $num, $i);
                } else {
                    self::$arr[$upline->user_name]['user_name'] = $upline->user_name;
                    self::$arr[$upline->user_name]['name'] = $upline->name;
                    self::$arr[$upline->user_name]['introduce_id'] = $upline->introduce_id;

                    self::$arr[$upline->user_name]['lv'] = $upline->lv;
                    self::$arr[$upline->user_name]['qualification_id'] = $upline->qualification_id;
                    self::$arr[$upline->user_name]['business_qualifications'] = $upline->qualification_id;
                    self::$arr[$upline->user_name]['created_at'] = $upline->created_at;
                }
            } else {

                if ($upline->children->isNotEmpty()) {

                    self::formatTree($upline->children, $limt_lv, $h, $num - 1, $i);
                }
            }
        }
    }
}
