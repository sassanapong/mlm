<?php

namespace App\Http\Controllers\Frontend\FC2024;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\eWallet;

class RunPerDayController extends Controller
{
    public $arr = array();


    public static function RunbonusPerday()
    {
        // dd('RunbonusPerday');
        $current_time = date('H:i'); // รับค่าเวลาปัจจุบันในรูปแบบ HH:MM

        $date = now();


        $date = date("Y-m-d", strtotime("-1 day", strtotime($date)));
        //$date =  date('Y-02-13 00:00:00');


        if ($current_time >= '00:00' && $current_time <= '06:00') {
            // เงื่อนไขที่เวลาอยู่ระหว่าง 00:00 ถึง 06:00 

            $log = DB::table('log_run_bonus_2024')
                ->where('date_run', $date)
                ->orderby('id', 'desc')
                ->first();

            if (empty($log)) {
                $step1 = \App\Http\Controllers\Frontend\FC2024\RunPerDay_pv_ab01Controller::delete_pv();

                if ($step1 == 0) {
                    DB::table('log_run_bonus_2024')
                        ->Insert([
                            'date_run' => $date,
                            'row_pending' =>  0,
                            'status' => 'success',
                            'note' => 'ลบรายการ',
                            'type' => 'step1'
                        ]);
                    DB::commit();
                    return 'step 1  success ลบรายการ';
                } else {
                    DB::commit();
                    return 'step 1  fail ลบรายการ';
                }
            }

            if ($log->type == 'step9' and $log->status == 'success') {
                DB::commit();
                return 'สำเร็จทุกรายการเเล้ว';
            }


            if ($log->status == 'success') {
                if ($log->type == 'step1') {
                    $step2 = \App\Http\Controllers\Frontend\FC2024\RunPerDay_pv_ab01Controller::bonus_allsale_permounth_03();
                    if ($step2['status'] == 'success') {
                        if ($step2['pending'] == 0) {

                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step2['pending'],
                                    'status' => 'success',
                                    'note' => 'step2',
                                    'type' => 'step2'
                                ]);
                            DB::commit();
                            return 'step 2  success  ' . $step2['pending'];
                        } else {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step2['pending'],
                                    'status' => 'pending',
                                    'note' => 'step2',
                                    'type' => 'step2'
                                ]);
                            DB::commit();
                            return 'step 2  pending  ' . $step2['pending'];
                        }
                    } else {
                        // DB::commit();
                        return 'step 2 fail';
                    }
                } elseif ($log->type == 'step2') {

                    $step3 = \App\Http\Controllers\Frontend\FC2024\RunPerDay_pv_ab01Controller::bonus_allsale_permounth_04();

                    if ($step3['status'] == 'success') {
                        if ($step3['pending'] == 0) {

                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step3['pending'],
                                    'status' => 'success',
                                    'note' => 'step3',
                                    'type' => 'step3'
                                ]);
                            DB::commit();
                            return 'step 3  success  ' . $step3['pending'];
                        } else {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step3['pending'],
                                    'status' => 'pending',
                                    'note' => 'step3',
                                    'type' => 'step3'
                                ]);
                            DB::commit();
                            return 'step 3 pending  ' . $step3['pending'];
                        }
                    } else {
                        // DB::commit();
                        return 'step 3 fail';
                    }
                } elseif ($log->type == 'step3') {

                    $step4 = \App\Http\Controllers\Frontend\FC2024\RunPerDay_pv_ab02Controller::Runbonus4Perday();

                    if ($step4['status'] == 'success') {
                        if ($step4['pending'] == 0) {

                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step4['pending'],
                                    'status' => 'success',
                                    'note' => 'step4',
                                    'type' => 'step4'
                                ]);
                            DB::commit();
                            return 'step 4 success  ' . $step4['pending'];
                        } else {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step4['pending'],
                                    'status' => 'pending',
                                    'note' => 'step4',
                                    'type' => 'step4'
                                ]);
                            DB::commit();
                            return 'step 4 pending  ' . $step4['pending'];
                        }
                    } else {
                        // DB::commit();
                        return 'step 4 fail';
                    }
                } elseif ($log->type == 'step4') {

                    $step5 = \App\Http\Controllers\Frontend\FC2024\RunPerDay_pv_ab03Controller::Runbonus9Perday();

                    if ($step5['status'] == 'success') {

                        if ($step5['pending'] == 0) {
                            $data =  DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step5['pending'],
                                    'status' => 'success',
                                    'note' => 'step5',
                                    'type' => 'step5'
                                ]);
                            DB::commit();
                            return 'step 5  success ' . $step5['pending'];
                        } else {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step5['pending'],
                                    'status' => 'pending',
                                    'note' => 'step5',
                                    'type' => 'step5'
                                ]);
                            DB::commit();
                            return 'step 5  pending  ' . $step5['pending'];
                        }
                    } else {
                        // DB::commit();
                        return 'step 5 fail';
                    }
                } elseif ($log->type == 'step5') {

                    DB::table('log_run_bonus_2024')
                        ->Insert([
                            'date_run' => $date,
                            'row_pending' => 0,
                            'status' => 'success',
                            'note' => 'step6',
                            'type' => 'step6'
                        ]);
                    DB::commit();
                    return 'step 6  success ' . 0;


                    $step6 = \App\Http\Controllers\Frontend\FC2024\RunPerDay_pv_ab04Controller::bonus_7_all('ss');
                    if ($step6['status'] == 'success') {
                        if ($step6['pending'] == 0) {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step6['pending'],
                                    'status' => 'success',
                                    'note' => 'step6',
                                    'type' => 'step6'
                                ]);
                            DB::commit();
                            return 'step 6  success ' . $step6['pending'];
                        } else {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step6['pending'],
                                    'status' => 'pending',
                                    'note' => 'step6',
                                    'type' => 'step6'
                                ]);
                            DB::commit();
                            return 'step 6 pending  ' . $step6['pending'];
                        }
                    } else {
                        // DB::commit();
                        return 'step 6 fail';
                    }
                } elseif ($log->type == 'step6') {
                    $step7 = \App\Http\Controllers\Frontend\FC2024\RunPerDay_pv_ab02Controller::bonus_4_03();
                    if ($step7['status'] == 'success') {
                        if ($step7['pending'] == 0) {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step7['pending'],
                                    'status' => 'success',
                                    'note' => 'จ่ายเงินโบนัสข้อ 8',
                                    'type' => 'step7'
                                ]);
                            DB::commit();
                            return 'step 7  success ' . $step7['pending'];
                        } else {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step7['pending'],
                                    'status' => 'pending',
                                    'note' => 'จ่ายเงินโบนัสข้อ 8',
                                    'type' => 'step7'
                                ]);
                            DB::commit();
                            return 'step 7 pending  ' . $step7['pending'];
                        }
                    } else {
                        // DB::commit();
                        return 'step 7 fail';
                    }
                } elseif ($log->type == 'step7') {
                    $step8 = \App\Http\Controllers\Frontend\FC2024\RunPerDay_pv_ab03Controller::bonus_9_ewallet();
                    if ($step8['status'] == 'success') {
                        if ($step8['pending'] == 0) {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step8['pending'],
                                    'status' => 'success',
                                    'note' => 'จ่ายเงินโบนัสข้อ 9',
                                    'type' => 'step8'
                                ]);
                            DB::commit();
                            return 'step 8 success ' . $step8['pending'];
                        } else {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step8['pending'],
                                    'status' => 'pending',
                                    'note' => 'จ่ายเงินโบนัสข้อ 9',
                                    'type' => 'step8'
                                ]);
                            DB::commit();
                            return 'step 8 pending  ' . $step8['pending'];
                        }
                    } else {
                        // DB::commit();
                        return 'step 8 fail';
                    }
                } elseif ($log->type == 'step8') {

                    DB::table('log_run_bonus_2024')
                        ->Insert([
                            'date_run' => $date,
                            'row_pending' => 0,
                            'status' => 'success',
                            'note' => 'จ่ายเงินโบนัสข้อ 7 (ไม่จ่ายเเล้ว)',
                            'type' => 'step9'
                        ]);
                    DB::commit();
                    return 'step 9 success ' . 0;

                    $step9 = \App\Http\Controllers\Frontend\FC2024\RunPerDay_pv_ab04Controller::bonus_7_ewallet();
                    if ($step9['status'] == 'success') {
                        if ($step9['pending'] == 0) {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step9['pending'],
                                    'status' => 'success',
                                    'note' => 'จ่ายเงินโบนัสข้อ 7',
                                    'type' => 'step9'
                                ]);
                            DB::commit();
                            return 'step 9 success ' . $step9['pending'];
                        } else {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step9['pending'],
                                    'status' => 'pending',
                                    'note' => 'จ่ายเงินโบนัสข้อ 7',
                                    'type' => 'step9'
                                ]);
                            DB::commit();
                            return 'step 9 pending ' . $step9['pending'];
                        }
                    } else {
                        // DB::commit();
                        return 'step 9 fail';
                    }
                } else {
                    DB::commit();
                    return  'success full';
                }
                // DB::commit();
                return 'fail';
            } else { //penging
                if ($log->type == 'step2') {
                    $step2 = \App\Http\Controllers\Frontend\FC2024\RunPerDay_pv_ab01Controller::bonus_allsale_permounth_03();
                    if ($step2['status'] == 'success') {
                        if ($step2['pending'] == 0) {

                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step2['pending'],
                                    'status' => 'success',
                                    'note' => 'step2',
                                    'type' => 'step2'
                                ]);
                            DB::commit();
                            return 'step 2  success  ' . $step2['pending'];
                        } else {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step2['pending'],
                                    'status' => 'pending',
                                    'note' => 'step2',
                                    'type' => 'step2'
                                ]);
                            DB::commit();
                            return 'step 2 pending' . $step2['pending'];
                        }
                    } else {
                        // DB::commit();
                        return 'step 2 fail';
                    }
                } elseif ($log->type == 'step3') {
                    $step3 = \App\Http\Controllers\Frontend\FC2024\RunPerDay_pv_ab01Controller::bonus_allsale_permounth_04();
                    if ($step3['status'] == 'success') {
                        if ($step3['pending'] == 0) {

                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step3['pending'],
                                    'status' => 'success',
                                    'note' => 'step3',
                                    'type' => 'step3'
                                ]);
                            DB::commit();
                            return 'step 3  success  ' . $step3['pending'];
                        } else {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step3['pending'],
                                    'status' => 'pending',
                                    'note' => 'step3',
                                    'type' => 'step3'
                                ]);
                            DB::commit();
                            return 'step 3 pending  ' . $step3['pending'];
                        }
                    } else {
                        // DB::commit();
                        return 'step 3 fail';
                    }
                } elseif ($log->type == 'step4') {
                    $step4 = \App\Http\Controllers\Frontend\FC2024\RunPerDay_pv_ab02Controller::Runbonus4Perday();
                    if ($step4['status'] == 'success') {
                        if ($step4['pending'] == 0) {

                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step4['pending'],
                                    'status' => 'success',
                                    'note' => 'step4',
                                    'type' => 'step4'
                                ]);
                            DB::commit();
                            return 'step 4 success  ' . $step4['pending'];
                        } else {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step4['pending'],
                                    'status' => 'pending',
                                    'note' => 'step4',
                                    'type' => 'step4'
                                ]);
                            DB::commit();
                            return 'step 4 pending  ' . $step4['pending'];
                        }
                    } else {
                        // DB::commit();
                        return 'step 4 fail';
                    }
                } elseif ($log->type == 'step5') {
                    $step5 = \App\Http\Controllers\Frontend\FC2024\RunPerDay_pv_ab03Controller::Runbonus9Perday();
                    if ($step5['status'] == 'success') {
                        if ($step5['pending'] == 0) {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step5['pending'],
                                    'status' => 'success',
                                    'note' => 'step5',
                                    'type' => 'step5'
                                ]);
                            DB::commit();
                            return 'step 5  success ' . $step5['pending'];
                        } else {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step5['pending'],
                                    'status' => 'pending',
                                    'note' => 'step5',
                                    'type' => 'step5'
                                ]);
                            DB::commit();
                            return 'step 5  pending  ' . $step5['pending'];
                        }
                    } else {
                        // DB::commit();
                        return 'step 5 fail';
                    }
                } elseif ($log->type == 'step6') {
                    DB::table('log_run_bonus_2024')
                        ->Insert([
                            'date_run' => $date,
                            'row_pending' => 0,
                            'status' => 'success',
                            'note' => 'step6',
                            'type' => 'step6'
                        ]);
                    DB::commit();
                    return 'step 6  success ' . 0;


                    $step6 = \App\Http\Controllers\Frontend\FC2024\RunPerDay_pv_ab04Controller::bonus_7_all('ss');
                    if ($step6['status'] == 'success') {
                        if ($step6['pending'] == 0) {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step6['pending'],
                                    'status' => 'success',
                                    'note' => 'step6',
                                    'type' => 'step6'
                                ]);
                            DB::commit();
                            return 'step 6  success ' . $step6['pending'];
                        } else {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step6['pending'],
                                    'status' => 'pending',
                                    'note' => 'step6',
                                    'type' => 'step6'
                                ]);
                            DB::commit();
                            return 'step 6 pending  ' . $step6['pending'];
                        }
                    } else {
                        // DB::commit();
                        return 'step 6 fail';
                    }
                } elseif ($log->type == 'step7') {
                    $step7 = \App\Http\Controllers\Frontend\FC2024\RunPerDay_pv_ab02Controller::bonus_4_03();
                    if ($step7['status'] == 'success') {
                        if ($step7['pending'] == 0) {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step7['pending'],
                                    'status' => 'success',
                                    'note' => 'จ่ายเงินโบนัสข้อ 8',
                                    'type' => 'step7'
                                ]);
                            DB::commit();
                            return 'step 7  success ' . $step7['pending'];
                        } else {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step7['pending'],
                                    'status' => 'pending',
                                    'note' => 'จ่ายเงินโบนัสข้อ 8',
                                    'type' => 'step7'
                                ]);
                            DB::commit();
                            return 'step 7 pending  ' . $step7['pending'];
                        }
                    } else {
                        // DB::commit();
                        return 'step 7 fail';
                    }
                } elseif ($log->type == 'step8') {
                    $step8 = \App\Http\Controllers\Frontend\FC2024\RunPerDay_pv_ab03Controller::bonus_9_ewallet();
                    if ($step8['status'] == 'success') {
                        if ($step8['pending'] == 0) {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step8['pending'],
                                    'status' => 'success',
                                    'note' => 'จ่ายเงินโบนัสข้อ 9',
                                    'type' => 'step8'
                                ]);
                            DB::commit();
                            return 'step 8 success ' . $step8['pending'];
                        } else {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step8['pending'],
                                    'status' => 'pending',
                                    'note' => 'จ่ายเงินโบนัสข้อ 9',
                                    'type' => 'step8'
                                ]);
                            DB::commit();
                            return 'step 8 pending  ' . $step8['pending'];
                        }
                    } else {
                        // DB::commit();
                        return 'step 8 fail';
                    }
                } elseif ($log->type == 'step9') {

                    DB::table('log_run_bonus_2024')
                        ->Insert([
                            'date_run' => $date,
                            'row_pending' => 0,
                            'status' => 'success',
                            'note' => 'จ่ายเงินโบนัสข้อ 7 (ไม่จ่ายเเล้ว)',
                            'type' => 'step9'
                        ]);
                    DB::commit();
                    return 'step 9 success ' . 0;



                    $step9 = \App\Http\Controllers\Frontend\FC2024\RunPerDay_pv_ab04Controller::bonus_7_ewallet();
                    if ($step9['status'] == 'success') {
                        if ($step9['pending'] == 0) {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step9['pending'],
                                    'status' => 'success',
                                    'note' => 'จ่ายเงินโบนัสข้อ 7',
                                    'type' => 'step9'
                                ]);
                            DB::commit();
                            return 'step 9 success ' . $step9['pending'];
                        } else {
                            DB::table('log_run_bonus_2024')
                                ->Insert([
                                    'date_run' => $date,
                                    'row_pending' => $step9['pending'],
                                    'status' => 'pending',
                                    'note' => 'จ่ายเงินโบนัสข้อ 7',
                                    'type' => 'step9'
                                ]);
                            DB::commit();
                            return 'step 9 pending ' . $step9['pending'];
                        }
                    } else {
                        // DB::commit();
                        return 'step 9 fail';
                    }
                } else {
                    DB::commit();
                    return  'success full';
                }
                // DB::commit();
                return 'fail';
            }
        } else {
            DB::commit();
            return 'fail ไม่อยู่ในเวลาที่เลือก';
        }
    }
}
