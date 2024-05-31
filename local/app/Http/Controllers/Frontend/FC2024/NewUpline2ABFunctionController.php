<?php

namespace App\Http\Controllers\Frontend\FC2024;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Support\Facades\Log;

class NewUpline2ABFunctionController extends Controller
{

    public static function uplineAB()
    {
        $members = DB::table('customers')
            ->where('status_check_runupline', 'fail')
            // ->where('user_name', '2481778')
            ->orderByDesc('id')
            ->limit(1000)
            ->get();

        $k = 0;
        $f = 0;
        foreach ($members as $member) {
            $introduce_id = $member->introduce_id;
            $data = self::check_type_register([$introduce_id], 1);
            $i = 1;

            Log::debug('Initial data', ['introduce_id' => $introduce_id, 'data' => $data]);

            $previous_user_names = [];
            while ($data['status'] == 'fail' && isset($data['arr_user_name'])) {
                if ($data['status'] == 'fail' && $data['code'] == 'run') {
                    // Log::debug('Loop data', ['iteration' => $i, 'data' => $data]);


                    // ตรวจสอบว่ามีการวนลูปซ้ำ ๆ หรือไม่
                    if (in_array($data['arr_user_name'], $previous_user_names)) {
                        dd('ซ้ำรหัส');
                        Log::debug('Detected loop, breaking loop to prevent infinite loop', ['iteration' => $i, 'data' => $data]);
                        break;
                    }
                    $previous_user_names[] = $data['arr_user_name'];
                }

                $data = self::check_type_register($data['arr_user_name'], $i);

                if ($data['status'] == 'success') {
                    break;
                }

                $i++;
            }


            // dd($members, $data);

            if ($data['status'] == 'success') {
                $k++;
                DB::table('customers')
                    ->where('user_name', $member->user_name)
                    ->update([
                        'upline_id' => $data['upline'],
                        'type_upline' => $data['type'],
                        'status_check_runupline' => 'success'
                    ]);
            } else {
                $f++;
                DB::table('customers')
                    ->where('user_name', $member->user_name)
                    ->update([
                        'status_check_runupline' => 'fail'
                    ]);
            }
        }

        $pending = DB::table('customers')
            ->where('status_check_runupline', 'fail')
            ->count();

        dd('fail:' . $f, 'success:' . $k, 'รหัสรอดำเนินการ:' . $pending, 'success');
    }

    public static function check_type_register($user_name, $lv)
    {
        if ($lv == 1) {
            $data_sponsor = DB::table('customers')
                ->select('user_name', 'upline_id', 'type_upline')
                ->where('upline_id', $user_name)
                ->orderBy('type_upline', 'ASC')
                ->get();
        } else {
            $upline_child = DB::table('customers')
                ->selectRaw('count(upline_id) as count_upline, upline_id')
                ->whereIn('upline_id', $user_name)
                ->groupBy('upline_id')
                ->orderBy('count_upline')
                ->orderBy('type_upline');

            $data_sponsor = DB::table('customers')
                ->selectRaw('(CASE WHEN count_upline IS NULL THEN 0 ELSE count_upline END) as count_upline, user_name, type_upline')
                ->whereIn('user_name', $user_name)
                ->leftJoinSub($upline_child, 'upline_child', function ($join) {
                    $join->on('customers.user_name', '=', 'upline_child.upline_id');
                })
                ->orderBy('count_upline')
                ->orderBy('type_upline')
                ->get();
        }

        if (count($data_sponsor) <= 0) {
            return [
                'status' => 'success',
                'upline' => $user_name,
                'type' => 'A',
                'rs' => $data_sponsor
            ];
        }

        if ($lv == 1) {
            $type = ['A', 'B'];
            $count = count($data_sponsor);
            if ($count < 2) {
                foreach ($data_sponsor as $value) {
                    if (($key = array_search($value->type_upline, $type)) !== false) {
                        unset($type[$key]);
                    }
                }
                $array_key = array_key_first($type);
                return [
                    'status' => 'success',
                    'upline' => $user_name,
                    'type' => $type[$array_key],
                    'rs' => $data_sponsor
                ];
            } elseif ($count >= 2) {
                $arr_user_name = array_map(function ($value) {
                    return $value->user_name;
                }, $data_sponsor->toArray());
                return [
                    'status' => 'fail',
                    'arr_user_name' => $arr_user_name,
                    'code' => 'run'
                ];
            }
        } else {
            if ($data_sponsor[0]->count_upline == 0) {
                return [
                    'status' => 'success',
                    'upline' => $data_sponsor[0]->user_name,
                    'type' => 'A',
                    'rs' => $data_sponsor
                ];
            }

            foreach ($data_sponsor as $value) {
                if ($value->count_upline < 2) {
                    $data_sponsor_check = DB::table('customers')
                        ->select('user_name', 'upline_id', 'type_upline')
                        ->where('upline_id', $value->user_name)
                        ->orderBy('type_upline', 'ASC')
                        ->get();

                    $type = ['A', 'B'];
                    foreach ($data_sponsor_check as $value_2) {
                        if (($key = array_search($value_2->type_upline, $type)) !== false) {
                            unset($type[$key]);
                        }
                    }
                    $array_key = array_key_first($type);
                    return [
                        'status' => 'success',
                        'upline' => $value->user_name,
                        'type' => $type[$array_key],
                        'rs' => $data_sponsor_check
                    ];
                }

                if ($value->type_upline == 'B' && $value->count_upline == 2 && $lv == 2) {
                    return self::check_next_levels_recursive([$user_name], 1);
                }
            }
        }
    }

    private static function check_next_levels_recursive($user_names, $level)
    {
        if ($level > 100) {
            return [
                'status' => 'fail',
                'ms' => 'ไม่สามารถลงทะเบียนได้กรุณาติดต่อเจ้าหน้าที่'
            ];
        }

        $data_sponsor_check = DB::table('customers')
            ->select('user_name', 'upline_id', 'type_upline')
            ->whereIn('upline_id', $user_names)
            ->orderBy('type_upline', 'ASC')
            ->orderBy('id', 'ASC')
            ->get();

        $user_full = [];
        foreach ($data_sponsor_check as $value) {
            $check_auto_plack = self::check_auto_plack($value->user_name);

            if ($check_auto_plack['status'] == 'success') {
                return $check_auto_plack;
            } else {
                $user_full[] = $value->user_name;
            }
        }

        if (count($user_full) > 0) {
            return self::check_next_levels_recursive($user_full, $level + 1);
        }

        return [
            'status' => 'fail',
            'ms' => 'ไม่สามารถลงทะเบียนได้กรุณาติดต่อเจ้าหน้าที่'
        ];
    }

    public static function check_auto_plack($user_name)
    {
        $data_sponsor = DB::table('customers')
            ->select('user_name', 'upline_id', 'type_upline')
            ->where('upline_id', $user_name)
            ->orderBy('type_upline', 'ASC')
            ->get();

        if (count($data_sponsor) <= 0) {
            return [
                'status' => 'success',
                'upline' => $user_name,
                'type' => 'A',
                'rs' => $data_sponsor
            ];
        }

        $type = ['A', 'B'];
        if (count($data_sponsor) < 2) {
            foreach ($data_sponsor as $value) {
                if (($key = array_search($value->type_upline, $type)) !== false) {
                    unset($type[$key]);
                }
            }
            $array_key = array_key_first($type);
            return [
                'status' => 'success',
                'upline' => $user_name,
                'type' => $type[$array_key],
                'rs' => $data_sponsor
            ];
        } else {
            return ['status' => 'fail', 'code' => 'run'];
        }
    }



    // public static function check_type_register($user_name, $lv)
    // { //สำหรับหาสายล่างสุด ออโต้เพลง 1-5

    //     if ($lv == 1) {
    //         $data_sponser = DB::table('customers')
    //             ->select('user_name', 'upline_id', 'type_upline')
    //             ->where('upline_id', $user_name)
    //             ->orderby('type_upline', 'ASC')
    //             ->get();


    //         // $test = DB::table('customers')
    //         // ->select('user_name','upline_id','type_upline')
    //         // ->orderby('type_upline','ASC')
    //         // ->get();
    //         // dd($test);

    //     } else {

    //         // if($lv == 3){
    //         //     dd($user_name);
    //         //     //dd($data_sponser,$lv);
    //         // }
    //         $upline_child = DB::table('customers')
    //             ->selectRaw('count(upline_id) as count_upline, upline_id')
    //             ->whereIn('upline_id', $user_name)
    //             ->orderby('count_upline')
    //             ->orderby('type_upline')
    //             ->groupby('upline_id');


    //         $data_sponser = DB::table('customers')
    //             //->selectRaw('count(upline_id) as count_upline,upline_id')
    //             //->whereIn('upline_id',$user_name)
    //             //->groupby('upline_id')
    //             //->orderby('count_upline','ASC')

    //             ->selectRaw('(CASE WHEN count_upline IS NULL THEN 0 ELSE count_upline END) as count_upline,user_name,type_upline')
    //             ->whereIn('user_name', $user_name)
    //             ->leftJoinSub($upline_child, 'upline_child', function ($join) {
    //                 $join->on('customers.user_name', '=', 'upline_child.upline_id');
    //             })
    //             ->orderby('count_upline')
    //             ->orderby('type_upline')
    //             ->get();
    //     }

    //     if (count($data_sponser) <= 0) {
    //         $data = ['status' => 'success', 'upline' => $user_name, 'type' => 'A', 'rs' => $data_sponser];

    //         return $data;
    //     }

    //     if ($lv == 1) {
    //         $type = ['A', 'B'];
    //         $count = count($data_sponser);
    //         if ($count < 2) {
    //             //dd('ddd');
    //             foreach ($data_sponser as $value) {
    //                 if (($key = array_search($value->type_upline, $type)) !== false) {
    //                     unset($type[$key]);
    //                 }
    //                 // if ($value->type_upline != 'A') {
    //                 //     $upline = $value->upline_id;

    //                 //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'A', 'rs' => $value];
    //                 //     return $data;
    //                 // } else if ($value->type_upline != 'B') {
    //                 //     $upline = $value->upline_id;

    //                 //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'B', 'rs' => $value];
    //                 //     return $data;
    //                 // } else if ($value->type_upline != 'C') {
    //                 //     $upline = $value->upline_id;
    //                 //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'C', 'rs' => $value];
    //                 //     return $data;
    //                 // } else if ($value->type_upline != 'D') {
    //                 //     $upline = $value->upline_id;
    //                 //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'D', 'rs' => $value];
    //                 //     return $data;
    //                 // } else if ($value->type_upline != 'E') {
    //                 //     $upline = $value->upline_id;
    //                 //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'E', 'rs' => $value];
    //                 //     return $data;
    //                 // } else {
    //                 //     $upline = $value->upline_id;
    //                 //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'A', 'rs' => $value];
    //                 //     return $data;
    //                 // }
    //             }
    //             $array_key = array_key_first($type);
    //             $upline =  $user_name;
    //             $data = ['status' => 'success', 'upline' => $upline, 'type' => $type[$array_key], 'rs' => $value];

    //             return $data;


    //             //dd($data_sponser);

    //         } elseif ($count >= 2) {
    //             foreach ($data_sponser as $value) {
    //                 $arr_user_name[] = $value->user_name;
    //             }

    //             $data = ['status' => 'fail', 'arr_user_name' => $arr_user_name, 'code' => 'run'];
    //             return $data;
    //         } else {

    //             //$data = ['status' => 'fail', 'ms' => 'ไม่สามารถลงทะเบียนได้กรุณาติดต่อเจ้าหน้าที่', 'user_name' => $data_sponser, 'code' => 'stop'];

    //             return response()->json(['status' => 'fail', 'ms' => 'CODE:25 ไม่สามารถลงทะเบียนได้กรุณาติดต่อเจ้าหน้าที่']);
    //             // return $data;
    //         }
    //     } else {
    //         if ($data_sponser[0]->count_upline ==  0) {

    //             $upline = $data_sponser[0]->user_name;
    //             $data = ['status' => 'success', 'upline' => $upline, 'type' => 'A', 'rs' => $data_sponser];
    //             return $data;
    //         }

    //         foreach ($data_sponser as $value) {
    //             if ($value->count_upline < 2) {





    //                 $data_sponser_ckeck = DB::table('customers')
    //                     ->select('user_name', 'upline_id', 'type_upline')
    //                     ->where('upline_id', $value->user_name)
    //                     ->orderby('type_upline', 'ASC')
    //                     ->get();


    //                 $type = ['A', 'B'];


    //                 foreach ($data_sponser_ckeck as $value_2) {

    //                     if (($key = array_search($value_2->type_upline, $type)) !== false) {
    //                         unset($type[$key]);
    //                     }
    //                     // if ($value->type_upline != 'A') {
    //                     //     $upline = $value->upline_id;

    //                     //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'A', 'rs' => $value];
    //                     //     return $data;
    //                     // } else if ($value->type_upline != 'B') {
    //                     //     $upline = $value->upline_id;
    //                     //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'B', 'rs' => $value];
    //                     //     return $data;
    //                     // } else if ($value->type_upline != 'C') {
    //                     //     $upline = $value->upline_id;
    //                     //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'C', 'rs' => $value];
    //                     //     return $data;
    //                     // } else if ($value->type_upline != 'D') {
    //                     //     $upline = $value->upline_id;
    //                     //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'D', 'rs' => $value];
    //                     //     return $data;
    //                     // } else if ($value->type_upline != 'E') {
    //                     //     $upline = $value->upline_id;
    //                     //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'E', 'rs' => $value];
    //                     //     return $data;
    //                     // } else {
    //                     //     $upline = $value->upline_id;
    //                     //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'A', 'rs' => $value];
    //                     //     return $data;
    //                     // }
    //                 }
    //                 $array_key = array_key_first($type);

    //                 $upline =  $value->user_name;
    //                 $data = ['status' => 'success', 'upline' => $upline, 'type' => $type[$array_key], 'rs' => $data_sponser_ckeck];

    //                 return $data;

    //                 // dd($data_sponser);

    //             }


    //             if ($value->type_upline == 'B' and $value->count_upline == 2) {

    //                 if ($lv == 2) { //2 คนแรกเต็มหมด

    //                     $data_sponser_ckeck = DB::table('customers')
    //                         ->select('user_name', 'upline_id', 'type_upline')
    //                         ->wherein('upline_id',  $user_name)
    //                         ->orderby('type_upline', 'ASC')
    //                         ->orderby('id', 'ASC')
    //                         ->get();


    //                     $l = 0;
    //                     $user_full = array();
    //                     foreach ($data_sponser_ckeck as $value) {
    //                         $l++;
    //                         $check_auto_plack = NewUpline2ABFunctionController::check_auto_plack($value->user_name);

    //                         if ($check_auto_plack['status'] == 'success') {
    //                             return  $check_auto_plack;
    //                         } else {
    //                             $user_full[$l] = $value->user_name;
    //                             // $user_full[$l]['type'] = $check_auto_plack['status'];
    //                         }

    //                         if ($l == 8) {
    //                             $data_sponser_ckeck_vl_3 = DB::table('customers')
    //                                 ->select('user_name', 'upline_id', 'type_upline')
    //                                 ->wherein('upline_id',  $user_full)
    //                                 ->orderby('type_upline', 'ASC')
    //                                 ->orderby('id', 'ASC')
    //                                 ->get();


    //                             foreach ($data_sponser_ckeck_vl_3 as $value) {
    //                                 $user_full_lv_3[] = $value->user_name;
    //                             }


    //                             $data_sponser_ckeck_vl_4 = DB::table('customers')
    //                                 ->select('user_name', 'upline_id', 'type_upline')
    //                                 ->wherein('upline_id',  $user_full_lv_3)
    //                                 ->orderby('type_upline', 'ASC')
    //                                 ->orderby('id', 'ASC')
    //                                 ->get();



    //                             $l4 = 0;

    //                             foreach ($data_sponser_ckeck_vl_4 as $value) {
    //                                 $l4++;
    //                                 $check_auto_plack_lv4 = NewUpline2ABFunctionController::check_auto_plack($value->user_name);
    //                                 if ($check_auto_plack_lv4['status'] == 'success') {
    //                                     return  $check_auto_plack_lv4;
    //                                 }
    //                                 if ($l4 == 16) {
    //                                     //เช็คต่อไปเรื่อยๆไม่สินสุด
    //                                     // $data = ['status' => 'fail', 'ms' => '16 คนชั้น 4 Code:25', 'user_name' => $data_sponser, 'code' => 'stop'];
    //                                     // return $data;
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // }

    // public static function check_auto_plack($user_name)
    // {
    //     $data_sponser = DB::table('customers')
    //         ->select('user_name', 'upline_id', 'type_upline')
    //         ->where('upline_id', $user_name)
    //         ->orderby('type_upline', 'ASC')
    //         ->get();
    //     if (count($data_sponser) <= 0) {
    //         $data = ['status' => 'success', 'upline' => $user_name, 'type' => 'A', 'rs' => $data_sponser];
    //         return $data;
    //     }

    //     $type = ['A', 'B'];
    //     $count = count($data_sponser);
    //     if ($count < 2) {

    //         foreach ($data_sponser as $value) {
    //             if (($key = array_search($value->type_upline, $type)) !== false) {
    //                 unset($type[$key]);
    //             }
    //         }
    //         $array_key = array_key_first($type);
    //         $upline =  $user_name;
    //         $data = ['status' => 'success', 'upline' => $upline, 'type' => $type[$array_key], 'rs' => $value];
    //         return $data;
    //     } else {

    //         $data = ['status' => 'fail', 'code' => 'run'];
    //         return $data;
    //     }
    // }


    // public static function uplineAB()
    // {

    //     // $pending = DB::table('customers')
    //     //     ->where('status_check_runupline', 'fail')
    //     //     ->count();

    //     // dd($pending);
    //     // ดึงข้อมูลสมาชิกที่ยังไม่มี upline_id และ type_upline
    //     $members = DB::table('customers')
    //         ->where('status_check_runupline', 'pending')
    //         ->orderBy('id')
    //         ->limit(1)
    //         ->get();

    //     $k = 0;
    //     foreach ($members as $member) {
    //         $introduce_id = $member->introduce_id;
    //         $data = self::check_type_register($introduce_id, 1);
    //         $i = 1;

    //         while ($data['status'] == 'fail' && isset($data['arr_user_name']) && $i <= 10) { // กำหนดให้วนลูปไม่เกิน 10 ครั้ง
    //             $i++;
    //             $data = self::check_type_register($data['arr_user_name'], $i);
    //         }

    //         if ($data['status'] == 'success') {
    //             // อัปเดตข้อมูล upline_id และ type_upline
    //             $k++;
    //             DB::table('customers')
    //                 ->where('user_name', $member->user_name)
    //                 ->update([
    //                     'upline_id' => $data['upline'],
    //                     'type_upline' => $data['type'],
    //                     'status_check_runupline' => 'success'
    //                 ]);
    //         } else {
    //             // ถ้าไม่เจอ upline ที่เหมาะสม สามารถเพิ่มการจัดการข้อผิดพลาดหรือดำเนินการอื่นๆ ที่จำเป็น

    //             DB::table('customers')
    //                 ->where('user_name', $member->user_name)
    //                 ->update([
    //                     'status_check_runupline' => 'fail'
    //                 ]);
    //         }
    //     }

    //     $pending = DB::table('customers')
    //         ->where('status_check_runupline', 'pending')
    //         ->count();
    //     dd('รันไปทั้งหมด:' . $k, 'รหัสรอดำเนินการ:' . $pending, 'success');
    // }


    // public static function check_type_register($user_name, $lv)
    // {
    //     if ($lv == 1) {
    //         $data_sponser = DB::table('customers')
    //             ->select('user_name', 'upline_id', 'type_upline')
    //             ->where('upline_id', $user_name)
    //             ->orderBy('type_upline', 'ASC')
    //             ->get();
    //     } else {
    //         $upline_child = DB::table('customers')
    //             ->selectRaw('count(upline_id) as count_upline, upline_id')
    //             ->whereIn('upline_id', $user_name)
    //             ->orderBy('count_upline')
    //             ->orderBy('type_upline')
    //             ->groupBy('upline_id');

    //         $data_sponser = DB::table('customers')
    //             ->selectRaw('(CASE WHEN count_upline IS NULL THEN 0 ELSE count_upline END) as count_upline, user_name, type_upline')
    //             ->whereIn('user_name', $user_name)
    //             ->leftJoinSub($upline_child, 'upline_child', function ($join) {
    //                 $join->on('customers.user_name', '=', 'upline_child.upline_id');
    //             })
    //             ->orderBy('count_upline')
    //             ->orderBy('type_upline')
    //             ->get();
    //     }

    //     if (count($data_sponser) <= 0) {
    //         return ['status' => 'success', 'upline' => $user_name, 'type' => 'A', 'rs' => $data_sponser];
    //     }

    //     if ($lv == 1) {
    //         $type = ['A', 'B'];
    //         $count = count($data_sponser);
    //         if ($count < 2) {
    //             foreach ($data_sponser as $value) {
    //                 if (($key = array_search($value->type_upline, $type)) !== false) {
    //                     unset($type[$key]);
    //                 }
    //             }
    //             $array_key = array_key_first($type);
    //             return ['status' => 'success', 'upline' => $user_name, 'type' => $type[$array_key], 'rs' => $data_sponser];
    //         } else {
    //             $arr_user_name = [];
    //             foreach ($data_sponser as $value) {
    //                 $arr_user_name[] = $value->user_name;
    //             }
    //             return ['status' => 'fail', 'arr_user_name' => $arr_user_name, 'code' => 'run'];
    //         }
    //     } else {
    //         foreach ($data_sponser as $value) {
    //             if ($value->count_upline < 2) {
    //                 $data_sponser_check = DB::table('customers')
    //                     ->select('user_name', 'upline_id', 'type_upline')
    //                     ->where('upline_id', $value->user_name)
    //                     ->orderBy('type_upline', 'ASC')
    //                     ->get();

    //                 $type = ['A', 'B'];
    //                 foreach ($data_sponser_check as $value_2) {
    //                     if (($key = array_search($value_2->type_upline, $type)) !== false) {
    //                         unset($type[$key]);
    //                     }
    //                 }
    //                 $array_key = array_key_first($type);
    //                 return ['status' => 'success', 'upline' => $value->user_name, 'type' => $type[$array_key], 'rs' => $data_sponser_check];
    //             }
    //         }
    //         return ['status' => 'fail', 'arr_user_name' => array_column($data_sponser->toArray(), 'user_name'), 'code' => 'run'];
    //     }
    // }



    // public static function uplineAB()
    // {

    //     // $update =  DB::table('customers')
    //     //     ->where('upline_id', '!=', 'AA')
    //     //     ->update(['type_upline' => null, 'upline_id' => null, 'upline_id' => null, 'status_check_runupline' => 'pending']);
    //     // dd($update);

    //     // ดึงข้อมูลสมาชิกที่ยังไม่มี upline_id และ type_upline
    //     $members = DB::table('customers')
    //         ->whereNull('upline_id')
    //         ->orWhereNull('type_upline')
    //         ->orderBy('id')
    //         ->limit(1)
    //         ->get();

    //     $k = 0;
    //     foreach ($members as $member) {
    //         $introduce_id = $member->introduce_id;
    //         $data = self::check_type_register($introduce_id, 1);
    //         $i = 1;
    //         $x = 'start';

    //         while ($x == 'start') {
    //             $i++;

    //             if ($data['status'] == 'fail' and $data['code'] == 'stop') {

    //                 $x = 'stop';
    //                 return response()->json(['status' => 'fail', 'ms' => $data['ms']]);

    //                 return response()->json(['ms' => $data['ms'], 'status' => 'fail']);
    //             } elseif ($data['status'] == 'fail' and $data['code'] == 'run') {

    //                 $data = NewUpline2ABFunctionController::check_type_register($data['arr_user_name'], $i);
    //                 dd($data);
    //             } else {
    //                 $x = 'stop';
    //             }
    //         }


    //         if ($data['status'] == 'success') {
    //             // อัปเดตข้อมูล upline_id และ type_upline
    //             $k++;
    //             DB::table('customers')
    //                 ->where('user_name', $member->user_name)
    //                 ->update([
    //                     'upline_id' => $data['upline'],
    //                     'type_upline' => $data['type'],
    //                     'status_check_runupline' => 'success'
    //                 ]);
    //         } else {
    //             // ถ้าไม่เจอ upline ที่เหมาะสม สามารถเพิ่มการจัดการข้อผิดพลาดหรือดำเนินการอื่นๆ ที่จำเป็น
    //             DB::table('customers')
    //                 ->where('user_name', $member->user_name)
    //                 ->update([
    //                     'status_check_runupline' => 'fail'
    //                 ]);
    //         }
    //     }

    //     $pending = DB::table('customers')
    //         ->where('status_check_runupline', 'pending')
    //         ->count();
    //     dd('รันไปทั้งหมด:' . $k, 'รหัสรอดำเนินการ:' . $pending, 'success');
    // }



    // public static function check_type_register($user_name, $lv)
    // {

    //     if ($lv == 1) {
    //         $data_sponser = DB::table('customers')
    //             ->select('user_name', 'upline_id', 'type_upline')
    //             ->where('upline_id', $user_name)
    //             ->orderby('type_upline', 'ASC')
    //             ->get();




    //         // $test = DB::table('customers')
    //         // ->select('user_name','upline_id','type_upline')
    //         // ->orderby('type_upline','ASC')
    //         // ->get();
    //         // dd($test);

    //     } else {

    //         $upline_child = DB::table('customers')
    //             ->selectRaw('count(upline_id) as count_upline, upline_id')
    //             ->whereIn('upline_id', $user_name)
    //             ->orderby('count_upline')
    //             ->orderby('type_upline')
    //             ->groupby('upline_id');


    //         $data_sponser = DB::table('customers')
    //             //->selectRaw('count(upline_id) as count_upline,upline_id')
    //             //->whereIn('upline_id',$user_name)
    //             //->groupby('upline_id')
    //             //->orderby('count_upline','ASC')

    //             ->selectRaw('(CASE WHEN count_upline IS NULL THEN 0 ELSE count_upline END) as count_upline,user_name,type_upline')
    //             ->whereIn('user_name', $user_name)
    //             ->leftJoinSub($upline_child, 'upline_child', function ($join) {
    //                 $join->on('customers.user_name', '=', 'upline_child.upline_id');
    //             })
    //             ->orderby('count_upline')
    //             ->orderby('type_upline')
    //             ->get();
    //     }

    //     if (count($data_sponser) <= 0) {
    //         $data = ['status' => 'success', 'upline' => $user_name, 'type' => 'A', 'rs' => $data_sponser];

    //         return $data;
    //     }

    //     if ($lv == 1) {
    //         $type = ['A', 'B'];
    //         $count = count($data_sponser);
    //         if ($count < 2) {
    //             //dd('ddd');
    //             foreach ($data_sponser as $value) {
    //                 if (($key = array_search($value->type_upline, $type)) !== false) {
    //                     unset($type[$key]);
    //                 }
    //             }
    //             $array_key = array_key_first($type);
    //             $upline =  $user_name;
    //             $data = ['status' => 'success', 'upline' => $upline, 'type' => $type[$array_key], 'rs' => $value];

    //             return $data;


    //             //dd($data_sponser);

    //         } elseif ($count >= 2) {
    //             foreach ($data_sponser as $value) {
    //                 $arr_user_name[] = $value->user_name;
    //             }

    //             $data = ['status' => 'fail', 'arr_user_name' => $arr_user_name, 'code' => 'run'];
    //             return $data;
    //         } else {

    //             //$data = ['status' => 'fail', 'ms' => 'ไม่สามารถลงทะเบียนได้กรุณาติดต่อเจ้าหน้าที่', 'user_name' => $data_sponser, 'code' => 'stop'];

    //             return response()->json(['status' => 'fail', 'ms' => 'CODE:25 ไม่สามารถลงทะเบียนได้กรุณาติดต่อเจ้าหน้าที่']);
    //             // return $data;
    //         }
    //     } else {
    //         if ($data_sponser[0]->count_upline ==  0) {

    //             $upline = $data_sponser[0]->user_name;
    //             $data = ['status' => 'success', 'upline' => $upline, 'type' => 'A', 'rs' => $data_sponser];
    //             return $data;
    //         }

    //         foreach ($data_sponser as $value) {
    //             if ($value->count_upline < 2) {

    //                 $data_sponser_ckeck = DB::table('customers')
    //                     ->select('user_name', 'upline_id', 'type_upline')
    //                     ->where('upline_id', $value->user_name)
    //                     ->orderby('type_upline', 'ASC')
    //                     ->get();
    //                 $type = ['A', 'B'];


    //                 foreach ($data_sponser_ckeck as $value_2) {

    //                     if (($key = array_search($value_2->type_upline, $type)) !== false) {
    //                         unset($type[$key]);
    //                     }
    //                 }
    //                 $array_key = array_key_first($type);

    //                 $upline =  $value->user_name;
    //                 $data = ['status' => 'success', 'upline' => $upline, 'type' => $type[$array_key], 'rs' => $data_sponser_ckeck];

    //                 return $data;

    //                 // dd($data_sponser);

    //             }
    //             if ($value->type_upline == 'B' and $value->count_upline == 2) {
    //                 if ($lv == 2) {

    //                     $data_sponser_ckeck = DB::table('customers')
    //                         ->select('user_name', 'upline_id', 'type_upline')
    //                         ->wherein('upline_id',  $user_name)
    //                         ->orderby('type_upline', 'ASC')
    //                         ->orderby('id', 'ASC')
    //                         ->get();
    //                     $l = 0;
    //                     $user_full = array();
    //                     foreach ($data_sponser_ckeck as $value) {
    //                         $l++;
    //                         $check_auto_plack = NewUpline2ABFunctionController::check_auto_plack($value->user_name);

    //                         if ($check_auto_plack['status'] == 'success') {
    //                             return  $check_auto_plack;
    //                         } else {
    //                             $user_full[$l] = $value->user_name;
    //                             // $user_full[$l]['type'] = $check_auto_plack['status'];
    //                         }

    //                         if ($l == 25) {
    //                             $data_sponser_ckeck_vl_3 = DB::table('customers')
    //                                 ->select('user_name', 'upline_id', 'type_upline')
    //                                 ->wherein('upline_id',  $user_full)
    //                                 ->orderby('type_upline', 'ASC')
    //                                 ->orderby('id', 'ASC')
    //                                 ->get();



    //                             foreach ($data_sponser_ckeck_vl_3 as $value) {
    //                                 $user_full_lv_3[] = $value->user_name;
    //                             }

    //                             $data_sponser_ckeck_vl_4 = DB::table('customers')
    //                                 ->select('user_name', 'upline_id', 'type_upline')
    //                                 ->wherein('upline_id',  $user_full_lv_3)
    //                                 ->orderby('type_upline', 'ASC')
    //                                 ->orderby('id', 'ASC')
    //                                 ->get();



    //                             $l4 = 0;

    //                             foreach ($data_sponser_ckeck_vl_4 as $value) {
    //                                 $l4++;
    //                                 $check_auto_plack_lv4 = NewUpline2ABFunctionController::check_auto_plack($value->user_name);
    //                                 if ($check_auto_plack_lv4['status'] == 'success') {
    //                                     return  $check_auto_plack_lv4;
    //                                 }
    //                                 if ($l4 == 625) {

    //                                     $data = ['status' => 'fail', 'ms' => 'ไม่สามารถลงทะเบียนได้กรุณาติดต่อเจ้าหน้าที่ Code:25', 'user_name' => $data_sponser, 'code' => 'stop'];
    //                                     return $data;
    //                                 }
    //                             }
    //                         }
    //                     }
    //                 }
    //             }
    //         }
    //     }
    // }

    // public static function check_auto_plack($user_name)
    // {
    //     $data_sponser = DB::table('customers')
    //         ->select('user_name', 'upline_id', 'type_upline')
    //         ->where('upline_id', $user_name)
    //         ->orderby('type_upline', 'ASC')
    //         ->get();
    //     if (count($data_sponser) <= 0) {
    //         $data = ['status' => 'success', 'upline' => $user_name, 'type' => 'A', 'rs' => $data_sponser];
    //         return $data;
    //     }

    //     $type = ['A', 'B'];
    //     $count = count($data_sponser);
    //     if ($count < 2) {
    //         //dd('ddd');
    //         foreach ($data_sponser as $value) {
    //             if (($key = array_search($value->type_upline, $type)) !== false) {
    //                 unset($type[$key]);
    //             }
    //         }
    //         $array_key = array_key_first($type);
    //         $upline =  $user_name;
    //         $data = ['status' => 'success', 'upline' => $upline, 'type' => $type[$array_key], 'rs' => $value];
    //         return $data;


    //         //dd($data_sponser);

    //     } else {
    //         foreach ($data_sponser as $value) {
    //             $arr_user_name[] = $value->user_name;
    //         }

    //         $data = ['status' => 'fail', 'code' => 'run'];
    //         return $data;
    //     }
    // }



    // public static function find_upline($introduce_id)
    // {
    //     // เริ่มต้นการค้นหาจากผู้แนะนำ (introduce_id)
    //     $upline_candidate = self::find_best_upline($introduce_id);

    //     if ($upline_candidate) {
    //         return [
    //             'status' => 'success',
    //             'upline_id' => $upline_candidate['upline_id'],
    //             'type_upline' => $upline_candidate['type_upline']
    //         ];
    //     } else {
    //         return [
    //             'status' => 'fail',
    //             'message' => 'No suitable upline found'
    //         ];
    //     }
    // }

    // private static function find_best_upline($user_name)
    // {
    //     $data_sponser = DB::table('customers')
    //         ->select('user_name', 'upline_id', 'type_upline')
    //         ->whereIn('type_upline', ['A', 'B'])
    //         ->where('upline_id', $user_name)
    //         ->orderBy('type_upline', 'ASC')
    //         ->get();

    //     // ถ้าไม่มีลูกทีมในสายงาน ให้ return เป็นสาย A
    //     if (count($data_sponser) <= 0) {
    //         return [
    //             'upline_id' => $user_name,
    //             'type_upline' => 'A'
    //         ];
    //     }

    //     $type = ['A', 'B'];
    //     $count = count($data_sponser);

    //     // ถ้าลูกทีมมีไม่ครบ 2 สาย ให้ return สายที่ยังว่างอยู่
    //     if ($count < 2) {
    //         foreach ($data_sponser as $value) {
    //             if (($key = array_search($value->type_upline, $type)) !== false) {
    //                 unset($type[$key]);
    //             }
    //         }

    //         $array_key = array_key_first($type);
    //         return [
    //             'upline_id' => $user_name,
    //             'type_upline' => $type[$array_key]
    //         ];
    //     } else {
    //         // ถ้าลูกทีมครบ 2 สายแล้ว ให้ค้นหาทีละชั้นจนกว่าจะเจอสมาชิกที่มีไม่ครบ 2 สาย

    //         foreach ($data_sponser as $value) {
    //             $result = self::find_best_upline($value->user_name);
    //             if ($result) {
    //                 return $result;
    //             }
    //         }
    //     }

    //     // ถ้าไม่เจอสมาชิกที่มีไม่ครบ 2 สาย
    //     return null;
    // }





    // public static function uplineAB()
    // {

    //     // $update =  DB::table('customers')
    //     //     ->where('upline_id', '!=', 'AA')
    //     //     ->update(['type_upline' => null, 'upline_id' => null, 'upline_id' => null, 'status_check_runupline' => 'pending']);
    //     // dd($update);

    //     $customers  = DB::table('customers')
    //         ->where('status_check_runupline', 'pending')
    //         // ->whereNull('type_upline')
    //         ->where('introduce_id', '6135984')
    //         ->orderBy('id')
    //         ->limit(1)
    //         ->get();



    //     $k = 0;
    //     foreach ($customers as $upline) {

    //         if ($upline->user_name == $upline->upline_id) {
    //             dd($upline->user_name, 'มีปัญหา uplineAB');
    //         }

    //         $i = 0;
    //         $i++;
    //         $k++;
    //         if ($i == 1) {
    //             $run_username = $upline->user_name;
    //         }

    //         if ($upline->upline_id !== 'AA') {

    //             if ($upline->type_upline == 'A') {
    //                 $check = DB::table('customers')
    //                     ->select(
    //                         'customers.pv',
    //                         'customers.id',
    //                         'customers.name',
    //                         'customers.last_name',
    //                         'customers.user_name',
    //                         'customers.qualification_id',
    //                         'customers.expire_date',
    //                         'dataset_qualification.code',
    //                         'customers.introduce_id',
    //                         'customers.upline_id',
    //                         'customers.type_upline',
    //                     )
    //                     ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
    //                     ->where('customers.upline_id', $upline->user_name)
    //                     ->where('customers.type_upline', 'A')
    //                     ->first();

    //                 if ($check) {
    //                     if ($upline->user_name == $check->user_name) {
    //                         DB::table('customers')
    //                             ->where('user_name', $run_username)
    //                             ->update(['status_check_runupline' => 'success']);
    //                     } else {
    //                         // dd('1');
    //                         $check_auto_plack =  \App\Http\Controllers\Frontend\RegisterController::check_auto_plack($upline->upline_id);

    //                         dd($check_auto_plack);
    //                         DB::table('customers')
    //                             ->where('user_name', $run_username)
    //                             ->update(['upline_id' => $check_auto_plack['upline'], 'type_upline' => $check_auto_plack['type'], 'status_check_runupline' => 'success']);
    //                     }
    //                 } else {
    //                     DB::table('customers')
    //                         ->where('user_name', $run_username)
    //                         ->update(['type_upline' => 'A', 'status_check_runupline' => 'success']);
    //                 }

    //                 // Update the introduce_id of the previous customer to the user_name of the next valid upline
    //                 // if ($previous_user_name) {
    //                 //     DB::table('customers')
    //                 //         ->where('user_name', $previous_user_name)
    //                 //         ->update(['introduce_id' => $next_user_name, 'upline_id' => $next_upline_id, 'type_upline' => $next_type_upline]);
    //                 // }


    //             } elseif ($upline->type_upline == 'B') {

    //                 $check = DB::table('customers')
    //                     ->select(
    //                         'customers.pv',
    //                         'customers.id',

    //                         'customers.name',
    //                         'customers.last_name',
    //                         'customers.user_name',
    //                         'customers.qualification_id',
    //                         'customers.expire_date',
    //                         'dataset_qualification.code',
    //                         'customers.introduce_id',
    //                         'customers.upline_id',
    //                         'customers.type_upline',
    //                     )
    //                     ->leftJoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
    //                     ->where('customers.upline_id', $upline->user_name)
    //                     ->where('customers.type_upline', 'B')
    //                     ->first();


    //                 if ($check) {

    //                     if ($upline->user_name == $check->user_name) {
    //                         DB::table('customers')
    //                             ->where('user_name', $run_username)
    //                             ->update(['status_check_runupline' => 'success']);
    //                     } else {

    //                         $check_auto_plack =  \App\Http\Controllers\Frontend\RegisterController::check_auto_plack($upline->upline_id);
    //                         // dd($customers, $check_auto_plack);

    //                         DB::table('customers')
    //                             ->where('user_name', $run_username)
    //                             ->update(['upline_id' => $check_auto_plack['upline'], 'type_upline' => $check_auto_plack['type'], 'status_check_runupline' => 'success']);
    //                     }
    //                 } else {
    //                     DB::table('customers')
    //                         ->where('user_name', $run_username)
    //                         ->update(['type_upline' => 'B', 'status_check_runupline' => 'success']);
    //                 }
    //             } else {

    //                 $check_auto_plack =  \App\Http\Controllers\Frontend\RegisterController::check_auto_plack($upline->upline_id);
    //                 dd($check_auto_plack);
    //                 DB::table('customers')
    //                     ->where('user_name', $run_username)
    //                     ->update(['upline_id' => $check_auto_plack['upline'], 'type_upline' => $check_auto_plack['type'], 'status_check_runupline' => 'success']);
    //             }
    //         }

    //         //รันเสร็จ
    //         // DB::table('customers')
    //         //     ->where('user_name', $run_username)
    //         //     ->update(['status_check_runupline' => 'success']);
    //     }

    //     $pending = DB::table('customers')
    //         ->where('status_check_runupline', 'pending')
    //         ->count();
    //     dd('รันไปทั้งหมด:' . $k, 'รหัสรอดำเนินการ:' . $pending, 'success');
    // }


}
