<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\CUser;

use DB;



class ApiFunction2Controller extends Controller
{


    public function storeRegister(Request $request)
    {


        // ตรวจสอบ PV Sponser
        $sponser = CUser::where('user_name', $request->sponser)->first();

        if (empty($sponser)) {
            return response()->json([
                'status' => 'error',
                'code' => 'ER01',
                'data' => null,
                'message' => 'ไม่พบรหัสผู้แนะนำ',
            ]);
        }
        // ตรวจสอบข้อมูลการลงทะเบียน



        $rules = [
            'full_name' => 'required',
            // 'business_name' => 'required',
            'phone' => 'required|numeric|digits:10|unique:customers,phone',
            'sponser' => 'required',
            'password' => 'required|min:4',
        ];

        $messages = [
            'full_name.required' => 'กรุณากรอกชื่อเต็ม',
            'phone.numeric' => 'กรุณากรอกเป็นตัวเลขเท่านั้น',
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'phone.digits' => 'หมายเลขโทรศัพท์ต้องมีความยาว 10 ตัวเลข', // ข้อความเมื่อเบอร์โทรศัพท์ไม่ครบ 10 ตัวเลข
            'phone.unique' => 'หมายเลขโทรศัพท์นี้มีอยู่ในระบบแล้ว', // ข้อความเมื่อเบอร์โทรศัพท์ซ้ำ
            'sponser.required' => 'กรุณากรอกข้อมูลแนะนำ',
            'password.required' => 'กรุณากรอกรหัสผ่านใหม่',
            'password.min' => 'รหัสผ่านใหม่ต้องมีความยาวอย่างน้อย 4 ตัวอักษร',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);


        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'code' => 'ER01',
                'data' => null,
                'message' => 'กรุณากรอกข้อมูให้ครบถ้วนก่อนลงทะเบียน',
                'errors' => $validator->errors(),

            ]);
        }

        try {
            DB::beginTransaction();

            $password = $request->password;
            $user_name = self::gencode_customer();
            $customer_username = DB::table('customers')
                ->where('user_name', $user_name)
                ->count();

            if ($customer_username > 0) {
                $x = 'start';
                $i = 0;
                while ($x == 'start') {
                    $customer_username = DB::table('customers')
                        ->where('user_name', $user_name)
                        ->count();
                    if ($customer_username == 0) {
                        $x = 'stop';
                    } else {
                        $user_name = self::gencode_customer();
                    }
                }
            }


            $request->sponser;


            // if ($request->type_upline and $request->type_upline and ($request->type_upline == 'A' || $request->type_upline == 'B')) {
            //     $data = ['upline_id' => $request->upline_id, 'type' => $request->type_upline];
            // } else {

            //     $data = \App\Http\Controllers\Frontend\FC\UplineController::uplineAB($request->sponser);
            //     if ($data['status'] == 'fail') {
            //         return response()->json(['status' => 'fail', 'ms' => 'ลงทะเบียนไม่สำเร็จไม่สามารถหาสายงาน Upline ได้']);
            //     }
            // }

            // $data_uni = \App\Http\Controllers\Frontend\FC\UnilevelController::uplineAB($request->sponser);
            // if ($data_uni['status'] == 'fail') {
            //     return response()->json(['status' => 'fail', 'ms' => 'ลงทะเบียนไม่สำเร็จไม่สามารถหาสายงานได้']);
            // }



            $customer = [
                'user_name' => $user_name,
                'password' => md5($password),
                'introduce_id' => $request->sponser,
                // 'upline_id' => $data['upline_id'],
                // 'uni_id' => $data_uni['uni_id'],
                // 'type_upline_uni' => $data_uni['type_upline_uni'],
                // 'type_upline' => $data['type_upline'],
                'name' => $request->full_name,
                'business_name' => $request->full_name,
                'phone' => $request->phone,
                'nation_id' => 'ไทย',
                'business_location_id' => 1,
                'qualification_id' => 'MC',
                'email' => $request->email,
                'type_app' => 'app',
            ];

            $new_customer = CUser::create($customer);
            $data_result = [

                'name' => $request->full_name,
                'user_name' => $user_name,
                'password' => $password,
                'phone' => $request->phone,

            ];

            DB::commit();
            return response()->json([
                'status' => 'success',
                'code' => 'success',
                'data' => $data_result,
                'message' => 'success',
            ], 200);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json([
                'status' => 'error',
                'code' => 'ER01',
                'data' => null,
                'message' => 'ลงทะเบียนไม่สำเร็จกรุณาลงทะเบียนไหม่',
                'errors' => $e,
            ]);
        }
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
                            $check_auto_plack = ApiFunction2Controller::check_auto_plack($value->user_name);

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
                            $check_auto_plack = ApiFunction2Controller::check_auto_plack($value->user_name);

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
                            $check_auto_plack = ApiFunction2Controller::check_auto_plack($value->user_name);

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
                            $check_auto_plack = ApiFunction2Controller::check_auto_plack($value->user_name);

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
                            $check_auto_plack = ApiFunction2Controller::check_auto_plack($value->user_name);

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
                            $check_auto_plack = ApiFunction2Controller::check_auto_plack($value->user_name);

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
                            $check_auto_plack = ApiFunction2Controller::check_auto_plack($value->user_name);

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
                            $check_auto_plack = ApiFunction2Controller::check_auto_plack($value->user_name);

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
                            $check_auto_plack = ApiFunction2Controller::check_auto_plack($value->user_name);

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



    public static function gencode_customer()
    {
        $alphabet = '0123456789';
        $user = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 6; $i++) {
            $n = rand(0, $alphaLength);
            $user[] = $alphabet[$n];
        }
        $user = implode($user);
        $user = 'A' . $user;
        return $user;
    }
}
