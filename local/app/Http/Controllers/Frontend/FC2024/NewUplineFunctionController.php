<?php

namespace App\Http\Controllers\Frontend\FC2024;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Return_;

class NewUplineFunctionController extends Controller
{

    public static function check_all_upline($username_check)
    {

        $upline_arr = []; // Initialize the array to store upline information

        $customers = DB::table('customers')
            ->where('user_name', $username_check)
            ->orderByDesc('id')
            ->limit('1')
            ->get();

        if (count($customers) <= 0) {
            return response()->json([
                'status' => 'success',
                'code' => 201,
                'data' => null,
                'message' => 'ไม่พบรหัส:' . $username_check,
            ], 200);
        }
        foreach ($customers as $introduce) {
            $i = 0;
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
                            'customers.upline_id',
                            'customers.type_upline',
                        )
                        ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        ->where('customers.user_name', $user_name)
                        ->first();


                    // If no upline is found, exit the loop

                    if (!$upline) {

                        if ($i == 1) {
                            if (empty($introduce->name) || $introduce->name == '' || $introduce->name == ' ') {
                                return response()->json([
                                    'status' => 'success',
                                    'code' => 201,
                                    'data' => null,
                                    'message' => 'รหัสนี้ไม่มีผู้แนะนำ',
                                ], 200);
                            }
                        }
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
                        'type_upline' => $upline->type_upline,

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
                            'customers.upline_id',
                            'customers.type_upline',

                        )
                        ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        ->where('customers.user_name', $run_username)
                        ->first();


                    return response()->json([
                        'status' => 'success',
                        'code' => 200,
                        'รหัสที่เช็ค' =>   $upline_check,
                        'ข้อมูลผู้แนะนำทั้งสายงาน' =>   $upline_arr,
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

        $customers  = DB::table('customers')
            // ->where('status_check_runupline', 'pending')
            // ->whereNull('name')
            // ->orwhere('name', '')
            ->orderByDesc('id')
            // ->limit(100000)
            ->count();

        dd($customers);

        // ->count();

        $k = 0;
        $delete = 0;

        foreach ($customers as $introduce) {
            $i = 0;
            $i++;
            $k++;
            if ($i == 1) {
                $run_username = $introduce->user_name;
            }
            if ($introduce->introduce_id !== 'AA') {
                $user_name = $introduce->introduce_id;
                $status = 'fail';
                $previous_user_name = null;

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
                            'customers.type_upline',
                        )
                        ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        ->where('customers.user_name', $user_name)
                        ->first();

                    // If no upline is found, exit the loop
                    if (!$upline) {
                        //ไม่มีผู้แนะนำ
                        if ($i == 1) {
                            if (empty($introduce->name) || $introduce->name == '' || $introduce->name == ' ') {
                                $delete++;
                                DB::table('customers')->where('user_name', $introduce->user_name)->delete();
                            }
                        }

                        break;
                    }

                    // Check if the upline has a valid name
                    if (empty($upline->name) || $upline->name == '' || $upline->name == ' ') {
                        $delete++;
                        // Save the introduce_id for the next iteration
                        $next_user_name = $upline->introduce_id;
                        // $next_upline_id = $upline->upline_id;
                        // $next_type_upline = $upline->type_upline;

                        // Update the introduce_id of the previous customer to the user_name of the next valid upline
                        if ($previous_user_name) {
                            DB::table('customers')
                                ->where('user_name', $previous_user_name)
                                ->update(['introduce_id' => $next_user_name]);
                        }

                        // Remove the upline with an empty name
                        DB::table('customers')->where('user_name', $user_name)->delete();

                        // Set the user_name to the next introduce_id for the next loop iteration
                        $user_name = $next_user_name;
                        continue;
                    }

                    $previous_user_name = $user_name; // Update previous_user_name before the next iteration
                    $user_name = $upline->introduce_id;
                    $status = 'success';
                }
            }

            //รันเสร็จ
            DB::table('customers')
                ->where('user_name', $run_username)
                ->update(['status_check_runupline' => 'success']);
        }

        $pending = DB::table('customers')
            ->where('status_check_runupline', 'pending')
            ->count();
        $ms = 'รันไปทั้งหมด:' . $k . 'ลบรหัสไป:' . $delete . 'รหัสรอดำเนินการ:' . $pending . 'success';
        Log::channel('run_2024')->info($ms);
        return $ms;
    }



    public static function set_pv_upgrade()
    {
        // $update =  DB::table('customers')
        //     ->update(['status_check_runupline' => 'pending']);

        $customers  = DB::table('customers')
            ->where('status_check_runupline', 'pending')
            ->where('qualification_id', ['XVVIP', 'SVVIP', 'MG', 'MR', 'ME', 'MD'])
            // ->orderByDesc('id')
            // ->limit(1)
            ->get();
        // dd($customers);
        $k = 0;

        foreach ($customers as $value) {

            $k++;

            if ($value->qualification_id == 'XVVIP') {
                $pv = 2400;
            } elseif ($value->qualification_id == 'SVVIP') {
                $pv = 3600;
            } elseif ($value->qualification_id == 'MG') {
                $pv = 6000;
            } elseif ($value->qualification_id == 'MR') {
                $pv = 6000;
            } elseif ($value->qualification_id == 'ME') {
                $pv = 6000;
            } elseif ($value->qualification_id == 'MD') {
                $pv = 6000;
            } else {
                $pv = $value->pv_upgrad;
            }


            //รันเสร็จ
            DB::table('customers')
                ->where('user_name', $value->user_name)
                ->update(['pv_upgrad' => $pv, 'status_check_runupline' => 'success']);
        }

        $pending = DB::table('customers')
            ->where('status_check_runupline', 'pending')
            ->count();
        $ms = 'รันไปทั้งหมด:' . $k . 'ปรับ Pv:' . $k . 'รหัสรอดำเนินการ:' . $pending . 'success';
        Log::channel('run_2024')->info($ms);
        return $ms;
    }
}
