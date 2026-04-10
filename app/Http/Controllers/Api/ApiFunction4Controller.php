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

        // Fetch introduce_id for the given user_name
        $introduce = DB::table('customers')
            ->select('introduce_id')
            ->where('user_name', $request->username)
            ->first();

        $upline_arr = []; // Initialize the array to store upline information

        if ($introduce && $introduce->introduce_id !== 'AA') {
            $user_name = $introduce->introduce_id;
            $max_levels = 10; // Define the maximum levels to search for uplines

            for ($level = 1; $level <= $max_levels; $level++) {
                $upline = DB::table('customers')
                    ->select(
                        'customers.pv',
                        'customers.id',
                        'customers.name',
                        'customers.last_name',
                        'customers.user_name',
                        'customers.qualification_id',
                        'customers.expire_date',
                        'dataset_qualification.code',
                        'customers.introduce_id'
                    )
                    ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                    ->where('customers.user_name', $user_name)
                    ->first();



                // if (empty($up->expire_date) || strtotime($up->expire_date) < strtotime(date('Ymd'))) {
                //   $status = 'fail';
                //   $user_name = $up->introduce_id;
                // } else


                // If no upline is found, exit the loop
                if (!$upline) {
                    $status = 'fail';
                    break;
                }

                // Check if the upline has a valid name
                if (empty($upline->name)) {
                    $status = 'fail';
                    $user_name = $upline->introduce_id;
                    continue;
                }

                // Determine the qualification code
                $qualification_code = $upline->code ?? 'MB';
                $full_name = "{$upline->name} {$upline->last_name}";

                // Append upline details to the array
                $upline_arr[] = [
                    'username' => $upline->user_name,
                    'name' => $full_name,
                    'position' => $upline->qualification_id,
                    'g' => $level,
                ];

                // Set the user_name for the next level lookup
                $user_name = $upline->introduce_id;
                $status = 'success';
            }


            if ($upline_arr) {
                return response()->json([
                    'status' => 'success',
                    'code' => 200,
                    'data' => $upline_arr,
                    'message' => 'successfully',
                ], 200);
            } else {
                return response()->json([
                    'status' => 'success',
                    'code' => 201,
                    'data' => null,
                    'message' => 'รหัสนี้ไม่มีผู้แนะนำ',
                ], 200);
            }
        }
    }
}
