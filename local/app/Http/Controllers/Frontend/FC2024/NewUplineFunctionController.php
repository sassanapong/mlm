<?php

namespace App\Http\Controllers\Frontend\FC2024;

use App\Http\Controllers\Controller;
use DB;

class NewUplineFunctionController extends Controller
{

    public static function check_all_upline()
    {

        $upline_arr = []; // Initialize the array to store upline information


        $customers = DB::table('customers')
            ->where('user_name', '0118983')
            ->orderByDesc('id')
            ->limit('1')
            ->get();


        $i = 0;
        foreach ($customers as $introduce) {
            $i++;
            if ($i == 1) {
                $run_username = $introduce->user_name;
            }
            if ($introduce->introduce_id !== 'AA') {

                $user_name = $introduce->introduce_id;
                $max_levels = 9999999999; // Define the maximum levels to search for uplines

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
                            'customers.introduce_id',
                            'customers.upline_id'
                        )
                        ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        ->where('customers.user_name', $user_name)
                        ->first();


                    // If no upline is found, exit the loop
                    if (!$upline) {
                        $status = 'fail';
                        break;
                    }

                    // Check if the upline has a valid name
                    // if (empty($upline->name)) {
                    //     $status = 'fail';
                    //     $user_name = $upline->introduce_id;
                    //     continue;
                    // }


                    $full_name = "{$upline->name} {$upline->last_name}";

                    // Append upline details to the array
                    $upline_arr[] = [

                        'username' => $upline->user_name,
                        'name' => $full_name,
                        'position' => $upline->qualification_id,
                        'introduce_id' => $upline->introduce_id,
                        'upline_id' => $upline->upline_id,
                        'g' => $level,
                    ];

                    // Set the user_name for the next level lookup
                    $user_name = $upline->introduce_id;
                    $status = 'success';
                }


                if ($upline_arr) {
                    $upline_check = DB::table('customers')
                        ->select(
                            'customers.pv',
                            'customers.id',
                            'customers.name',
                            'customers.last_name',
                            'customers.user_name',
                            'customers.qualification_id',
                            'customers.expire_date',
                            'dataset_qualification.code',
                            'customers.introduce_id',
                            'customers.upline_id'
                        )
                        ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        ->where('customers.user_name', $user_name)
                        ->first();



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


    public static function delete_empty_upline()
    {
        $upline_arr = []; // Initialize the array to store upline information

        $customers = DB::table('customers')
            ->where('user_name', '0118983')
            ->where('status_check_runupline', 'pending')
            ->orderByDesc('id')
            ->limit(1)
            ->get();
        $i = 0;
        foreach ($customers as $introduce) {
            $i++;
            if ($i == 1) {
                $run_username = $introduce->user_name;
            }
            if ($introduce->introduce_id !== 'AA') {
                $user_name = $introduce->introduce_id;
                $status = 'fail';

                while (true) {
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
                            'customers.introduce_id',
                            'customers.upline_id',

                        )
                        ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        ->where('customers.user_name', '$run_username')
                        ->first();

                    // If no upline is found, exit the loop
                    if (!$upline) {
                        break;
                    }

                    // Check if the upline has a valid name
                    if (empty($upline->name)) {
                        // Save the introduce_id for the next iteration
                        $next_user_name = $upline->introduce_id;
                        $next_upline_id = $upline->upline_id;

                        // Update the introduce_id of the current customer to the user_name of the next valid upline
                        DB::table('customers')
                            ->where('user_name', $introduce->user_name)
                            ->update(['introduce_id' => $next_user_name, 'upline_id' => $next_upline_id]);

                        // Remove the upline with an empty name
                        DB::table('customers')->where('user_name', $user_name)->delete();

                        // Set the user_name to the next introduce_id for the next loop iteration
                        $user_name = $next_user_name;
                        continue;
                    }

                    $full_name = "{$upline->name} {$upline->last_name}";

                    // Append upline details to the array
                    $upline_arr[] = [
                        'username' => $upline->user_name,
                        'name' => $full_name,
                        'position' => $upline->qualification_id,
                        'introduce_id' => $upline->introduce_id,
                        'upline_id' => $upline->upline_id,

                    ];

                    // Set the user_name for the next level lookup
                    $user_name = $upline->introduce_id;
                    $status = 'success';
                }

                if ($upline_arr) {

                    DB::table('customers')
                        ->where('user_name', $run_username)
                        ->update(['status_check_runupline' => 'success']);

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
}
