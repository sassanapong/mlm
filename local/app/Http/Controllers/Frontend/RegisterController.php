<?php

namespace App\Http\Controllers\Frontend;

use App\Customers;
use App\AddressProvince;
use App\CustomersAddressCard;
use App\CustomersAddressDelivery;
use App\CustomersBank;
use App\CustomersBenefit;
use App\Jang_pv;
use App\Log_insurance;
use App\eWallet;
use App\Report_bonus_register;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use DB;

class RegisterController extends Controller
{
    public function index()
    {

        // $data = RegisterController::check_type_register('A758052',1);
        // $i=0;
        // $x = 'start';
        // while ($x == 'start') {
        //     $i++;
        //     if ( $data['status'] == 'fail' and $data['code'] == 'stop') {
        //         $x = 'stop';
        //     }elseif($data['status'] == 'fail' and $data['code'] == 'run'){

        //         $data = RegisterController::check_type_register($data['arr_user_name']);

        //     }else{
        //         $x = 'stop';
        //     }

        // }
        // dd($data,$i);

        // BEGIN  data year   ::: age_min 20 age_max >= 80
        $yeay = date('Y');
        $age_min = 17;
        $yeay_thai = date("Y", strtotime($yeay)) + 543 - $age_min;
        $arr_year = [];
        $age_max = 61;
        for ($i = 1; $i < $age_max; $i++) {
            $arr_year[] = date("Y", strtotime($yeay_thai)) - $i;
        }
        // END  data year   ::: age_min 20 age_max >= 80
        $bank = DB::table('dataset_bank')
            ->get();

        // BEGIN Day
        $day = [];
        for ($i = 1; $i < 32; $i++) {
            $day[] = $i;
        }
        // END Day
        rsort($arr_year);

        $province = AddressProvince::orderBy('province_name', 'ASC')->get();

        $customers_id = Auth::guard('c_user')->user()->id;
        // $customers_up = Auth::guard('c_user')->user()->upline_id;
        // $customers_data = Auth::guard('c_user')->user()->where('user_name', $customers_up)->first();

        return view('frontend/register')
            ->with('day', $day)
            ->with('bank', $bank)
            ->with('arr_year', $arr_year)
            ->with('province', $province);
    }

    public function pv(Request $request)
    {
        $result = DB::table('dataset_qualification')->where('code', $request->val)->first();
        return $result->pv;
    }


    public function store_register(Request $request)
    {
        //dd($request->all());
        //return response()->json(['status' => 'fail', 'ms' => 'ลงทะเบียนไม่สำเร็จกรุณาลงทะเบียนไหม่sss']);


        // เช็ค PV Sponser
        $sponser = Customers::where('user_name', $request->sponser)->first();
        if ($sponser->pv < $request->pv || $request->pv < 20) {
            return response()->json(['pvalert' => 'PV ของท่านไม่เพียงพอ']);
        }

         $pv_register = $request->pv;
        // End PV Sponser

        //BEGIN data validator
        $rule = [
            // BEGIN ข้อมูลส่วนตัว
            'sizebusiness' => 'required',
            'prefix_name' => 'required',
            'name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'business_name' => 'required',
            'id_card' => 'required|min:13|unique:customers',
            'phone' => 'required|numeric',
            'day' => 'required',
            'month' => 'required',
            'year' => 'required',
            'nation_id' => 'required',
            'phone' => 'required|numeric',
            // END ข้อมูลส่วนตัว

            // BEGIN ที่อยู่ตามบัตรประชาชน
            'file_card' => 'required|mimes:jpeg,jpg,png',
            'card_address' => 'required',
            'card_moo' => 'required',
            'card_soi' => 'required',
            'card_road' => 'required',
            'card_province' => 'required',
            'card_district' => 'required',
            'card_tambon' => 'required',
            'card_zipcode' => 'required',
            // END ที่อยู่ตามบัตรประชาชน

            //  BEGIN ที่อยู่จัดส่ง
            'same_address' => 'required',
            'same_moo' => 'required',
            'same_soi' => 'required',
            'same_road' => 'required',
            'same_province' => 'required',
            'same_district' => 'required',
            'same_tambon' => 'required',
            'same_zipcode' => 'required',
            // END ที่อยู่จัดส่ง
        ];
        $message_err = [
            // BEGIN ข้อมูลส่วนตัว
            'sizebusiness.required' => 'กรุณากรอกข้อมูล',
            'prefix_name.required' => 'กรุณากรอกข้อมูล',
            'name.required' => 'กรุณากรอกข้อมูล',
            'last_name.required' => 'กรุณากรอกข้อมูล',
            'gender.required' => 'กรุณากรอกข้อมูล',
            'business_name.required' => 'กรุณากรอกข้อมูล',
            'id_card.required' => 'กรุณากรอกข้อมูล',
            'id_card.min' => 'กรุณากรอกให้ครบ 13 หลัก',
            'id_card.unique' => 'เลขบัตรนี้ถูกใช้งานแล้ว',
            'phone.required' => 'กรุณากรอกข้อมูล',
            'phone.numeric' => 'เป็นตัวเลขเท่านั้น',
            'day.required' => 'กรุณากรอกข้อมูล',
            'month.required' => 'กรุณากรอกข้อมูล',
            'year.required' => 'กรุณากรอกข้อมูล',
            'nation_id.required' => 'กรุณากรอกข้อมูล',
            'id_card.required' => 'กรุณากรอกข้อมูล',
            'phone.required' => 'กรุณากรอกข้อมูล',
            // END ข้อมูลส่วนตัว

            // BEGIN ที่อยู่ตามบัตรประชาชน
            'file_card.required' => 'กรุณากรอกข้อมูล',
            'file_card.mimes' => 'รองรับไฟล์นามสกุล jpeg,jpg,png เท่านั้น',
            'card_address.required' => 'กรุณากรอกข้อมูล',
            'card_moo.required' => 'กรุณากรอกข้อมูล',
            'card_soi.required' => 'กรุณากรอกข้อมูล',
            'card_road.required' => 'กรุณากรอกข้อมูล',
            'card_province.required' => 'กรุณากรอกข้อมูล',
            'card_district.required' => 'กรุณากรอกข้อมูล',
            'card_tambon.required' => 'กรุณากรอกข้อมูล',
            'card_zipcode.required' => 'กรุณากรอกข้อมูล',
            // END ที่อยู่ตามบัตรประชาชน

            // BEGIN ที่อยู่จัดส่ง
            'same_address.required' => 'กรุณากรอกข้อมูล',
            'same_moo.required' => 'กรุณากรอกข้อมูล',
            'same_soi.required' => 'กรุณากรอกข้อมูล',
            'same_road.required' => 'กรุณากรอกข้อมูล',
            'same_province.required' => 'กรุณากรอกข้อมูล',
            'same_district.required' => 'กรุณากรอกข้อมูล',
            'same_tambon.required' => 'กรุณากรอกข้อมูล',
            'same_zipcode.required' => 'กรุณากรอกข้อมูล',
            // END ที่อยู่จัดส่ง

        ];


        if ($request->file_bank) {

            $rule['file_bank'] = 'mimes:jpeg,jpg,png';
            $message_err['file_bank.mimes'] = 'รองรับไฟล์นามสกุล jpeg,jpg,png เท่านั้น';

            $rule['bank_name'] = 'required';
            $message_err['bank_name.required'] = 'กรุณากรอกข้อมูล';

            $rule['bank_branch'] = 'required';
            $message_err['bank_branch.required'] = 'กรุณากรอกข้อมูล';

            $rule['bank_no'] = 'required|numeric';
            $message_err['bank_no.required'] = 'กรุณากรอกข้อมูล';
            $message_err['bank_no.numeric'] = 'ใส่เฉพาะตัวเลขเท่านั้น';

            $rule['account_name'] = 'required';
            $message_err['account_name.required'] = 'กรุณากรอกข้อมูล';
        }

        if ($request->name_benefit) {
            $rule['last_name_benefit'] = 'required';
            $message_err['last_name_benefit.required'] = 'กรุณากรอกข้อมูล';
            $rule['involved'] = 'required';
            $message_err['involved.required'] = 'กรุณากรอกข้อมูล';
        }

        $validator = Validator::make(
            $request->all(),
            $rule,
            $message_err
        );
        //END data validator


        if (!$validator->fails()) {
            //BEGIN วันเกิด
            $day = $request->day;
            $month = $request->month;
            $year = $request->year;

            $YMD = $year . "-" . $month . "-" . $day;

            $birth_day = date('Y-m-d', strtotime($YMD));
            // END วันเกิด
            $password = substr($request->id_card, -4);

            // BEGIN generatorusername เอา 7 หลัก
            // $user_name = Auth::guard('c_user')->user()->count();
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

            // END generatorusername เอา 7 หลัก

            $request->sponser;

            $data = RegisterController::check_type_register($request->sponser, 1);


            $i = 1;
            $x = 'start';

            while ($x == 'start') {
                $i++;
                if ($data['status'] == 'fail' and $data['code'] == 'stop') {

                    $x = 'stop';
                    return response()->json(['status' => 'fail', 'ms' => $data['ms']]);

                    return response()->json(['ms' => $data['ms'], 'status' => 'fail']);
                } elseif ($data['status'] == 'fail' and $data['code'] == 'run') {

                    $data = RegisterController::check_type_register($data['arr_user_name'], $i);
                } else {
                    $x = 'stop';
                }
            }

            $start_month = date('Y-m-d');
            $mt_mount_new = strtotime("+33 Day", strtotime($start_month));

            if($request->sizebusiness == 'VVIP' || $request->sizebusiness == 'VIP' || $request->sizebusiness == 'MO'){

                $insurance_date =date('Y-m-d',strtotime("+1 years", strtotime($start_month)));
                $log_insurance_data = [
                    'user_name' => $user_name,
                    'old_exprie_date' => null,
                    'new_exprie_date' => $insurance_date,
                    'position' => $request->sizebusiness,
                    'pv'=>$request->pv,
                    'status' => 'success',
                    'type' => 'register',
                ];
                Log_insurance::create($log_insurance_data);

            }else{
                $insurance_date = null;
            }



            $customer = [
                'user_name' => $user_name,
                'expire_date' => date('Y-m-d', $mt_mount_new),
                'expire_insurance_date'=>$insurance_date,
                'password' => md5($password),
                'upline_id' => $data['upline'],
                'pv_upgrad' =>$request->pv,
                'introduce_id' => $request->sponser,
                'type_upline' => $data['type'],
                'prefix_name' => $request->prefix_name,
                'name' => $request->name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'business_name' => $request->business_name,
                'id_card' => $request->id_card,
                'phone' => $request->phone,
                'birth_day' => $birth_day,
                'nation_id' => 'ไทย',
                'business_location_id' => $request->nation_id,
                'qualification_id' => $request->sizebusiness,
                'id_card' => $request->id_card,
                'phone' => $request->phone,
                'email' => $request->email,
                'line_id' => $request->line_id,
                'vvip_register_type' => 'register',

                'facebook' => $request->facebook,
                'regis_doc4_status' => 0,
                'regis_doc1_status' => 3,
            ];


            $customer_username = $request->sponser;
            $arr_user = array();
            $report_bonus_register = array();


            $code_bonus =  \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_bonus(2);


            for ($i = 1; $i <= 10; $i++) {
                $x = 'start';
                $data_user =  DB::table('customers')
                    ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
                    // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                    ->where('user_name', '=', $customer_username)
                    ->first();
                if ($i == 1) {
                    $name_g1 = $data_user->name . ' ' . $data_user->last_name;
                }
                // dd($customer_username);

                if (empty($data_user)) {
                    //$rs = Report_bonus_register::insert($report_bonus_register);

                } else {
                    while ($x = 'start') {
                        if (empty($data_user->name)) {

                            $data_user =  DB::table('customers')
                                ->select('customers.name', 'customers.last_name', 'customers.user_name', 'customers.introduce_id', 'customers.qualification_id', 'customers.expire_date')
                                // ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=','customers.qualification_id')
                                ->where('user_name', '=', $customer_username)
                                ->first();

                            $customer_username = $data_user->introduce_id;
                        } else {

                            if ($data_user->qualification_id == '' || $data_user->qualification_id == null || $data_user->qualification_id == '-') {
                                $qualification_id = 'MB';
                            } else {
                                $qualification_id = $data_user->qualification_id;
                            }

                            $report_bonus_register[$i]['user_name'] = $request->sponser;
                            $report_bonus_register[$i]['name'] = $name_g1;
                            $report_bonus_register[$i]['regis_user_name'] = $user_name;
                            $report_bonus_register[$i]['regis_user_introduce_id'] = $customer_username;
                            $report_bonus_register[$i]['regis_name'] = $request->name . ' ' . $request->last_name;
                            $report_bonus_register[$i]['user_name_g'] = $data_user->user_name;
                            $report_bonus_register[$i]['name_g'] = $data_user->name . ' ' . $data_user->last_name;
                            $report_bonus_register[$i]['qualification'] = $qualification_id;
                            $report_bonus_register[$i]['g'] = $i;
                            $report_bonus_register[$i]['pv'] = $pv_register;
                            $report_bonus_register[$i]['code_bonus'] = $code_bonus;
                            $report_bonus_register[$i]['type'] = 'register';
                            $arr_user[$i]['user_name'] = $data_user->user_name;
                            $arr_user[$i]['lv'] = [$i];
                            if ($i == 1) {
                                $report_bonus_register[$i]['percen'] = 250;

                                $arr_user[$i]['pv'] = $pv_register;
                                $arr_user[$i]['position'] = $qualification_id;
                                $wallet_total = $pv_register * 250 / 100;
                                $arr_user[$i]['bonus'] = $wallet_total;
                                $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                            } elseif ($i == 2) {
                                $report_bonus_register[$i]['percen'] = 20;
                                $arr_user[$i]['pv'] = $pv_register;
                                $arr_user[$i]['position'] = $qualification_id;
                                if ($qualification_id == 'MB') {
                                    $report_bonus_register[$i]['bonus'] = 0;
                                    $arr_user[$i]['bonus'] = 0;
                                } else {

                                    $wallet_total = $pv_register * 20 / 100;
                                    $arr_user[$i]['bonus'] = $wallet_total;
                                    $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                    $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                    $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                                }
                            } elseif ($i == 3) {
                                $report_bonus_register[$i]['percen'] = 10;
                                $arr_user[$i]['pv'] = $pv_register;
                                $arr_user[$i]['position'] = $qualification_id;
                                if ($qualification_id == 'MB') {
                                    $report_bonus_register[$i]['tax_total'] = 0;
                                    $report_bonus_register[$i]['bonus_full'] = 0;
                                    $report_bonus_register[$i]['bonus'] = 0;
                                    $arr_user[$i]['bonus'] = 0;
                                } else {

                                    $wallet_total = $pv_register * 10 / 100;
                                    $arr_user[$i]['bonus'] = $wallet_total;
                                    $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                    $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                    $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                                }
                            } elseif ($i == 4) {
                                $report_bonus_register[$i]['percen'] = 5;
                                $arr_user[$i]['pv'] = $pv_register;
                                $arr_user[$i]['position'] = $qualification_id;

                                if ($qualification_id == 'MB' || $qualification_id == 'MO') {
                                    $report_bonus_register[$i]['tax_total'] = 0;
                                    $report_bonus_register[$i]['bonus_full'] = 0;
                                    $report_bonus_register[$i]['bonus'] = 0;
                                    $arr_user[$i]['bonus'] = 0;
                                } else {

                                    $wallet_total = $pv_register * 5 / 100;
                                    $arr_user[$i]['bonus'] = $wallet_total;
                                    $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                    $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                    $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                                }
                            } elseif ($i >= 5 and $i <= 10) {
                                $report_bonus_register[$i]['percen'] = 5;
                                $arr_user[$i]['pv'] = $pv_register;
                                $arr_user[$i]['position'] = $qualification_id;

                                if (($i == 5 || $i == 6 || $i == 7) and $qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP') {
                                    $report_bonus_register[$i]['tax_total'] = 0;
                                    $report_bonus_register[$i]['bonus_full'] = 0;
                                    $report_bonus_register[$i]['bonus'] = 0;
                                    $arr_user[$i]['bonus'] = 0;
                                } elseif (($i == 8 || $i == 9 || $i == 10) and $qualification_id == 'MB' || $qualification_id == 'MO' || $qualification_id == 'VIP' || $qualification_id == 'VVIP') {

                                    $report_bonus_register[$i]['tax_total'] = 0;
                                    $report_bonus_register[$i]['bonus_full'] = 0;
                                    $report_bonus_register[$i]['bonus'] = 0;
                                    $arr_user[$i]['bonus'] = 0;
                                } else {
                                    $wallet_total = $pv_register * 5 / 100;
                                    $arr_user[$i]['bonus'] = $wallet_total;
                                    $report_bonus_register[$i]['tax_total'] = $wallet_total * 3 / 100;
                                    $report_bonus_register[$i]['bonus_full'] = $wallet_total;
                                    $report_bonus_register[$i]['bonus'] = $wallet_total - $wallet_total * 3 / 100;
                                }
                            }

                            $customer_username = $data_user->introduce_id;
                            $x = 'stop';
                            break;
                        }
                    }
                }
            }


            try {
                DB::BeginTransaction();
                foreach ($report_bonus_register as $value) {
                    DB::table('report_bonus_register')
                        ->updateOrInsert(
                            ['user_name' => $value['user_name'], 'regis_user_name' => $value['regis_user_name'], 'g' => $value['g'], 'type' => $value['type']],
                            $value
                        );
                }



                // หัก PV Sponser
                $sponser = Customers::where('user_name', $request->sponser)->first();
                // End PV Sponser

                $insert_customer = Customers::create($customer);


                $code =   \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_pv();


                $jang_pv = [
                    'code' => $code,
                    'customer_username' => $sponser->user_name,
                    'to_customer_username' => $user_name,
                    'position' => $request->sizebusiness,
                    'pv_old' => $sponser->pv,
                    'pv' => $pv_register,
                    'pv_balance' => $sponser->pv - $pv_register,
                    'type' => '4',
                    'status' => 'Success'
                ];

                $sponser->pv = $sponser->pv - $pv_register;
                $sponser->save();

                $insert_jangpv = Jang_pv::create($jang_pv);


                if ($request->file_card) {

                    $url = 'local/public/images/customers_card/' . date('Ym');
                    $imageName = $request->file_card->extension();
                    $filenametostore =  date("YmdHis") . '.' . $insert_customer->id . "." . $imageName;
                    $request->file_card->move($url,  $filenametostore);

                    $CustomersAddressCard = [
                        'customers_id' => $insert_customer->id,
                        'user_name' => $user_name,
                        'url' => $url,
                        'img_card' => $filenametostore,
                        'address' => $request->card_address,
                        'moo' => $request->card_moo,
                        'soi' => $request->card_soi,
                        'road' => $request->card_road,
                        'tambon' => $request->card_tambon,
                        'district' => $request->card_district,
                        'province' => $request->card_province,
                        'zipcode' => $request->card_zipcode,
                        'phone' => $request->card_phone,
                    ];

                    // $query_address_card = CustomersAddressCard::create($CustomersAddressCard);
                    $query_address_card = CustomersAddressCard::updateOrInsert([
                        'customers_id' => $insert_customer->id
                    ], $CustomersAddressCard);

                    //END ข้อมูล บัตรประชาชน

                    //BEGIN สถานะว่า เอาข้อมูลมาจากไหน 1= บปช , 2= กรอกมาเอง
                    if ($request->status_address) {
                        $status_address = 1;
                    } else {
                        $status_address = 2;
                    }
                    //END สถานะว่า เอาข้อมูลมาจากไหน 1= บปช , 2= กรอกมาเอง
                    // BEGIN ที่อยู่ในการจัดส่ง
                    $CustomersAddressDelivery = [
                        'customers_id' => $insert_customer->id,
                        'user_name' => $user_name,
                        'address' => $request->same_address,
                        'moo' => $request->same_moo,
                        'soi' => $request->same_soi,
                        'road' => $request->same_road,
                        'tambon' => $request->same_tambon,
                        'district' => $request->same_district,
                        'province' => $request->same_province,
                        'zipcode' => $request->same_zipcode,
                        'phone' => $request->same_phone,
                        'status' => $status_address,
                    ];

                    $query_address_delivery = CustomersAddressDelivery::updateOrInsert([
                        'customers_id' => $insert_customer->id
                    ], $CustomersAddressDelivery);
                    // $query_address_delivery = CustomersAddressDelivery::create($CustomersAddressDelivery);
                    // END ที่อยู่ในการจัดส่ง

                    // BEGIN ข้อมูลธนาคาร
                    if ($request->file_bank) {

                        $url = 'local/public/images/customers_bank/' . date('Ym');
                        $imageName = $request->file_bank->extension();
                        $filenametostore =  date("YmdHis") . '.' . $insert_customer->id . "." . $imageName;
                        $request->file_bank->move($url,  $filenametostore);

                        $bank = DB::table('dataset_bank')
                            ->where('id', '=', $request->bank_name)
                            ->first();

                        $CustomersBank = [
                            'customers_id' => $insert_customer->id,
                            'user_name' => $user_name,
                            'url' => $url,
                            'img_bank' => $filenametostore,
                            'bank_name' => $bank->name,
                            'bank_id_fk' => $bank->id,
                            'code_bank' => $bank->code,
                            'bank_branch' => $request->bank_branch,
                            'bank_no' => $request->bank_no,
                            'account_name' => $request->account_name
                            // 'regis_doc4_status' => 3
                        ];



                        $rquery_bamk = CustomersBank::updateOrInsert([
                            'customers_id' => $insert_customer->id
                        ], $CustomersBank);

                        // $rquery_bamk = CustomersBank::create($CustomersBank);

                        Customers::where('id', $insert_customer->id)->update(['regis_doc4_status' => 3]);
                    }
                    // END ข้อมูลธนาคาร


                    // BEGIN  ผู้รับผลประโยชน์
                    if ($request->name_benefit) {

                        $CustomersBenefit = [
                            'customers_id' => $insert_customer->id,
                            'user_name' => $user_name,
                            'name' => $request->name_benefit,
                            'last_name' => $request->last_name_benefit,
                            'involved' => $request->involved,
                        ];

                        $qurey_customers_benefit = CustomersBenefit::create($CustomersBenefit);
                    }
                    // END  ผู้รับผลประโยชน์

                    $data_result = [
                        'prefix_name' => $request->prefix_name,
                        'name' => $request->name,
                        'last_name' => $request->last_name,
                        'business_name' => $request->business_name,
                        'user_name' => $user_name,
                        'password' => $password,
                    ];


                    $report_bonus_register = DB::table('report_bonus_register')
                        ->where('status', '=', 'panding')
                        ->where('bonus', '>', 0)
                        ->where('code_bonus', '=', $code_bonus)
                        ->where('regis_user_name', '=', $user_name)
                        ->get();

                    foreach ($report_bonus_register as $value) {


                        if ($value->bonus > 0) {


                            $wallet_g = DB::table('customers')
                                ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'bonus_total')
                                ->where('user_name', $value->user_name_g)
                                ->first();

                            if ($wallet_g->ewallet == '' || empty($wallet_g->ewallet)) {
                                $wallet_g_user = 0;
                            } else {

                                $wallet_g_user = $wallet_g->ewallet;
                            }

                            if ($wallet_g->bonus_total == '' || empty($wallet_g->bonus_total)) {
                                $bonus_total = 0 + $value->bonus;
                            } else {

                                $bonus_total = $wallet_g->bonus_total + $value->bonus;
                            }

                            if ($wallet_g->ewallet_use == '' || empty($wallet_g->ewallet_use)) {
                                $ewallet_use = 0;
                            } else {

                                $ewallet_use = $wallet_g->ewallet_use;
                            }
                            $eWallet_register = new eWallet();
                            $wallet_g_total = $wallet_g_user + $value->bonus;
                            $ewallet_use_total =  $ewallet_use + $value->bonus;

                            $eWallet_register->transaction_code = $code_bonus;
                            $eWallet_register->customers_id_fk = $wallet_g->id;
                            $eWallet_register->customer_username = $value->user_name_g;
                            // $eWallet_register->customers_id_receive = $user->id;
                            // $eWallet_register->customers_name_receive = $user->user_name;
                            $eWallet_register->tax_total = $value->tax_total;
                            $eWallet_register->bonus_full = $value->bonus_full;
                            $eWallet_register->amt = $value->bonus;
                            $eWallet_register->old_balance = $wallet_g_user;
                            $eWallet_register->balance = $wallet_g_total;
                            $eWallet_register->type = 10;
                            $eWallet_register->note_orther = 'โบนัสขยายธุรกิจ รหัส ' . $value->user_name . 'แนะนำรหัส ' . $value->regis_user_name;
                            $eWallet_register->receive_date = now();
                            $eWallet_register->receive_time = now();
                            $eWallet_register->status = 2;

                            DB::table('customers')
                                ->where('user_name', $value->user_name_g)
                                ->update(['ewallet' => $wallet_g_total, 'ewallet_use' => $ewallet_use_total, 'bonus_total' => $bonus_total]);

                            DB::table('report_bonus_register')
                                ->where('user_name_g',  $value->user_name_g)
                                ->where('code_bonus', '=', $code_bonus)
                                ->where('regis_user_name', '=', $value->regis_user_name)
                                ->where('g', '=', $value->g)
                                ->update(['status' => 'success']);

                            $eWallet_register->save();
                        } else {
                            DB::table('report_bonus_register')
                                ->where('user_name_g',  $value->user_name_g)
                                ->where('code_bonus', '=', $code_bonus)
                                ->where('regis_user_name', '=', $value->regis_user_name)
                                ->where('g', '=', $value->g)
                                ->update(['status' => 'success']);
                        }
                    }


                    if ($request->sizebusiness == 'VVIP') {
                        $upline_pv = \App\Http\Controllers\Frontend\FC\PvUpPositionXvvipController::get_pv_upgrade($request->sponser);//โบนัสสร้างทีม XVVIP

                        $data_user_uoposition =  DB::table('customers')
                            ->select(
                                'customers.name',
                                'customers.last_name',
                                'bonus_total',
                                'customers.user_name',
                                'customers.upline_id',
                                'customers.introduce_id',
                                'customers.qualification_id',
                                'customers.expire_date',
                                'dataset_qualification.id as qualification_id_fk'
                            )
                            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                            ->where('user_name', '=', $request->sponser)
                            // ->where('dataset_qualification.id', '=', 6)// 4 - 7
                            ->first();
                        // $data_user =  DB::table('customers')
                        //     ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        //     ->where('customers.introduce_id', '=', '1384810')
                        //     ->where('dataset_qualification.id', '=', 4)
                        //     ->count();//
                        $position =  $data_user_uoposition->qualification_id;

                        // dd($data_user_1,$data_user);


                        // dd($data_user_1);
                        //ขึ้น XVVIP แนะนำ 2 VVIP คะแนน 0ว


                            $data_user =  DB::table('customers')
                                ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                                ->where('customers.introduce_id', '=', $data_user_uoposition->user_name)
                                ->where('dataset_qualification.id', '=', 4)
                                ->count(); //
                            // dd($data_user);
                            // dd($data_user,$data_user_uoposition->qualification_id,$data_user_uoposition->qualification_id_fk);
                            //$data_user >= 2 and $data_user_uoposition->qualification_id != 'XVVIP' and  $data_user_uoposition->qualification_id_fk< 5
                            if ($data_user_uoposition->qualification_id_fk == 9) { //MD
                                $data_svvip =  DB::table('customers')
                                    ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                                    ->where('customers.introduce_id', '=', $data_user_uoposition->user_name)
                                    ->where('dataset_qualification.id', '=', 6)
                                    ->count();
                                if ($data_svvip >= 21 and $upline_pv >= 200000 and $data_user_uoposition->bonus_total >= 3000000) {


                                    $update_position = DB::table('customers')
                                        ->where('user_name', $data_user_uoposition->user_name)
                                        ->update(['qualification_id' => 'MD']);
                                    $position =  'MD';
                                    DB::table('log_up_vl')->insert([
                                        'user_name' => $data_user_uoposition->user_name,'introduce_id' => $data_user_uoposition->introduce_id, 'bonus_total' => $data_user_uoposition->bonus_total,
                                        'old_lavel' => $data_user->code, 'new_lavel' => 'MD', 'vvip' => $data_user, 'svvip' => $data_svvip, 'status' => 'success'
                                    ]);
                                }
                            }

                            if ($data_user_uoposition->qualification_id_fk == 8) {
                                $data_svvip =  DB::table('customers')
                                    ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                                    ->where('customers.introduce_id', '=', $data_user_uoposition->user_name)
                                    ->where('dataset_qualification.id', '=', 6)
                                    ->count();
                                if ($data_svvip >= 13 and $upline_pv >= 180000 and $data_user_uoposition->bonus_total >= 2000000) {

                                    $update_position = DB::table('customers')
                                        ->where('user_name', $data_user_uoposition->user_name)
                                        ->update(['qualification_id' => 'ME']);
                                        $position =  'ME';
                                    DB::table('log_up_vl')->insert([
                                        'user_name' => $data_user_uoposition->user_name,'introduce_id' => $data_user_uoposition->introduce_id, 'bonus_total' => $data_user_uoposition->bonus_total,
                                        'old_lavel' => $data_user_uoposition->qualification_id, 'new_lavel' => 'ME', 'vvip' => $data_user, 'svvip' => $data_svvip, 'status' => 'success'
                                    ]);
                                }
                            }

                            if ($data_user_uoposition->qualification_id_fk == 7) {
                                $data_svvip =  DB::table('customers')
                                    ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                                    ->where('customers.introduce_id', '=', $data_user_uoposition->user_name)
                                    ->where('dataset_qualification.id', '=', 6)
                                    ->count();
                                if ($data_svvip >= 7 and $upline_pv >= 120000 and $data_user_uoposition->bonus_total >= 1000000) {


                                    $update_position = DB::table('customers')
                                        ->where('user_name', $data_user_uoposition->user_name)
                                        ->update(['qualification_id' => 'MR']);
                                        $position =  'MR';
                                    DB::table('log_up_vl')->insert([
                                        'user_name' => $data_user_uoposition->user_name,'introduce_id' => $data_user_uoposition->introduce_id, 'bonus_total' => $data_user_uoposition->bonus_total,
                                        'old_lavel' => $data_user_uoposition->qualification_id, 'new_lavel' => 'MR', 'vvip' => $data_user, 'svvip' => $data_svvip, 'status' => 'success'
                                    ]);
                                }
                            }

                            if ($data_user_uoposition->qualification_id_fk == 6) {
                                $data_svvip =  DB::table('customers')
                                    ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                                    ->where('customers.introduce_id', '=', $data_user_uoposition->user_name)
                                    ->where('dataset_qualification.id', '=', 6)
                                    ->count();
                                if ($data_svvip >= 3 and  $upline_pv >= 72000 and $data_user_uoposition->bonus_total >= 400000  ) {


                                    $update_position = DB::table('customers')
                                        ->where('user_name', $data_user_uoposition->user_name)
                                        ->update(['qualification_id' => 'MG']);
                                        $position =  'MG';
                                    DB::table('log_up_vl')->insert([
                                        'user_name' => $data_user_uoposition->user_name,'introduce_id' => $data_user_uoposition->introduce_id, 'bonus_total' => $data_user_uoposition->bonus_total,
                                        'old_lavel' => $data_user_uoposition->qualification_id, 'new_lavel' => 'MG', 'vvip' => $data_user, 'svvip' => $data_svvip, 'status' => 'success'
                                    ]);
                                }
                            }

                            if ($data_user_uoposition->qualification_id_fk == 5 and $upline_pv >= 48000 and $data_user_uoposition->bonus_total >= 100000) {

                                $update_position = DB::table('customers')
                                    ->where('user_name', $data_user_uoposition->user_name)
                                    ->update(['qualification_id' => 'SVVIP']);
                                    $position =  'SVVIP';


                                DB::table('log_up_vl')->insert([
                                    'user_name' => $data_user_uoposition->user_name,'introduce_id' => $data_user_uoposition->introduce_id, 'bonus_total' => $data_user_uoposition->bonus_total,
                                    'old_lavel' => $data_user_uoposition->qualification_id, 'new_lavel' => 'SVVIP', 'vvip' => $data_user, 'status' => 'success'
                                ]);
                            }



                            if ($upline_pv >= 2400  and  $data_user_uoposition->qualification_id_fk == 4) {

                                $update_position = DB::table('customers')
                                    ->where('user_name', $data_user_uoposition->user_name)
                                    ->update(['qualification_id' => 'XVVIP']);
                                    $position =  'XVVIP';
                                DB::table('log_up_vl')->insert([
                                    'user_name' => $data_user_uoposition->user_name,'introduce_id' => $data_user_uoposition->introduce_id, 'old_lavel' => $data_user_uoposition->qualification_id,
                                    'new_lavel' => 'XVVIP', 'bonus_total' => $data_user_uoposition->bonus_total, 'vvip' => $data_user, 'status' => 'success'
                                ]);
                            }


                        $data_user_bonus_4 =  DB::table('customers')
                            ->select(
                                'customers.id',
                                'customers.name',
                                'customers.last_name',
                                'bonus_total',
                                'customers.user_name',
                                'customers.upline_id',
                                'customers.introduce_id',
                                'customers.qualification_id',
                                'dataset_qualification.id as qualification_id_fk'
                            )
                            ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                            ->where('customers.introduce_id', '=', $request->sponser)
                            ->where('dataset_qualification.id', '=', 4)
                            ->where('customers.vvip_register_type', '=', 'register')
                            ->where('customers.vvip_status_runbonus', '=', 'panding')
                            ->limit(2)
                            ->get();

                        if (count($data_user_bonus_4) >= 2 and ($position == 'XVVIP' || $position == 'SVVIP'
                            || $position == 'MG' || $position == 'MR' || $position == 'ME' || $position == 'MD')) {

                            $f = 0;
                            foreach ($data_user_bonus_4 as $value_bonus_4) {
                                $user_runbonus[] = $value_bonus_4->user_name;
                                $f++;
                                DB::table('customers')
                                ->where('user_name', $value_bonus_4->user_name)
                                ->update(['vvip_status_runbonus' => 'success']);
                                if ($f == 2) {

                                    $code_b4 =    $code_bonus = \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_bonus(4);


                                    $data_user_bonus4 = DB::table('customers')
                                        ->select('id', 'upline_id', 'user_name', 'introduce_id', 'name', 'last_name', 'qualification_id')
                                        ->where('user_name', '=', $data_user_uoposition->introduce_id)
                                        ->first();
                                    if (
                                        $data_user_bonus4->qualification_id == 'XVVIP' || $data_user_bonus4->qualification_id == 'SVVIP' || $data_user_bonus4->qualification_id == 'MG'
                                        || $data_user_bonus4->qualification_id == 'MR' || $data_user_bonus4->qualification_id == 'ME' || $data_user_bonus4->qualification_id == 'MD'
                                    ) {
                                        $report_bonus_register_b4['user_name'] = $request->sponser;
                                        $introduce_id =  DB::table('customers')
                                        ->select('introduce_id')
                                        ->where('user_name', '=',$request->sponser)
                                        ->first();
                                        $report_bonus_register_b4['introduce_id'] = $introduce_id->introduce_id;
                                        $report_bonus_register_b4['name'] = $name_g1;
                                        $report_bonus_register_b4['regis_user_name'] = $user_name;
                                        $report_bonus_register_b4['regis_user_introduce_id'] = $request->sponser;
                                        $report_bonus_register_b4['regis_name'] = $request->name . ' ' . $request->last_name;
                                        $report_bonus_register_b4['user_upgrad'] = $data_user_uoposition->user_name;
                                        $report_bonus_register_b4['user_name_recive_bonus'] = $data_user_bonus4->user_name;
                                        $report_bonus_register_b4['name_recive_bonus'] =  $data_user_bonus4->name . ' ' . $data_user_bonus4->last_name;
                                        $report_bonus_register_b4['old_position'] = '';
                                        $report_bonus_register_b4['new_position'] = '';
                                        $report_bonus_register_b4['code_bonus'] = $code_b4;
                                        $report_bonus_register_b4['type'] = 'register';
                                        $report_bonus_register_b4['user_name_vvip_1'] = $user_runbonus[0];
                                        $report_bonus_register_b4['user_name_vvip_2'] = $user_runbonus[1];
                                        $report_bonus_register_b4['tax_total'] =  2000 * 3 / 100;
                                        $report_bonus_register_b4['bonus_full'] = 2000;
                                        $report_bonus_register_b4['bonus'] =  2000 - (2000 * 3 / 100);
                                        $report_bonus_register_b4['pv_vvip_1'] =  '1200';
                                        $report_bonus_register_b4['pv_vvip_2'] =  '1200';


                                        DB::table('report_bonus_register_xvvip')
                                            ->updateOrInsert(
                                                ['regis_user_name' =>  $user_name, 'user_name' => $request->sponser],
                                                $report_bonus_register_b4
                                            );

                                        $report_bonus_register_xvvip = DB::table('report_bonus_register_xvvip')
                                            ->where('status', '=', 'panding')
                                            ->where('bonus', '>', 0)
                                            ->where('code_bonus', '=', $code_b4)
                                            ->where('regis_user_name', '=', $user_name)
                                            ->first();



                                        $wallet_b4 = DB::table('customers')
                                            ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'bonus_total')
                                            ->where('user_name', $report_bonus_register_xvvip->user_name_recive_bonus)
                                            ->first();

                                        if ($wallet_b4->ewallet == '' || empty($wallet_b4->ewallet)) {
                                            $wallet_b4_user = 0;
                                        } else {

                                            $wallet_b4_user = $wallet_b4->ewallet;
                                        }

                                        if ($wallet_b4->bonus_total == '' || empty($wallet_b4->bonus_total)) {
                                            $bonus_total_b4 = 0 + $report_bonus_register_xvvip->bonus;
                                        } else {

                                            $bonus_total_b4 = $wallet_b4->bonus_total + $report_bonus_register_xvvip->bonus;
                                        }

                                        if ($wallet_g->ewallet_use == '' || empty($wallet_g->ewallet_use)) {
                                            $ewallet_use_b4 = 0;
                                        } else {

                                            $ewallet_use_b4 = $wallet_g->ewallet_use;
                                        }
                                        $eWallet_register_b4 = new eWallet();
                                        $wallet_total_b4 = $wallet_b4_user +  $report_bonus_register_xvvip->bonus;
                                        $ewallet_use_total_b4 =  $ewallet_use_b4 + $report_bonus_register_xvvip->bonus;

                                        $eWallet_register_b4->transaction_code =  $report_bonus_register_xvvip->code_bonus;
                                        $eWallet_register_b4->customers_id_fk = $data_user_bonus4->id;
                                        $eWallet_register_b4->customer_username = $report_bonus_register_xvvip->user_name_recive_bonus;
                                        // $eWallet_register_b4->customers_id_receive = $user->id;
                                        // $eWallet_register_b4->customers_name_receive = $user->user_name;
                                        $eWallet_register_b4->tax_total = $report_bonus_register_xvvip->tax_total;
                                        $eWallet_register_b4->bonus_full = $report_bonus_register_xvvip->bonus_full;
                                        $eWallet_register_b4->amt = $report_bonus_register_xvvip->bonus;
                                        $eWallet_register_b4->old_balance = $wallet_b4_user;
                                        $eWallet_register_b4->balance = $wallet_total_b4;
                                        $eWallet_register_b4->type = 11;
                                        $eWallet_register_b4->note_orther = 'โบนัสสร้างทีม รหัส ' . $user_runbonus[0] . ' และรหัส ' . $user_runbonus[1] . ' สมัครสมาชิก VVIP';
                                        $eWallet_register_b4->receive_date = now();
                                        $eWallet_register_b4->receive_time = now();
                                        $eWallet_register_b4->status = 2;

                                        DB::table('customers')
                                            ->where('user_name', $data_user_bonus4->user_name)
                                            ->update(['ewallet' => $wallet_total_b4, 'ewallet_use' => $ewallet_use_total_b4, 'bonus_total' => $bonus_total_b4]);

                                        DB::table('report_bonus_register_xvvip')
                                            ->where('code_bonus', '=', $report_bonus_register_xvvip->code_bonus)
                                            ->where('regis_user_name', '=', $report_bonus_register_xvvip->regis_user_name)
                                            ->update(['status' => 'success']);

                                        $eWallet_register_b4->save();

                                        DB::table('report_bonus_register_xvvip')
                                            ->where('code_bonus', '=', $report_bonus_register_xvvip->code_bonus)
                                            ->where('regis_user_name', '=', $report_bonus_register_xvvip->regis_user_name)
                                            ->update(['status' => 'success']);

                                        $eWallet_register_b4->save();
                                    }
                                }
                            }

                        }
                    }

                    //คำนวนตำแหน่งไหม่


                    DB::commit();
                    return response()->json(['status' => 'success', 'data_result' => $data_result], 200);
                }
            } catch (Exception $e) {
                DB::rollback();
                // dd( $validator->errors());
                return response()->json(['status' => 'fail', 'ms' => 'ลงทะเบียนไม่สำเร็จกรุณาลงทะเบียนไหม่']);
            }
        }

        //return  redirect('register')->withError('ลงทะเบียนไม่สำเร็จ');
        // dd($validator->errors());

        return response()->json(['ms' => 'กรุณากรอกข้อมูให้ครบถ้วนก่อนลงทะเบียน', 'error' => $validator->errors()]);
    }

    public static function check_sponser(Request $rs)
    { //คนแนะนำ//คนสร้าง

        $introduce_id = $rs->sponser;
        $user_create = $rs->user_name;
        $data_user = DB::table('customers')
            ->select('upline_id', 'user_name', 'name', 'last_name')
            ->where('user_name', '=', $introduce_id)
            ->first();

        if (!empty($data_user)) {

            if ($introduce_id == $user_create) {
                $resule = ['status' => 'success', 'message' => 'My Account', 'data' => $data_user];
                return $resule;
            }

            $upline_id = $data_user->upline_id;


            //หาด้านบนตัวเอง
            $upline_id_arr = array();

            $data_account = DB::table('customers')
                ->select('upline_id', 'user_name', 'name', 'last_name')
                ->where('user_name', '=', $upline_id)
                ->first();

            if (empty($data_account)) {
                $username = null;
                $j = 0;
            } else {
                $username = $data_account->upline_id;
                $j = 2;
            }

            for ($i = 1; $i <= $j; $i++) {
                if ($username == 'AA') {
                    $upline_id_arr[] = $data_account->user_name;
                    $j = 0;
                } else {
                    $data_account = DB::table('customers')
                        ->select('upline_id', 'user_name', 'name', 'last_name')
                        ->where('user_name', '=', $username)
                        ->first();

                    if (empty($data_account)) {
                        $j = 0;
                    } else {
                        $upline_id_arr[] = $data_account->user_name;
                        $username = $data_account->upline_id;

                        if ($data_account->user_name == $user_create) {
                            $j = 0;
                        } else {
                            $j = $j + 1;
                        }
                    }
                }
            }

            //return $resule;

            if (in_array($user_create, $upline_id_arr)) {
                $resule = ['status' => 'success', 'message' => 'เปลี่ยนผู้แนะนำสำเร็จ', 'data' => $data_user];
            } else {
                $resule = ['status' => 'fail', 'message' => 'ไม่พบรหัสสมาชิกดังกล่าวหรือไม่ได้อยู่ในสายงานเดียวกัน', 'data' => null];
            }
            return $resule;
        } else {
            $resule = ['status' => 'fail', 'message' => 'ไม่พบข้อมูลผู้ใช้งานรหัสนี้'];
            return $resule;
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

    public static function check_type_register($user_name, $lv)
    { //สำหรับหาสายล่างสุด ออโต้เพลง 1-5

        if ($lv == 1) {
            $data_sponser = DB::table('customers')
                ->select('user_name', 'upline_id', 'type_upline')
                ->where('upline_id', $user_name)
                ->orderby('type_upline', 'ASC')
                ->get();




            // $test = DB::table('customers')
            // ->select('user_name','upline_id','type_upline')
            // ->orderby('type_upline','ASC')
            // ->get();
            // dd($test);

        } else {

            // if($lv == 3){
            //     dd($user_name);
            //     //dd($data_sponser,$lv);
            // }
            $upline_child = DB::table('customers')
                ->selectRaw('count(upline_id) as count_upline, upline_id')
                ->whereIn('upline_id', $user_name)
                ->orderby('count_upline')
                ->orderby('type_upline')
                ->groupby('upline_id');


            $data_sponser = DB::table('customers')
                //->selectRaw('count(upline_id) as count_upline,upline_id')
                //->whereIn('upline_id',$user_name)
                //->groupby('upline_id')
                //->orderby('count_upline','ASC')

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
            $type = ['A', 'B', 'C', 'D', 'E'];
            $count = count($data_sponser);
            if ($count <= '4') {
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

            } elseif ($count >= '5') {
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
                if ($value->count_upline <= '4') {


                    $data_sponser_ckeck = DB::table('customers')
                        ->select('user_name', 'upline_id', 'type_upline')
                        ->where('upline_id', $value->user_name)
                        ->orderby('type_upline', 'ASC')
                        ->get();
                    $type = ['A', 'B', 'C', 'D', 'E'];
                    foreach ($data_sponser_ckeck as $value) {
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
                    $upline =  $value->user_name;
                    $data = ['status' => 'success', 'upline' => $upline, 'type' => $type[$array_key], 'rs' => $data_sponser_ckeck];
                    return $data;

                    // dd($data_sponser);

                }
                if ($value->type_upline == 'E' and $value->count_upline == 5) {
                    if ($lv == 2) { //25 คนแรกเต็มหมด

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
                            $check_auto_plack = RegisterController::check_auto_plack($value->user_name);

                            if ($check_auto_plack['status'] == 'success') {
                                return  $check_auto_plack;
                            } else {
                                $user_full[$l] = $value->user_name;
                                // $user_full[$l]['type'] = $check_auto_plack['status'];
                            }

                            if ($l == 25) {
                                $data_sponser_ckeck_vl_3 = DB::table('customers')
                                    ->select('user_name', 'upline_id', 'type_upline')
                                    ->wherein('upline_id',  $user_full)
                                    ->orderby('type_upline', 'ASC')
                                    ->orderby('id', 'ASC')
                                    ->get();



                                foreach ($data_sponser_ckeck_vl_3 as $value) {
                                    $user_full_lv_3[] = $value->user_name;
                                }

                                $data_sponser_ckeck_vl_4 = DB::table('customers')
                                    ->select('user_name', 'upline_id', 'type_upline')
                                    ->wherein('upline_id',  $user_full_lv_3)
                                    ->orderby('type_upline', 'ASC')
                                    ->orderby('id', 'ASC')
                                    ->get();



                                $l4 = 0;

                                foreach ($data_sponser_ckeck_vl_4 as $value) {
                                    $l4++;
                                    $check_auto_plack_lv4 = RegisterController::check_auto_plack($value->user_name);
                                    if ($check_auto_plack_lv4['status'] == 'success') {
                                        return  $check_auto_plack_lv4;
                                    }
                                    if ($l4 == 625) {
                                        $data = ['status' => 'fail', 'ms' => 'ไม่สามารถลงทะเบียนได้กรุณาติดต่อเจ้าหน้าที่ Code:25', 'user_name' => $data_sponser, 'code' => 'stop'];
                                        return $data;
                                    }
                                }
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

        $type = ['A', 'B', 'C', 'D', 'E'];
        $count = count($data_sponser);
        if ($count <= '4') {
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


            //dd($data_sponser);

        } else {
            // foreach ($data_sponser as $value) {
            //     $arr_user_name[] = $value->user_name;
            // }

            $data = ['status' => 'fail', 'code' => 'run'];
            return $data;
        }
    }

    //$data = App\Http\Controllers\Frontend\RegisterController::runbonus_not_thai($user_name);

    public static function runbonus_not_thai($user_name)
    {

        $data = DB::table('customers')
            ->where('user_name', $user_name)
            ->first();

        if (empty($data)) {
            $rs = ['status' => 'fail', 'ms' => 'ไม่มีรหัสนี้ในระบบ'];
            return $rs;
        }

        if ($data->business_location_id == 1) {
            $rs = ['status' => 'fail', 'ms' => 'รหัสนี้ไม่ใช่ต่างชาติ'];
            return $rs;
        }

        if ($data->status_runbonus_not_thai == 'success') {
            $rs = ['status' => 'fail', 'ms' => 'รหัสนี้ถูกคำนวนไปเเล้ว'];
            return $rs;
        }


        $report_bonus_register = DB::table('report_bonus_register')
            ->where('status', '=', 'panding')
            ->where('bonus', '>', 0)
            ->where('regis_user_name', '=', $user_name)
            ->get();

        foreach ($report_bonus_register as $value) {


            if ($value->bonus > 0) {

                $wallet_g = DB::table('customers')
                    ->select('ewallet', 'id', 'user_name', 'ewallet_use', 'bonus_total')
                    ->where('user_name', $value->user_name_g)
                    ->first();

                if ($wallet_g->ewallet == '' || empty($wallet_g->ewallet)) {
                    $wallet_g_user = 0;
                } else {

                    $wallet_g_user = $wallet_g->ewallet;
                }

                if ($wallet_g->bonus_total == '' || empty($wallet_g->bonus_total)) {
                    $bonus_total = 0 + $value->bonus;
                } else {

                    $bonus_total = $wallet_g->bonus_total + $value->bonus;
                }

                if ($wallet_g->ewallet_use == '' || empty($wallet_g->ewallet_use)) {
                    $ewallet_use = 0;
                } else {

                    $ewallet_use = $wallet_g->ewallet_use;
                }
                $eWallet_register = new eWallet();
                $wallet_g_total = $wallet_g_user +  $value->bonus;
                $ewallet_use_total =  $ewallet_use + $value->bonus;

                $eWallet_register->transaction_code = $value->code_bonus;
                $eWallet_register->customers_id_fk = $wallet_g->id;
                $eWallet_register->customer_username = $value->user_name_g;
                // $eWallet_register->customers_id_receive = $user->id;
                // $eWallet_register->customers_name_receive = $user->user_name;
                $eWallet_register->tax_total = $value->tax_total;
                $eWallet_register->bonus_full = $value->bonus_full;
                $eWallet_register->amt = $value->bonus;
                $eWallet_register->old_balance = $wallet_g_user;
                $eWallet_register->balance = $wallet_g_total;
                $eWallet_register->type = 10;
                $eWallet_register->note_orther = 'โบนัสโบนัสขยายธุรกิจ รหัส ' . $value->user_name . 'แนะนำรหัส ' . $value->regis_user_name;
                $eWallet_register->receive_date = now();
                $eWallet_register->receive_time = now();
                $eWallet_register->status = 2;

                DB::table('customers')
                    ->where('user_name', $value->user_name_g)
                    ->update(['ewallet' => $wallet_g_total, 'ewallet_use' => $ewallet_use_total, 'bonus_total' => $bonus_total]);

                DB::table('report_bonus_register')
                    ->where('user_name_g',  $value->user_name_g)
                    ->where('code_bonus', '=', $value->code_bonus)
                    ->where('regis_user_name', '=', $value->regis_user_name)
                    ->where('g', '=', $value->g)
                    ->update(['status' => 'success']);

                $eWallet_register->save();
            } else {
                DB::table('report_bonus_register')
                    ->where('user_name_g',  $value->user_name_g)
                    ->where('code_bonus', '=', $value->code_bonus)
                    ->where('regis_user_name', '=', $value->regis_user_name)
                    ->where('g', '=', $value->g)
                    ->update(['status' => 'success']);
            }
        }


        if ($data->qualification_id == 'VVIP') {

            $data_user_uoposition =  DB::table('customers')
                ->select(
                    'customers.name',
                    'customers.last_name',
                    'bonus_total',
                    'customers.user_name',
                    'customers.upline_id',
                    'customers.introduce_id',
                    'customers.qualification_id',
                    'customers.expire_date',
                    'dataset_qualification.id as qualification_id_fk'
                )
                ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                ->where('user_name', '=', $data->introduce_id)
                // ->where('dataset_qualification.id', '=', 6)// 4 - 7
                ->get();
            // $data_user =  DB::table('customers')
            //     ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
            //     ->where('customers.introduce_id', '=', '1384810')
            //     ->where('dataset_qualification.id', '=', 4)
            //     ->count();//

            // dd($data_user_1,$data_user);


            $i = 0;
            $k = 0;
            // dd($data_user_1);
            //ขึ้น XVVIP แนะนำ 2 VVIP คะแนน 0ว
            foreach ($data_user_uoposition as $value) {
                $i++;
                $data_user =  DB::table('customers')
                    ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                    ->where('customers.introduce_id', '=', $value->user_name)
                    ->where('dataset_qualification.id', '=', 4)
                    ->count(); //
                // dd($data_user);
                // dd($data_user,$value->qualification_id,$value->qualification_id_fk);
                //$data_user >= 2 and $value->qualification_id != 'XVVIP' and  $value->qualification_id_fk< 5
                if ($data_user >= 200 and $value->qualification_id_fk == 9) { //MD
                    $data_svvip =  DB::table('customers')
                        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        ->where('customers.introduce_id', '=', $value->user_name)
                        ->where('dataset_qualification.id', '=', 6)
                        ->count();
                    if ($data_svvip >= 21 and $value->bonus_total >= 3000000) {

                        $k++;
                        DB::table('customers')
                            ->where('user_name', $value->user_name)
                            ->update(['qualification_id' => 'MD']);
                        DB::table('log_up_vl')->insert([
                            'user_name' => $value->user_name,'introduce_id' => $value->introduce_id, 'bonus_total' => $value->bonus_total,
                            'old_lavel' => $data_user->code, 'new_lavel' => 'MD', 'vvip' => $data_user, 'svvip' => $data_svvip, 'status' => 'success'
                        ]);
                    }
                }

                if ($data_user >= 150 and  $value->qualification_id_fk == 8) {
                    $data_svvip =  DB::table('customers')
                        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        ->where('customers.introduce_id', '=', $value->user_name)
                        ->where('dataset_qualification.id', '=', 6)
                        ->count();
                    if ($data_svvip >= 13 and $value->bonus_total >= 2000000) {

                        $k++;
                        DB::table('customers')
                            ->where('user_name', $value->user_name)
                            ->update(['qualification_id' => 'ME']);
                        DB::table('log_up_vl')->insert([
                            'user_name' => $value->user_name,'introduce_id' => $value->introduce_id, 'bonus_total' => $value->bonus_total,
                            'old_lavel' => $value->qualification_id, 'new_lavel' => 'ME', 'vvip' => $data_user, 'svvip' => $data_svvip, 'status' => 'success'
                        ]);
                    }
                }



                if ($data_user >= 100 and  $value->qualification_id_fk == 7) {
                    $data_svvip =  DB::table('customers')
                        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        ->where('customers.introduce_id', '=', $value->user_name)
                        ->where('dataset_qualification.id', '=', 6)
                        ->count();
                    if ($data_svvip >= 7 and $value->bonus_total >= 1000000) {

                        $k++;
                        DB::table('customers')
                            ->where('user_name', $value->user_name)
                            ->update(['qualification_id' => 'MR']);
                        DB::table('log_up_vl')->insert([
                            'user_name' => $value->user_name,'introduce_id' => $value->introduce_id, 'bonus_total' => $value->bonus_total,
                            'old_lavel' => $value->qualification_id, 'new_lavel' => 'MR', 'vvip' => $data_user, 'svvip' => $data_svvip, 'status' => 'success'
                        ]);
                    }
                }

                if ($data_user >= 60 and  $value->qualification_id_fk == 6) {
                    $data_svvip =  DB::table('customers')
                        ->leftjoin('dataset_qualification', 'dataset_qualification.code', '=', 'customers.qualification_id')
                        ->where('customers.introduce_id', '=', $value->user_name)
                        ->where('dataset_qualification.id', '=', 6)
                        ->count();
                    if ($data_svvip >= 3 and $value->bonus_total >= 100000) {

                        $k++;
                        DB::table('customers')
                            ->where('user_name', $value->user_name)
                            ->update(['qualification_id' => 'MG']);
                        DB::table('log_up_vl')->insert([
                            'user_name' => $value->user_name,'introduce_id' => $value->introduce_id, 'bonus_total' => $value->bonus_total,
                            'old_lavel' => $value->qualification_id, 'new_lavel' => 'MG', 'vvip' => $data_user, 'svvip' => $data_svvip, 'status' => 'success'
                        ]);
                    }
                }

                if ($data_user >= 40 and  $value->qualification_id_fk == 5 and $value->bonus_total >= 100000) {


                    DB::table('customers')
                        ->where('user_name', $value->user_name)
                        ->update(['qualification_id' => 'SVVIP']);

                    $k++;
                    DB::table('log_up_vl')->insert([
                        'user_name' => $value->user_name,'introduce_id' => $value->introduce_id, 'bonus_total' => $value->bonus_total,
                        'old_lavel' => $value->qualification_id, 'new_lavel' => 'SVVIP', 'vvip' => $data_user, 'status' => 'success'
                    ]);
                }

                if ($data_user >= 2 and  $value->qualification_id_fk == 4) {

                    $k++;
                    DB::table('customers')
                        ->where('user_name', $value->user_name)
                        ->update(['qualification_id' => 'XVVIP']);
                    DB::table('log_up_vl')->insert([
                        'user_name' => $value->user_name,'introduce_id' => $value->introduce_id, 'old_lavel' => $value->qualification_id,
                        'new_lavel' => 'XVVIP', 'bonus_total' => $value->bonus_total, 'vvip' => $data_user, 'status' => 'success'
                    ]);
                }
            }
        }
        $start_month = date('Y-m-d');
        $mt_mount_new = strtotime("+33 Day", strtotime($start_month));

        DB::table('customers')
            ->where('user_name', $data->user_name)
            ->update(['status_runbonus_not_thai' => 'success', 'expire_date' => $mt_mount_new]);
        $rs = ['status' => 'success', 'ms' => 'อัพเดทโบนัสต่างชาติสำเร็จ'];
        return  $rs;
    }
}
