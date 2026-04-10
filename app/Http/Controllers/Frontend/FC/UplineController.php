<?php

namespace App\Http\Controllers\Frontend\FC;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Log;

class UplineController extends Controller
{

    public static function uplineAB($introduce_id)
    {

        // $update =  DB::table('customers')
        //     ->where('user_name', '!=', '6135984')
        //     ->update(['type_upline' => null, 'upline_id' => null, 'status_check_runupline' => 'pending']);
        // dd($update);

        $members = DB::table('customers')
            ->select('id', 'user_name', 'introduce_id')
            ->where('user_name', '=', $introduce_id)
            ->orderBy('id')
            ->get();

        // dd($members);

        $successUpdates = [];
        $failUpdates = [];
        $k = 0;
        $f = 0;

        foreach ($members as $member) {
            $introduce_id = $member->user_name;

            $data = UplineController::check_type_register($introduce_id, 1);


            $i = 1;

            while ($data['status'] == 'fail' && $data['code'] == 'run') {

                $i++;
                $data = UplineController::check_type_register($data['arr_user_name'], $i);
            }

            if ($data['status'] == 'fail25') {
                return [
                    'status' => 'fail',
                    'ms' => 'เกิดข้อผิดพลาด ในการค้นหา  Unilevel',
                ];
            }

            if ($data['status'] == 'fail' && $data['code'] == 'stop') {
                $f++;
                $failUpdates[] = $member->user_name;
                continue;
            }



            if ($data['status'] == 'success') {
                $k++;
                // DB::table('customers')
                //     ->where('user_name', $member->user_name)
                //     ->update([
                //         'upline_id' => $data['upline_id'],
                //         'type_upline' => $data['type'],
                //         'status_check_runupline' => 'success'
                //     ]);

                return
                    [
                        'status' => 'success',
                        'upline_id' => $data['upline_id'],
                        'type' => $data['type'],
                        'status_check_runupline' => 'success'
                    ];
            } else {
                $f++;
                $failUpdates[] = $member->user_name;
            }
        }

        return [
            'status' => 'fail',
            'ms' => 'เกิดข้อผิดพลาด ในการค้นหา  Unilevel',
        ];
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

            if ($value->type_upline == 'B' && $value->count_upline = 2) {
                return self::check_recursive_auto_plac($user_name, $lv);
            }
        }

        return ['status' => 'fail25', 'ms' => 'CODE:25 มีหรัสที่เกิด upline_id หลายรายการ'];
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
        $maxCheck = 2 ** $lv;

        $data_check = DB::table('customers')
            ->select('user_name', 'upline_id', 'type_upline')
            ->whereIn('upline_id', (array) $user_name)
            ->orderBy('type_upline', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        $checked = 0;
        $user_full = [];

        foreach ($data_check as $value) {
            $checked++;
            $check = UplineController::check_auto_plack($value->user_name);

            if ($check['status'] == 'success') {
                return $check;
            } else {
                $user_full[] = $value->user_name;
            }

            if ($checked == $maxCheck) {
                return ['status' => 'fail', 'arr_user_name' => $user_full, 'code' => 'run'];
            }
        }

        return ['status' => 'fail', 'arr_user_name' => $user_full, 'code' => 'stop'];
    }


    public static function check_auto_plack($user_name)
    {
        $data_sponser = DB::table('customers')
            ->select('user_name', 'upline_id', 'type_upline')
            ->where('upline_id', $user_name)
            ->orderBy('type_upline', 'ASC')
            ->limit(2) // ดักไว้ที่ 2 ไม่ต้องโหลดเยอะ
            ->get();

        if ($data_sponser->isEmpty()) {
            return [
                'status' => 'success',
                'upline_id' => $user_name,
                'type' => 'A',
                'rs' => []
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
