<?php

namespace App\Http\Controllers\Frontend;

use App\Customers;
use App\AddressProvince;
use App\CustomersAddressCard;
use App\CustomersAddressDelivery;
use App\CustomersBank;
use App\CustomersBenefit;
use App\Jang_pv;
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
        $age_min = 20;
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

    public function pv(Request $request){
        $result = DB::table('dataset_qualification')->where('code', $request->val)->first();
        return $result->pv;
    }


    public function store_register(Request $request)
    {

        // เช็ค PV Sponser
        $sponser = Customers::where('user_name',$request->sponser)->first();
        if($sponser->pv < $request->pv){
            return response()->json(['pvalert' => 'PV ของท่านไม่เพียงพอ']);
        }
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
            'email' => 'email',
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
            'email.email' => 'รูปแบบเมลไม่ถูกต้อง',
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
            $i = 0;
            $x = 'start';
            while ($x == 'start') {
                $i++;
                if ($data['status'] == 'fail' and $data['code'] == 'stop') {

                    $x = 'stop';
                    return response()->json(['status' => 'fail', 'ms' => $data['ms']]);
                } elseif ($data['status'] == 'fail' and $data['code'] == 'run') {

                    $data = RegisterController::check_type_register($data['arr_user_name']);
                } else {
                    $x = 'stop';
                }
            }


            $customer = [
                'user_name' => $user_name,
                'password' => md5($password),
                'upline_id' => $data['upline'],
                'introduce_id' => $request->sponser,
                'type_upline' => $data['type'],
                'prefix_name' => $request->prefix_name,
                'name' => $request->name,
                'last_name' => $request->last_name,
                'gender' => $request->gender,
                'business_name' => $request->business_name,
                'business_location_id' => $request->nation_id,
                'id_card' => $request->id_card,
                'phone' => $request->phone,
                'birth_day' => $birth_day,
                'nation_id' => 'ไทย',
                'business_location_id' => $request->nation_id,
                'qualification_id'=> $request->sizebusiness,
                'id_card' => $request->id_card,
                'phone' => $request->phone,
                'email' => $request->email,
                'line_id' => $request->line_id,
                'facebook' => $request->facebook,
                'regis_doc4_status' => 0,
                'regis_doc1_status' => 3,
            ];

            try {
                DB::BeginTransaction();

                // หัก PV Sponser
                $sponser = Customers::where('user_name',$request->sponser)->first();
                // End PV Sponser
              
                $insert_customer = Customers::create($customer);

                $y = date('Y') + 543;
                $y = substr($y, -2);
                $code =  IdGenerator::generate([
                    'table' => 'jang_pv',
                    'field' => 'code',
                    'length' => 15,
                    'prefix' => 'PV' . $y . '' . date("m") . '-',
                    'reset_on_prefix_change' => true
                ]);

                $jang_pv = [
                   'code'=>$code,
                   'customer_username'=>$sponser->user_name,
                   'to_customer_username'=>$user_name,
                   'position'=>$request->sizebusiness,
                   'pv_old'=>$sponser->pv,
                   'pv'=>$request->pv,
                   'pv_balance'=>$sponser->pv-$request->pv,
                   'type'=>'4',
                   'status'=>'Success'
                ];

                $sponser->pv = $sponser->pv-$request->pv;
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

                    $query_address_card = CustomersAddressCard::create($CustomersAddressCard);

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
                    $query_address_delivery = CustomersAddressDelivery::create($CustomersAddressDelivery);
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
                            'account_name' => $request->account_name,
                        ];



                        $rquery_bamk = CustomersBank::updateOrInsert([
                            'customers_id' => $insert_customer->id
                        ], $CustomersBank);

                        $rquery_bamk = CustomersBank::create($CustomersBank);


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
                    DB::commit();
                    return response()->json(['status' => 'success', 'data_result' => $data_result], 200);
                }
            } catch (Exception $e) {
                DB::rollback();
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

    public static function check_type_register($user_name, $lv = '')
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

                ->selectRaw('(CASE WHEN count_upline IS NULL THEN 0 ELSE count_upline END) as count_upline, user_name,type_upline')
                ->whereIn('user_name', $user_name)
                ->leftJoinSub($upline_child, 'upline_child', function ($join) {
                    $join->on('customers.user_name', '=', 'upline_child.upline_id');
                })
                ->orderby('count_upline')
                ->orderby('type_upline')
                ->get();
            // dd($data_sponser);
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
                $data = ['status' => 'fail', 'ms' => 'ไม่สามารถลงทะเบียนได้กรุณาติดต่อเจ้าหน้าที่', 'user_name' => $data_sponser, 'code' => 'stop'];
                return $data;
            }
        } else {
            if ($data_sponser[0]->count_upline ==  0) {
                $upline = $data_sponser[0]->user_name;
                $data = ['status' => 'success', 'upline' => $upline, 'type' => 'A', 'rs' => $data_sponser];
                return $data;
            }
            if ($data_sponser[0]->count_upline <= '4') {


                $data_sponser_ckeck = DB::table('customers')
                    ->select('user_name', 'upline_id', 'type_upline')
                    ->where('upline_id', $data_sponser[0]->user_name)
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
                $upline =  $data_sponser[0]->user_name;
                $data = ['status' => 'success', 'upline' => $upline, 'type' => $type[$array_key], 'rs' => $data_sponser_ckeck];
                return $data;

                // dd($data_sponser);

            } else {
                foreach ($data_sponser as $value) {
                    $arr_user_name_2[] = $value->upline_id;
                }

                $data = ['status' => 'fail', 'arr_user_name' => $arr_user_name_2, 'code' => 'run'];
                return $data;
            }
        }
    }
}
