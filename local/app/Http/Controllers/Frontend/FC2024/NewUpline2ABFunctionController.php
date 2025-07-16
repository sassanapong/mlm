<?php

namespace App\Http\Controllers\Frontend\FC2024;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Log;

class NewUpline2ABFunctionController extends Controller
{

    public static function uplineAB()
    {

        // $update =  DB::table('customers')
        //     ->where('user_name', '!=', '6135984')
        //     ->update(['type_upline_uni' => null, 'uni_id' => null, 'status_check_runupline' => 'pending']);
        // dd($update);

        $members = DB::table('customers')
            ->select('id', 'user_name', 'introduce_id')
            ->where('status_check_runupline', 'pending')
            ->where('introduce_id', '=', '1165816')
            ->whereNotNull('introduce_id')
            ->orderBy('id')
            // ->limit(1)
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
                dd($data);
                $i++;
                $data = NewUpline2ABFunctionController::check_type_register($data['arr_user_name'], $i);
            }

            if ($data['status'] == 'fail25') {
                dd($data);
            }

            if ($data['status'] == 'fail' && $data['code'] == 'stop') {
                $f++;
                $failUpdates[] = $member->user_name;
                continue;
            }



            if ($data['status'] == 'success') {
                dd($data);
                $k++;
                $successUpdates[] = [
                    'user_name' => $member->user_name,
                    'uni_id' => $data['uni_id'],
                    'type_upline_uni' => $data['type']
                ];
            } else {
                $f++;
                $failUpdates[] = $member->user_name;
            }
        }

        // ทำ Bulk Update (Success)
        foreach ($successUpdates as $row) {
            DB::table('customers')
                ->where('user_name', $row['user_name'])
                ->update([
                    'uni_id' => $row['uni_id'],
                    'type_upline_uni' => $row['type_upline_uni'],
                    'status_check_runupline' => 'success'
                ]);
        }

        // ทำ Bulk Update (Fail)
        if (!empty($failUpdates)) {
            DB::table('customers')
                ->whereIn('user_name', $failUpdates)
                ->update(['status_check_runupline' => 'fail']);
        }

        $pending = DB::table('customers')
            ->where('status_check_runupline', 'pending')
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
            ->select('user_name', 'uni_id', 'type_upline_uni')
            ->where('uni_id', $user_name)
            ->orderBy('type_upline_uni', 'ASC')
            ->get();

        if ($data_sponsor->isEmpty()) {
            return ['status' => 'success', 'uni_id' => $user_name, 'type' => 'A', 'rs' => $data_sponsor];
        }

        $availableType = ['A', 'B'];

        foreach ($data_sponsor as $value) {
            if (($key = array_search($value->type_upline_uni, $availableType)) !== false) {
                unset($availableType[$key]);
            }
        }

        if (count($data_sponsor) < 2) {
            $type = reset($availableType);
            return ['status' => 'success', 'uni_id' => $user_name, 'type' => $type, 'rs' => $data_sponsor];
        }

        $arr_user_name = $data_sponsor->pluck('user_name')->toArray();
        return ['status' => 'fail', 'arr_user_name' => $arr_user_name, 'code' => 'run'];
    }

    private static function check_multi_level($user_name, $lv)
    {

        $upline_child = DB::table('customers')
            ->selectRaw('count(uni_id) as count_upline, uni_id')

            ->whereIn('uni_id', (array) $user_name)
            ->orderBy('type_upline_uni', 'ASC')
            ->groupBy('uni_id');

        $data_sponsor = DB::table('customers')
            ->selectRaw('COALESCE(upline_child.count_upline, 0) as count_upline, customers.user_name, customers.type_upline_uni')
            ->leftJoinSub($upline_child, 'upline_child', function ($join) {
                $join->on('customers.user_name', '=', 'upline_child.uni_id');
            })
            ->whereIn('customers.user_name', (array) $user_name)
            ->orderBy('count_upline')
            ->orderBy('type_upline_uni')
            ->get();

        dd($data_sponsor);

        if ($data_sponsor->isEmpty()) {
            return ['status' => 'success', 'uni_id' => $user_name, 'type' => 'A', 'rs' => $data_sponsor];
        }

        foreach ($data_sponsor as $value) {
            if ($value->count_upline < 2) {

                return self::assign_type_to_upline($value->user_name);
            }

            if ($value->type_upline_uni == 'B' && $value->count_upline = 2) {
                return self::check_recursive_auto_plac($user_name, $lv);
            }
        }

        return ['status' => 'fail25', 'ms' => 'CODE:25 มีหรัสที่เกิด uni_id หลายรายการ'];
    }

    private static function assign_type_to_upline($upline_user_name)
    {
        $data_check = DB::table('customers')
            ->select('user_name', 'uni_id', 'type_upline_uni')
            ->where('uni_id', $upline_user_name)
            ->orderBy('type_upline_uni', 'ASC')
            ->get();

        // dd($data_check, $upline_user_name, 'asas');

        $type = ['A', 'B'];

        foreach ($data_check as $value) {
            if (($key = array_search($value->type_upline_uni, $type)) !== false) {
                unset($type[$key]);
            }
        }

        $typeToUse = reset($type);

        return ['status' => 'success', 'uni_id' => $upline_user_name, 'type' => $typeToUse, 'rs' => $data_check];
    }

    private static function check_recursive_auto_plac($user_name, $lv)
    {
        $maxCheck = 2 ** $lv;

        $data_check = DB::table('customers')
            ->select('user_name', 'uni_id', 'type_upline_uni')
            ->whereIn('uni_id', (array) $user_name)
            ->orderBy('type_upline_uni', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        $checked = 0;
        $user_full = [];

        foreach ($data_check as $value) {
            $checked++;
            $check = NewUpline2ABFunctionController::check_auto_plack($value->user_name);

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
            ->select('user_name', 'uni_id', 'type_upline_uni')
            ->where('uni_id', $user_name)
            ->orderBy('type_upline_uni', 'ASC')
            ->limit(2) // ดักไว้ที่ 2 ไม่ต้องโหลดเยอะ
            ->get();

        if ($data_sponser->isEmpty()) {
            return [
                'status' => 'success',
                'uni_id' => $user_name,
                'type' => 'A',
                'rs' => []
            ];
        }

        $types_needed = ['A', 'B'];

        // เอาค่า type_upline_uni ออกมาแล้ว diff กับ A, B
        $existing_types = $data_sponser->pluck('type_upline_uni')->all();
        $available_types = array_diff($types_needed, $existing_types);

        if (count($available_types) > 0) {
            $next_type = reset($available_types); // เอาค่าแรกของ type ที่ยังไม่มี
            return [
                'status' => 'success',
                'uni_id' => $user_name,
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
