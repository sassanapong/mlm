<?php

namespace App\Http\Controllers\Backend;

use App\AddressProvince;
use App\Customers;
use App\CustomersAddressCard;
use App\CustomersAddressDelivery;
use App\CustomersBank;
use App\CustomersBenefit;
use App\Http\Controllers\Controller;
use App\Http\Middleware\Customer;
use App\Models\CUser;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Validator;

class CustomerServiceController extends Controller
{


    public function index(Request $request)
    {
        return view('backend.customer_service.check_doc.index');
    }


    public function get_check_doc(Request $request)
    {

        $data = Customers::select(
            "id",
            'user_name',
            'name',
            'last_name',
            'prefix_name',
            'phone',
            'regis_doc1_status',
            'regis_doc4_status',
        )
            // ->where(function ($query) use ($request) {
            //     if ($request->has('Where')) {
            //         foreach (request('Where') as $key => $val) {
            //             if ($val) {
            //                 if (strpos($val, ',')) {
            //                     $query->whereIn($key, explode(',', $val));
            //                 } else {
            //                     $query->where($key, $val);
            //                 }
            //             }
            //         }
            //     }
            //     if ($request->has('Like')) {
            //         foreach (request('Like') as $key => $val) {
            //             if ($val) {
            //                 $query->where($key, 'like', '%' . $val . '%');
            //             }
            //         }
            //     }
            //     // $query->orWhere('regis_doc1_status', '>=', '3');
            //     // $query->orWhere('regis_doc4_status', '>=', '3');
            // })
            ->where('regis_doc1_status', '>=', '3')
            ->orwhere('regis_doc4_status', '>=', '3')
            ->orderBy('updated_at', 'DESC');


        return DataTables::of($data)
            ->setRowClass('intro-x py-4 h-24 zoom-in')

            // ดึงข้อมูล created_at
            ->editColumn('created_at', function ($query) {
                $time = date('d-m-Y H:i:s', strtotime($query->created_at));

                return $time;
            })

            // ดึงข้อมูล ชื่อ นามสกุล
            ->editColumn('name', function ($query) {
                $text = $query->prefix_name . "" . $query->name . " " . $query->last_name;
                return $text;
            })

            // Text color icon
            ->addColumn('text_color_doc_1', function ($query) {
                $text_color = '';
                if ($query->regis_doc1_status == 1) {
                    $text_color = 'text-success';
                }
                if ($query->regis_doc1_status == 3) {
                    $text_color = 'text-warning';
                }
                if ($query->regis_doc1_status == 4) {
                    $text_color = 'text-danger';
                }
                return $text_color;
            })
            ->addColumn('text_color_doc_4', function ($query) {
                $text_color = '';

                if ($query->regis_doc4_status == 1) {
                    $text_color = 'text-success';
                }
                if ($query->regis_doc4_status == 3) {
                    $text_color = 'text-warning';
                }
                if ($query->regis_doc4_status == 4) {
                    $text_color = 'text-danger';
                }
                return $text_color;
            })
            ->make(true);
    }

    public function admin_login_user($id)
    {

        $users = CUser::where('id', $id)->first();
        Auth::guard('c_user')->logout();
        Auth::guard('c_user')->login($users);

        return redirect('editprofile');
    }


    public function admin_get_info_card(Request $request)
    {
        $user_name = $request->user_name;
        $data = CustomersAddressCard::select(
            'customers_address_card.*',
            'district_name as district',
            'province_name as province',
            'tambon_name as tambon',
            'regis_doc1_status',
        )
            ->leftjoin('customers', 'customers.user_name', 'customers_address_card.user_name')
            ->leftjoin('address_districts', 'address_districts.district_id', 'customers_address_card.district')
            ->leftjoin('address_provinces', 'address_provinces.province_id', 'customers_address_card.province')
            ->leftjoin('address_tambons', 'address_tambons.tambon_id', 'customers_address_card.tambon')
            ->where('customers_address_card.user_name', $user_name)->first();

        return response()->json($data);
    }


    public function action_card_doc(Request $request)
    {
        $user_name = $request->user_name;
        $status = $request->status;


        // status == 1 ผ่าน ,4 == ไม่ผ่าน
        if ($status == 1) {

            $query = Customers::where('user_name', $user_name)->update(['regis_doc1_status' => $status]);
            return response()->json(['status' => 'success'], 200);
        } else if ($status == 4) {
            $query = Customers::where('user_name', $user_name)->update(['regis_doc1_status' => $status]);

            $query_del_card = CustomersAddressCard::where('user_name', $user_name)->first();

            if ($query_del_card) {
                $path = $query_del_card->url . '/' . $query_del_card->img_card;

                if (file_exists($path)) {
                    unlink($path);
                }
            }
            $query_del_card->delete();
            return response()->json(['status' => 'success'], 200);
        }
    }




    public function admin_get_info_bank(Request $request)
    {
        $user_name = $request->user_name;
        $data = CustomersBank::select(
            'customers_bank.*',
            'regis_doc1_status',
            'regis_doc4_status',
            'dataset_bank.name'
        )
            ->leftjoin('customers', 'customers.user_name', 'customers_bank.user_name')
            ->leftjoin('dataset_bank', 'dataset_bank.id', 'customers_bank.bank_id_fk')
            ->where('customers_bank.user_name', $user_name)->first();
        return response()->json($data);
    }

    public function action_bank_doc(Request $request)
    {
        $user_name = $request->user_name;
        $status = $request->status;
        // status == 1 ผ่าน ,4 == ไม่ผ่าน
        if ($status == 1) {

            $query = Customers::where('user_name', $user_name)->update(['regis_doc4_status' => $status]);
            return response()->json(['status' => 'success'], 200);
        } else if ($status == 4) {
            $query = Customers::where('user_name', $user_name)->update(['regis_doc4_status' => $status]);

            $query_del_bank = CustomersBank::where('user_name', $user_name)->first();

            if ($query_del_bank) {
                $path = $query_del_bank->url . '/' . $query_del_bank->img_bank;

                if (file_exists($path)) {
                    unlink($path);
                }
            }
            $query_del_bank->delete();
            return response()->json(['status' => 'success'], 200);
        }
    }


    public function info_customer($id)

    {
        $customers_id = $id;
        $province = AddressProvince::orderBy('province_name', 'ASC')->get();

        //BEGIN ข้อมูลส่วนตัวของ customers
        $customers_info = Customers::where('id', $customers_id)->first();
        //END ข้อมูลส่วนตัวของ customers

        //BEGIN แยกวันเกิดจากที่ดึงมาใน DB จาก Y-m-d แยก ออกวันอันๆ
        $customers_day = date('d', strtotime($customers_info->birth_day));
        $customers_month = date('m', strtotime($customers_info->birth_day));
        $customers_year = date('Y', strtotime($customers_info->birth_day));
        //END แยกวันเกิดจากที่ดึงมาใน DB จาก Y-m-d แยก ออกวันอันๆ


        // BEGIN ข้อมูลบัตรประชาชน
        $address_card = CustomersAddressCard::where('customers_id', $customers_id)->first();
        // END ข้อมูลบัตรประชาชน

        // BEGIN ที่อยู่จัดส่ง
        $address_delivery = CustomersAddressDelivery::where('customers_id', $customers_id)->first();
        // END ที่อยู่จัดส่ง

        // BEGIN ข้อมูลธนาคาร
        $info_bank = CustomersBank::where('customers_id', $customers_id)->first();
        // END ข้อมูลธนาคาร

        // BEGIN ผู้รับผลประโยชน์
        $info_benefit = CustomersBenefit::where('customers_id', $customers_id)->first();
        $bank = DB::table('dataset_bank')
            ->get();


        return view('backend.customer_service.check_doc.info_customer')
            ->with('province', $province) //จังหวัด
            ->with('customers_info', $customers_info) //ข้อมูลส่วนตัว
            ->with('customers_day', $customers_day) //วันเกิด
            ->with('customers_month', $customers_month) //เดือนเกิด
            ->with('customers_year', $customers_year) //ปีเกิด
            ->with('address_card', $address_card) //ข้อมูลบัตรประชาชน
            ->with('address_delivery', $address_delivery) //ข้อมูลที่อยู่จัดส่ง
            ->with('info_bank', $info_bank) //ข้อมูลธนาคาร
            ->with('bank', $bank) //ข้อมูลธนาคาร
            ->with('info_benefit', $info_benefit); //ผู้รับผลประโยชน์
        ;
    }


    public function admin_edit_form_info(Request $request)
    {


        $rule = [
            // BEGIN ข้อมูลส่วนตัว
            'prefix_name' => 'required',
            'name' => 'required',
            'last_name' => 'required',
            'gender' => 'required',
            'id_card' => 'required|min:13',
            'phone' => 'required|numeric',
            'day' => 'required',
            'month' => 'required',
            'year' => 'required',
            'nation_id' => 'required',
            'phone' => 'required|numeric',
            // END ข้อมูลส่วนตัว
        ];
        $message_err = [
            // BEGIN ข้อมูลส่วนตัว

            'prefix_name.required' => 'กรุณากรอกข้อมูล',
            'name.required' => 'กรุณากรอกข้อมูล',
            'last_name.required' => 'กรุณากรอกข้อมูล',
            'gender.required' => 'กรุณากรอกข้อมูล',
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
        ];

        $validator = Validator::make(
            $request->all(),
            $rule,
            $message_err
        );

        if (!$validator->fails()) {


            //BEGIN วันเกิด
            $day = $request->day;
            $month = $request->month;
            $year = $request->year;

            $YMD = $year . "-" . $month . "-" . $day;

            $birth_day = date('Y-m-d', strtotime($YMD));


            $data = $request->all();
            $dataPrepare = [];
            foreach ($data as $key => $value) {
                if (
                    $key != "_token" && $key != "customers_id" && $key != "day" && $key != "month" && $key
                    != "year"
                ) {
                    $dataPrepare[$key] = $value;
                }
                $dataPrepare['birth_day'] =  $birth_day;
            }



            $query = Customers::where('id', $request->customers_id)->update($dataPrepare);
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['error' => $validator->errors()]);
    }
    public function admin_edit_form_info_card(Request $request)
    {


        $rule = [
            // BEGIN ข้อมูลส่วนตัว
            'card_address' => 'required',
            'card_moo' => 'required',
            'card_soi' => 'required',
            'card_road' => 'required',
            'card_province' => 'required',
            'card_district' => 'required',
            'card_tambon' => 'required',
            'card_zipcode' => 'required',
            'card_phone' => 'required',
            // END ข้อมูลส่วนตัว
        ];
        $message_err = [
            // BEGIN ข้อมูลส่วนตัว

            'card_address.required' => 'กรุณากรอกข้อมูล',
            'card_moo.required' => 'กรุณากรอกข้อมูล',
            'card_soi.required' => 'กรุณากรอกข้อมูล',
            'card_road.required' => 'กรุณากรอกข้อมูล',
            'card_province.required' => 'กรุณากรอกข้อมูล',
            'card_district.required' => 'กรุณากรอกข้อมูล',
            'card_tambon.required' => 'กรุณากรอกข้อมูล',
            'card_zipcode.required' => 'กรุณากรอกข้อมูล',
            'card_phone.required' => 'กรุณากรอกข้อมูล',

            // END ข้อมูลส่วนตัว
        ];

        $validator = Validator::make(
            $request->all(),
            $rule,
            $message_err
        );

        if (!$validator->fails()) {
            $dataPrepare = [
                'address' => $request->card_address,
                'moo' => $request->card_moo,
                'soi' => $request->card_soi,
                'road' => $request->card_road,
                'province' => $request->card_province,
                'district' => $request->card_district,
                'tambon' => $request->card_tambon,
                'zipcode' => $request->card_zipcode,
                'phone' => $request->card_phone,
            ];
            $query = CustomersAddressCard::updateOrInsert([
                'customers_id' =>  $request->customers_id
            ], $dataPrepare);
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['error' => $validator->errors()]);
    }


    public function admin_edit_form_address_delivery(Request $request)
    {

        $rule = [
            // BEGIN ข้อมูลส่วนตัว
            'same_address' => 'required',
            'same_moo' => 'required',
            'same_soi' => 'required',
            'same_road' => 'required',
            'same_province' => 'required',
            'same_district' => 'required',
            'same_tambon' => 'required',
            'same_zipcode' => 'required',
            'same_phone' => 'required',
            // END ข้อมูลส่วนตัว
        ];
        $message_err = [
            // BEGIN ข้อมูลส่วนตัว

            'same_address.required' => 'กรุณากรอกข้อมูล',
            'same_moo.required' => 'กรุณากรอกข้อมูล',
            'same_soi.required' => 'กรุณากรอกข้อมูล',
            'same_road.required' => 'กรุณากรอกข้อมูล',
            'same_province.required' => 'กรุณากรอกข้อมูล',
            'same_district.required' => 'กรุณากรอกข้อมูล',
            'same_tambon.required' => 'กรุณากรอกข้อมูล',
            'same_zipcode.required' => 'กรุณากรอกข้อมูล',
            'same_phone.required' => 'กรุณากรอกข้อมูล',

            // END ข้อมูลส่วนตัว
        ];

        $validator = Validator::make(
            $request->all(),
            $rule,
            $message_err
        );

        if (!$validator->fails()) {
            $dataPrepare = [
                'address' => $request->same_address,
                'moo' => $request->same_moo,
                'soi' => $request->same_soi,
                'road' => $request->same_road,
                'tambon' => $request->same_tambon,
                'district' => $request->same_district,
                'province' => $request->same_province,
                'zipcode' => $request->same_zipcode,
                'phone' => $request->same_phone,
            ];
            $query = CustomersAddressDelivery::where('customers_id', $request->customers_id)->update($dataPrepare);
            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['error' => $validator->errors()]);
    }

    public function admin_edit_form_info_bank(Request $request)
    {
        $rule = [
            // BEGIN ข้อมูลส่วนตัว
            'bank_name' => 'required',
            'bank_branch' => 'required',
            'bank_no' => 'required',
            'account_name' => 'required',

            // END ข้อมูลส่วนตัว
        ];
        $message_err = [
            // BEGIN ข้อมูลส่วนตัว

            'bank_name.required' => 'กรุณากรอกข้อมูล',
            'bank_branch.required' => 'กรุณากรอกข้อมูล',
            'bank_no.required' => 'กรุณากรอกข้อมูล',
            'account_name.required' => 'กรุณากรอกข้อมูล',


            // END ข้อมูลส่วนตัว
        ];

        $validator = Validator::make(
            $request->all(),
            $rule,
            $message_err
        );

        if (!$validator->fails()) {

            $bank = DB::table('dataset_bank')
                ->where('id', '=', $request->bank_name)
                ->first();

            $dataPrepare = [
                'customers_id' => $request->customers_id,
                'user_name' => $request->user_name,
                'bank_name' => $bank->name,
                'bank_id_fk' => $bank->id,
                'code_bank' => $bank->code,
                'bank_branch' => $request->bank_branch,
                'bank_no' => $request->bank_no,
                'account_name' => $request->account_name,
            ];

            $rquery_bamk = CustomersBank::updateOrInsert([
                'customers_id' =>  $request->customers_id
            ], $dataPrepare);

            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['error' => $validator->errors()]);
    }


    public function admin_edit_form_info_benefit(Request $request)
    {
        $rule = [
            // BEGIN ข้อมูลส่วนตัว
            'name_benefit' => 'required',

            'last_name_benefit' => 'required',
            'involved' => 'required',
            // END ข้อมูลส่วนตัว
        ];
        $message_err = [
            // BEGIN ข้อมูลส่วนตัว

            'name_benefit.required' => 'กรุณากรอกข้อมูล',
            'last_name_benefit.required' => 'กรุณากรอกข้อมูล',
            'involved.required' => 'กรุณากรอกข้อมูล',
            // END ข้อมูลส่วนตัว
        ];

        $validator = Validator::make(
            $request->all(),
            $rule,
            $message_err
        );

        if (!$validator->fails()) {

            $dataPrepare = [
                'customers_id' => $request->customers_id,
                'user_name' => $request->user_name,
                'name' => $request->name_benefit,
                'last_name' => $request->last_name_benefit,
                'involved' => $request->involved,
            ];

            $rquery_bamk = CustomersBenefit::updateOrInsert([
                'customers_id' =>  $request->customers_id
            ], $dataPrepare);

            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['error' => $validator->errors()]);
    }

    public function search_username(Request $request)
    {
        $query = Customers::select('id')->where('user_name', $request->user_name)->first();

        if ($query  != null) {
            return response()->json(['status' => 'success', 'data' => $query], 200);
        }
        return response()->json(['status' => 'error']);
    }
}
