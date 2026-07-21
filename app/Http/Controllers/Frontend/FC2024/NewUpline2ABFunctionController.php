<?php

namespace App\Http\Controllers\Frontend\FC2024;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Log;

class NewUpline2ABFunctionController extends Controller
{

    public static function uplineAB()
    {
        dd('closs');

        // $update =  DB::table('customers')
        //     ->where('user_name', '!=', '6135984')
        //     ->update(['type_upline' => null, 'upline_id' => null, 'status_check_runupline' => 'pending']);
        // dd($update); 
 
        // $update =  DB::table('customers')
        //     ->where('id', '>', '775816')
        //     ->update(['type_upline' => null, 'upline_id' => null, 'status_check_runupline' => 'pending']);
        // dd($update);
  
        $members = DB::table('customers')
            ->select('id', 'user_name', 'introduce_id')
            ->where('id', '>', '775816')
            ->where('status_check_runupline', 'pending')
            ->where('upline_id', '=', null)
            // ->where('introduce_id', '=', '1165816')
            ->whereNotNull('introduce_id')
            ->orderBy('id')
            // ->limit(50) 
            ->get();


        // dd($members);

        $successUpdates = [];
        $failUpdates = [];
        $k = 0;
        $f = 0;

        foreach ($members as $member) {
            $introduce_id = $member->introduce_id;

            $data = NewUpline2ABFunctionController::check_type_register($introduce_id, 1);


            $i = 1;

            while ($data['status'] == 'fail' && $data['code'] == 'run') {

                $i++;
                $data = NewUpline2ABFunctionController::check_type_register($data['arr_user_name'], $i);
            }

            if ($data['status'] == 'fail25') {
                return $data;
            }

            if ($data['status'] == 'fail' && $data['code'] == 'stop') {
                $f++;
                $failUpdates[] = $member->user_name;
                continue;
            }



            if ($data['status'] == 'success') {

                $k++;
                DB::table('customers')
                    ->where('id', $member->id)
                    ->update([
                        'upline_id' => $data['upline_id'],
                        'type_upline' => $data['type'],
                        'status_check_runupline' => 'success'
                    ]);
            } else {
                $f++;
                $failUpdates[] = $member->user_name;
            }
        }

        // ทำ Bulk Update (Fail)
        // if (!empty($failUpdates)) {
        //     DB::table('customers')
        //         ->whereIn('user_name', $failUpdates)
        //         ->update(['status_check_runupline' => 'fail']);
        // }

        $pending = DB::table('customers')
            ->where('status_check_runupline', 'pending')
            ->where('upline_id', '=', null)
            ->whereNotNull('introduce_id')
            ->count();

        dd('fail: ' . $f, 'success: ' . $k, 'รหัสรอดำเนินการ: ' . $pending);
    }




    public static function check_type_register($user_name, $lv)
    {
        if ($lv == 1) {
            return self::check_lv1($user_name);
        }

        return self::check_multi_level($user_name, $lv);
    }

    private static function check_lv1($user_name)
    {
        $data_sponsor = DB::table('customers')
            ->select('user_name', 'upline_id', 'type_upline')
            ->where('upline_id', $user_name)
            ->orderBy('type_upline', 'ASC')
            ->get();

        if ($data_sponsor->isEmpty()) {
            return ['status' => 'success', 'upline_id' => $user_name, 'type' => 'A', 'rs' => $data_sponsor];
        }

        $availableType = ['A', 'B'];

        foreach ($data_sponsor as $value) {
            if (($key = array_search($value->type_upline, $availableType)) !== false) {
                unset($availableType[$key]);
            }
        }

        if (count($data_sponsor) < 2) {
            $type = reset($availableType);
            return ['status' => 'success', 'upline_id' => $user_name, 'type' => $type, 'rs' => $data_sponsor];
        }

        $arr_user_name = $data_sponsor->pluck('user_name')->toArray();
        return ['status' => 'fail', 'arr_user_name' => $arr_user_name, 'code' => 'run'];
    }

    private static function check_multi_level($user_name, $lv)
    {

        $upline_child = DB::table('customers')
            ->selectRaw('count(upline_id) as count_upline, upline_id')

            ->whereIn('upline_id', (array) $user_name)
            ->orderBy('type_upline', 'ASC')
            ->groupBy('upline_id');

        $data_sponsor = DB::table('customers')
            ->selectRaw('COALESCE(upline_child.count_upline, 0) as count_upline, customers.user_name, customers.type_upline')
            ->leftJoinSub($upline_child, 'upline_child', function ($join) {
                $join->on('customers.user_name', '=', 'upline_child.upline_id');
            })
            ->whereIn('customers.user_name', (array) $user_name)
            ->orderBy('count_upline')
            ->orderBy('type_upline')
            ->get();



        if ($data_sponsor->isEmpty()) {
            return ['status' => 'success', 'upline_id' => $user_name, 'type' => 'A', 'rs' => $data_sponsor];
        }

        foreach ($data_sponsor as $value) {
            if ($value->count_upline < 2) {

                return self::assign_type_to_upline($value->user_name);
            }
        }

        // dd($data_sponsor, $user_name, $lv, 'มีรหัสที่เกิด upline_id หลายรายการ');

        return self::check_recursive_auto_plac($user_name, $lv);
    }

    private static function assign_type_to_upline($upline_user_name)
    {
        $data_check = DB::table('customers')
            ->select('user_name', 'upline_id', 'type_upline')
            ->where('upline_id', $upline_user_name)
            ->orderBy('type_upline', 'ASC')
            ->get();

        // dd($data_check, $upline_user_name, 'asas');

        $type = ['A', 'B'];

        foreach ($data_check as $value) {
            if (($key = array_search($value->type_upline, $type)) !== false) {
                unset($type[$key]);
            }
        }

        $typeToUse = reset($type);

        return ['status' => 'success', 'upline_id' => $upline_user_name, 'type' => $typeToUse, 'rs' => $data_check];
    }

    private static function check_recursive_auto_plac($user_name, $lv)
    {
        $data_check = DB::table('customers')
            ->select('user_name', 'upline_id', 'type_upline')
            ->whereIn('upline_id', (array) $user_name)
            ->orderBy('type_upline', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        $user_full = [];

        foreach ($data_check as $value) {
            $check = NewUpline2ABFunctionController::check_auto_plack($value->user_name);

            if ($check['status'] == 'success') {
                return $check;
            } else {
                $user_full[] = $value->user_name;
            }
        }

        if (!empty($user_full)) {
            return ['status' => 'fail', 'arr_user_name' => $user_full, 'code' => 'run'];
        }

        return ['status' => 'fail', 'arr_user_name' => $user_full, 'code' => 'stop'];
    }


    public static function check_auto_plack($user_name)
    {
        $data_sponser = DB::table('customers')
            ->select('user_name', 'upline_id', 'type_upline')
            ->where('upline_id', $user_name)
            ->orderBy('type_upline', 'ASC')
            ->get();

        if ($data_sponser->isEmpty()) {
            return [
                'status' => 'success',
                'upline_id' => $user_name,
                'type' => 'A',
                'rs' => []
            ];
        }

        if ($data_sponser->count() >= 2) {
            return [
                'status' => 'fail',
                'code' => 'run'
            ];
        }

        $types_needed = ['A', 'B'];

        // เอาค่า type_upline ออกมาแล้ว diff กับ A, B
        $existing_types = $data_sponser->pluck('type_upline')->all();
        $available_types = array_diff($types_needed, $existing_types);

        if (count($available_types) > 0) {
            $next_type = reset($available_types); // เอาค่าแรกของ type ที่ยังไม่มี
            return [
                'status' => 'success',
                'upline_id' => $user_name,
                'type' => $next_type,
                'rs' => $data_sponser
            ];
        }

        return [
            'status' => 'fail',
            'code' => 'run'
        ];
    }
}
