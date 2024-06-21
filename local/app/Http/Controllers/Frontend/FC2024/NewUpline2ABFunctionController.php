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
        //     ->where('upline_id', '!=', 'AA')
        //     ->update(['type_upline' => null, 'upline_id' => null, 'upline_id' => null, 'status_check_runupline' => 'pending']);
        // dd($update);

        $members = DB::table('customers')
            ->where('status_check_runupline', 'pending')
            // ->where('introduce_id', '0534768')
            ->orderBy('id')
            ->limit(100000)
            ->get();
        // dd($members);

        $k = 0;
        $f = 0;
        foreach ($members as $member) {
            $introduce_id = $member->introduce_id;
            $data = NewUpline2ABFunctionController::check_type_register($introduce_id, 1);
            $i = 1;
            $x = 'start';

            while ($x == 'start') {
                $i++;

                if ($data['status'] == 'fail' and $data['code'] == 'stop') {

                    $x = 'stop';
                    return response()->json(['status' => 'fail', 'ms' => $data['ms']]);

                    return response()->json(['ms' => $data['ms'], 'status' => 'fail']);
                } elseif ($data['status'] == 'fail' and $data['code'] == 'run') {

                    $data = NewUpline2ABFunctionController::check_type_register($data['arr_user_name'], $i);
                } else {
                    $x = 'stop';
                }
                // if ($i == 2) {
                //     dd($data);
                // }
            }

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
            ->where('status_check_runupline', 'pending')
            ->count();

        dd('fail:' . $f, 'success:' . $k, 'รหัสรอดำเนินการ:' . $pending, 'success');
    }



    public static function check_type_register($user_name, $lv)
    { //สำหรับหาสายล่างสุด ออโต้เพลง 1-5

        if ($lv == 1) {
            $data_sponser = DB::table('customers')
                ->select('user_name', 'upline_id', 'type_upline')
                ->where('upline_id', $user_name)
                ->orderby('type_upline', 'ASC')
                ->get();
        } else {


            $upline_child = DB::table('customers')
                ->selectRaw('count(upline_id) as count_upline, upline_id')
                ->whereIn('upline_id', $user_name)
                ->orderby('count_upline')
                ->orderby('type_upline')
                ->groupby('upline_id');


            $data_sponser = DB::table('customers')

                ->selectRaw('(CASE WHEN count_upline IS NULL THEN 0 ELSE count_upline END) as count_upline,user_name,type_upline')
                ->whereIn('user_name', $user_name)
                ->leftJoinSub($upline_child, 'upline_child', function ($join) {
                    $join->on('customers.user_name', '=', 'upline_child.upline_id');
                })
                ->orderby('count_upline')
                ->orderby('type_upline')
                ->get();
        }

        if (count($data_sponser) <= 0) {
            $data = ['status' => 'success', 'upline' => $user_name, 'type' => 'A', 'rs' => $data_sponser];

            return $data;
        }

        if ($lv == 1) {
            $type = ['A', 'B'];
            $count = count($data_sponser);
            if ($count < 2) {
                //dd('ddd');
                foreach ($data_sponser as $value) {
                    if (($key = array_search($value->type_upline, $type)) !== false) {
                        unset($type[$key]);
                    }
                    // if ($value->type_upline != 'A') {
                    //     $upline = $value->upline_id;

                    //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'A', 'rs' => $value];
                    //     return $data;
                    // } else if ($value->type_upline != 'B') {
                    //     $upline = $value->upline_id;

                    //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'B', 'rs' => $value];
                    //     return $data;
                    // } else if ($value->type_upline != 'C') {
                    //     $upline = $value->upline_id;
                    //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'C', 'rs' => $value];
                    //     return $data;
                    // } else if ($value->type_upline != 'D') {
                    //     $upline = $value->upline_id;
                    //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'D', 'rs' => $value];
                    //     return $data;
                    // } else if ($value->type_upline != 'E') {
                    //     $upline = $value->upline_id;
                    //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'E', 'rs' => $value];
                    //     return $data;
                    // } else {
                    //     $upline = $value->upline_id;
                    //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'A', 'rs' => $value];
                    //     return $data;
                    // }
                }
                $array_key = array_key_first($type);
                $upline =  $user_name;
                $data = ['status' => 'success', 'upline' => $upline, 'type' => $type[$array_key], 'rs' => $value];

                return $data;


                //dd($data_sponser);

            } elseif ($count >= 2) {
                foreach ($data_sponser as $value) {
                    $arr_user_name[] = $value->user_name;
                }

                $data = ['status' => 'fail', 'arr_user_name' => $arr_user_name, 'code' => 'run'];
                return $data;
            } else {

                //$data = ['status' => 'fail', 'ms' => 'ไม่สามารถลงทะเบียนได้กรุณาติดต่อเจ้าหน้าที่', 'user_name' => $data_sponser, 'code' => 'stop'];

                return response()->json(['status' => 'fail', 'ms' => 'CODE:25 ไม่สามารถลงทะเบียนได้กรุณาติดต่อเจ้าหน้าที่']);
                // return $data;
            }
        } else {

            if ($data_sponser[0]->count_upline ==  0) {

                $upline = $data_sponser[0]->user_name;
                $data = ['status' => 'success', 'upline' => $upline, 'type' => 'A', 'rs' => $data_sponser];
                return $data;
            }

            foreach ($data_sponser as $value) {

                if ($value->count_upline < 2) {



                    $data_sponser_ckeck = DB::table('customers')
                        ->select('user_name', 'upline_id', 'type_upline')
                        ->where('upline_id', $value->user_name)
                        ->orderby('type_upline', 'ASC')
                        ->get();


                    $type = ['A', 'B'];


                    foreach ($data_sponser_ckeck as $value_2) {

                        if (($key = array_search($value_2->type_upline, $type)) !== false) {
                            unset($type[$key]);
                        }
                        // if ($value->type_upline != 'A') {
                        //     $upline = $value->upline_id;

                        //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'A', 'rs' => $value];
                        //     return $data;
                        // } else if ($value->type_upline != 'B') {
                        //     $upline = $value->upline_id;
                        //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'B', 'rs' => $value];
                        //     return $data;
                        // } else if ($value->type_upline != 'C') {
                        //     $upline = $value->upline_id;
                        //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'C', 'rs' => $value];
                        //     return $data;
                        // } else if ($value->type_upline != 'D') {
                        //     $upline = $value->upline_id;
                        //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'D', 'rs' => $value];
                        //     return $data;
                        // } else if ($value->type_upline != 'E') {
                        //     $upline = $value->upline_id;
                        //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'E', 'rs' => $value];
                        //     return $data;
                        // } else {
                        //     $upline = $value->upline_id;
                        //     $data = ['status' => 'success', 'upline' => $upline, 'type' => 'A', 'rs' => $value];
                        //     return $data;
                        // }
                    }
                    $array_key = array_key_first($type);

                    $upline =  $value->user_name;
                    $data = ['status' => 'success', 'upline' => $upline, 'type' => $type[$array_key], 'rs' => $data_sponser_ckeck];

                    return $data;

                    // dd($data_sponser);

                }


                if ($lv == 2) {

                    if ($value->type_upline == 'B' and $value->count_upline == 2) {



                        $data_sponser_ckeck = DB::table('customers')
                            ->select('user_name', 'upline_id', 'type_upline')
                            ->wherein('upline_id',  $user_name)
                            ->orderby('type_upline', 'ASC')
                            ->orderby('id', 'ASC')
                            ->get();
                        $l = 0;
                        $user_full = array();
                        foreach ($data_sponser_ckeck as $value) {
                            $l++;
                            $check_auto_plack = NewUpline2ABFunctionController::check_auto_plack($value->user_name);

                            if ($check_auto_plack['status'] == 'success') {
                                return  $check_auto_plack;
                            } else {
                                $user_full[$l] = $value->user_name;
                                // $user_full[$l]['type'] = $check_auto_plack['status'];
                            }
                            $max = 2 ** $lv;
                            if ($l == $max) {
                                $data = ['status' => 'fail', 'arr_user_name' => $user_full, 'code' => 'run'];
                                return $data;
                            }
                        }
                    }
                }
                if ($lv == 3) {

                    if ($value->type_upline == 'B' and $value->count_upline == 2) {



                        $data_sponser_ckeck = DB::table('customers')
                            ->select('user_name', 'upline_id', 'type_upline')
                            ->wherein('upline_id',  $user_name)
                            ->orderby('type_upline', 'ASC')
                            ->orderby('id', 'ASC')
                            ->get();

                        $l = 0;
                        $user_full = array();
                        foreach ($data_sponser_ckeck as $value) {
                            $l++;
                            $check_auto_plack = NewUpline2ABFunctionController::check_auto_plack($value->user_name);

                            if ($check_auto_plack['status'] == 'success') {
                                return  $check_auto_plack;
                            } else {
                                $user_full[$l] = $value->user_name;
                                // $user_full[$l]['type'] = $check_auto_plack['status'];
                            }

                            $max = 2 ** $lv;
                            if ($l == $max) {
                                $data = ['status' => 'fail', 'arr_user_name' => $user_full, 'code' => 'run'];
                                return $data;
                            }
                        }
                    }
                }

                if ($lv == 4) {

                    if ($value->type_upline == 'B' and $value->count_upline == 2) {


                        $data_sponser_ckeck = DB::table('customers')
                            ->select('user_name', 'upline_id', 'type_upline')
                            ->wherein('upline_id',  $user_name)
                            ->orderby('type_upline', 'ASC')
                            ->orderby('id', 'ASC')
                            ->get();

                        $l = 0;
                        $user_full = array();
                        foreach ($data_sponser_ckeck as $value) {
                            $l++;
                            $check_auto_plack = NewUpline2ABFunctionController::check_auto_plack($value->user_name);

                            if ($check_auto_plack['status'] == 'success') {
                                return  $check_auto_plack;
                            } else {
                                $user_full[$l] = $value->user_name;
                                // $user_full[$l]['type'] = $check_auto_plack['status'];
                            }

                            $max = 2 ** $lv;
                            if ($l == $max) {
                                $data = ['status' => 'fail', 'arr_user_name' => $user_full, 'code' => 'run'];
                                return $data;
                            }
                        }
                    }
                }

                if ($lv == 5) {

                    if ($value->type_upline == 'B' and $value->count_upline == 2) {


                        $data_sponser_ckeck = DB::table('customers')
                            ->select('user_name', 'upline_id', 'type_upline')
                            ->wherein('upline_id',  $user_name)
                            ->orderby('type_upline', 'ASC')
                            ->orderby('id', 'ASC')
                            ->get();

                        $l = 0;
                        $user_full = array();
                        foreach ($data_sponser_ckeck as $value) {
                            $l++;
                            $check_auto_plack = NewUpline2ABFunctionController::check_auto_plack($value->user_name);

                            if ($check_auto_plack['status'] == 'success') {
                                return  $check_auto_plack;
                            } else {
                                $user_full[$l] = $value->user_name;
                                // $user_full[$l]['type'] = $check_auto_plack['status'];
                            }

                            $max = 2 ** $lv;
                            if ($l == $max) {
                                $data = ['status' => 'fail', 'arr_user_name' => $user_full, 'code' => 'run'];
                                return $data;
                            }
                        }
                    }
                }

                if ($lv == 6) {

                    if ($value->type_upline == 'B' and $value->count_upline == 2) {


                        $data_sponser_ckeck = DB::table('customers')
                            ->select('user_name', 'upline_id', 'type_upline')
                            ->wherein('upline_id',  $user_name)
                            ->orderby('type_upline', 'ASC')
                            ->orderby('id', 'ASC')
                            ->get();

                        $l = 0;
                        $user_full = array();
                        foreach ($data_sponser_ckeck as $value) {
                            $l++;
                            $check_auto_plack = NewUpline2ABFunctionController::check_auto_plack($value->user_name);

                            if ($check_auto_plack['status'] == 'success') {
                                return  $check_auto_plack;
                            } else {
                                $user_full[$l] = $value->user_name;
                                // $user_full[$l]['type'] = $check_auto_plack['status'];
                            }

                            $max = 2 ** $lv;
                            if ($l == $max) {
                                $data = ['status' => 'fail', 'arr_user_name' => $user_full, 'code' => 'run'];
                                return $data;
                            }
                        }
                    }
                }


                if ($lv == 7) {

                    if ($value->type_upline == 'B' and $value->count_upline == 2) {


                        $data_sponser_ckeck = DB::table('customers')
                            ->select('user_name', 'upline_id', 'type_upline')
                            ->wherein('upline_id',  $user_name)
                            ->orderby('type_upline', 'ASC')
                            ->orderby('id', 'ASC')
                            ->get();

                        $l = 0;
                        $user_full = array();
                        foreach ($data_sponser_ckeck as $value) {
                            $l++;
                            $check_auto_plack = NewUpline2ABFunctionController::check_auto_plack($value->user_name);

                            if ($check_auto_plack['status'] == 'success') {
                                return  $check_auto_plack;
                            } else {
                                $user_full[$l] = $value->user_name;
                                // $user_full[$l]['type'] = $check_auto_plack['status'];
                            }

                            $max = 2 ** $lv;
                            if ($l == $max) {
                                $data = ['status' => 'fail', 'arr_user_name' => $user_full, 'code' => 'run'];
                                return $data;
                            }
                        }
                    }
                }

                if ($lv == 8) {

                    if ($value->type_upline == 'B' and $value->count_upline == 2) {


                        $data_sponser_ckeck = DB::table('customers')
                            ->select('user_name', 'upline_id', 'type_upline')
                            ->wherein('upline_id',  $user_name)
                            ->orderby('type_upline', 'ASC')
                            ->orderby('id', 'ASC')
                            ->get();

                        $l = 0;
                        $user_full = array();
                        foreach ($data_sponser_ckeck as $value) {
                            $l++;
                            $check_auto_plack = NewUpline2ABFunctionController::check_auto_plack($value->user_name);

                            if ($check_auto_plack['status'] == 'success') {
                                return  $check_auto_plack;
                            } else {
                                $user_full[$l] = $value->user_name;
                                // $user_full[$l]['type'] = $check_auto_plack['status'];
                            }

                            $max = 2 ** $lv;
                            if ($l == $max) {
                                $data = ['status' => 'fail', 'arr_user_name' => $user_full, 'code' => 'run'];
                                return $data;
                            }
                        }
                    }
                }


                if ($lv == 9) {

                    if ($value->type_upline == 'B' and $value->count_upline == 2) {


                        $data_sponser_ckeck = DB::table('customers')
                            ->select('user_name', 'upline_id', 'type_upline')
                            ->wherein('upline_id',  $user_name)
                            ->orderby('type_upline', 'ASC')
                            ->orderby('id', 'ASC')
                            ->get();

                        $l = 0;
                        $user_full = array();
                        foreach ($data_sponser_ckeck as $value) {
                            $l++;
                            $check_auto_plack = NewUpline2ABFunctionController::check_auto_plack($value->user_name);

                            if ($check_auto_plack['status'] == 'success') {
                                return  $check_auto_plack;
                            } else {
                                $user_full[$l] = $value->user_name;
                                // $user_full[$l]['type'] = $check_auto_plack['status'];
                            }

                            $max = 2 ** $lv;
                            if ($l == $max) {
                                $data = ['status' => 'fail', 'arr_user_name' => $user_full, 'code' => 'run'];
                                return $data;
                            }
                        }
                    }
                }

                if ($lv == 10) {

                    if ($value->type_upline == 'B' and $value->count_upline == 2) {


                        $data_sponser_ckeck = DB::table('customers')
                            ->select('user_name', 'upline_id', 'type_upline')
                            ->wherein('upline_id',  $user_name)
                            ->orderby('type_upline', 'ASC')
                            ->orderby('id', 'ASC')
                            ->get();

                        $l = 0;
                        $user_full = array();
                        foreach ($data_sponser_ckeck as $value) {
                            $l++;
                            $check_auto_plack = NewUpline2ABFunctionController::check_auto_plack($value->user_name);

                            if ($check_auto_plack['status'] == 'success') {
                                return  $check_auto_plack;
                            } else {
                                $user_full[$l] = $value->user_name;
                                // $user_full[$l]['type'] = $check_auto_plack['status'];
                            }

                            $max = 2 ** $lv;
                            if ($l == $max) {
                                $data = ['status' => 'fail', 'arr_user_name' => $user_full, 'code' => 'run'];
                                return $data;
                            }
                        }
                    }
                }
            }
        }
    }

    public static function check_auto_plack($user_name)
    {
        $data_sponser = DB::table('customers')
            ->select('user_name', 'upline_id', 'type_upline')
            ->where('upline_id', $user_name)
            ->orderby('type_upline', 'ASC')
            ->get();
        if (count($data_sponser) <= 0) {
            $data = ['status' => 'success', 'upline' => $user_name, 'type' => 'A', 'rs' => $data_sponser];
            return $data;
        }

        $type = ['A', 'B'];
        $count = count($data_sponser);
        if ($count < 2) {
            //dd('ddd');
            foreach ($data_sponser as $value) {
                if (($key = array_search($value->type_upline, $type)) !== false) {
                    unset($type[$key]);
                }
            }
            $array_key = array_key_first($type);
            $upline =  $user_name;
            $data = ['status' => 'success', 'upline' => $upline, 'type' => $type[$array_key], 'rs' => $value];
            return $data;
        } else {


            $data = ['status' => 'fail', 'code' => 'run'];
            return $data;
        }
    }
}
