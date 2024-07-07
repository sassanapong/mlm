<?php

namespace App\Http\Controllers\Frontend\FC2024;

use App\Http\Controllers\Controller;
use DB;
use Carbon\Carbon;
use Phattarachai\LineNotify\Facade\Line;
use App\eWallet;

class RunPerDay_pv_ab03Controller extends Controller
{

    public static function Runbonus9Perday()
    {

        try {
            DB::beginTransaction();

            $bonus_9_01 = RunPerDay_pv_ab03Controller::bonus_9_01();
            if ($bonus_9_01['status'] !== 'success') {
                throw new \Exception($bonus_9_01['message']);
            }

            dd($bonus_9_01);

            // $bonus_4_02 = RunPerDay_pv_ab02Controller::bonus_4_02();
            // if ($bonus_4_01['status'] !== 'success') {
            //     throw new \Exception($bonus_4_01['message']);
            // }


            // //คำนวนชุดนี้สุดท้าย ต้องมี code รัน
            // if ($bonus_4_01['status'] == 'success' and $bonus_4_02['status'] == 'success') {
            //     $bonus_4_03 = RunPerDay_pv_ab02Controller::bonus_4_03();
            //     DB::commit();

            //     $ms = "โบนัสบริหาร team 2 สายงาน(4)  \n" .
            //         $bonus_4_01['message'] . "\n" .
            //         $bonus_4_02['message'] . "\n" .
            //         $bonus_4_03['message'] . "\n";

            //     Line::send($ms);
            //     return $ms;
            // } else {
            //     DB::commit();

            //     $ms = "โบนัสบริหาร team 2 สายงาน(4 รอจ่ายเงิน)  \n" .
            //         $bonus_4_01['message'] . "\n" .
            //         $bonus_4_02['message'] . "\n";

            //     Line::send($ms);
            //     return $ms;
            // }
        } catch (\Exception $e) {
            DB::rollBack();
            Line::send($e->getMessage());
            return response()->json(['status' => 'fail', 'message' => $e->getMessage()], 500);
        }
    }

    public static function bonus_9_01()
    {

        $c = DB::table('report_pv_per_day_ab_balance')
            ->where('status_bonus9', '=', 'pending')
            ->get();
        $i = 0;
        try {
            DB::BeginTransaction();

            foreach ($c as $value) {
                $i++;
            }

            DB::commit();
            return ['status' => 'success', 'message' => 'จ่ายโบนัส สำเร็จ (' . $i . ') รายการ'];
        } catch (Exception $e) {
            DB::rollback();
            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }
}
