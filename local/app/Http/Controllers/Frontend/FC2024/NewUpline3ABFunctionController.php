<?php

namespace App\Http\Controllers\Frontend\FC2024;

use App\Http\Controllers\Controller;
use DB;

class NewUpline3ABFunctionController extends Controller
{
    public static $arr = array();

    public function allupline($user_name)
    {
        $data = self::all_upline($user_name, 5);
        dd($data);
    }

    public static function all_upline($user_name, $limt_lv)
    {
        $introduce = self::tree($user_name, $limt_lv)->flatten();
        $data = ['status' => 'ข้อมูลภายใต้สายงาน Upline', 'data' => $introduce];
        return $data;
    }

    public static function tree($user_name, $limt_lv)
    {
        $all_upline = self::user_upline($user_name);
        self::formatTree($all_upline, $limt_lv, 0);
        return $all_upline;
    }

    public static function user_upline($user_name)
    {
        return DB::table('customers')
            ->select(
                'customers.id',
                'customers.user_name',
                'customers.name',
                'customers.introduce_id',
                'customers.upline_id',
                'customers.type_upline',
                'customers.qualification_id',
                'dataset_qualification.business_qualifications',
                'customers.created_at'
            )
            ->leftJoin('dataset_qualification', 'dataset_qualification.id', '=', 'customers.qualification_id')
            ->where('customers.upline_id', '=', $user_name)
            ->get();
    }

    public static function formatTree($uplines, $limt_lv, $level)
    {
        $level += 1;

        foreach ($uplines as $upline) {
            $upline->lv = $level;

            if ($level <= $limt_lv || $limt_lv == 0) {
                $upline->children = self::user_upline($upline->user_name);
                if ($upline->children->isNotEmpty()) {
                    self::formatTree($upline->children, $limt_lv, $level);
                }
            }

            self::$arr[$upline->user_name] = [
                'user_name' => $upline->user_name,
                'name' => $upline->name,
                'introduce_id' => $upline->introduce_id,
                'lv' => $upline->lv,
                'qualification_id' => $upline->qualification_id,
                'business_qualifications' => $upline->business_qualifications,
                'created_at' => $upline->created_at
            ];
        }
    }
}
