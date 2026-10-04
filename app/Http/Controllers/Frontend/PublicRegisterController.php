<?php

namespace App\Http\Controllers\Frontend;

use App\AddressProvince;
use App\Customers;
use App\Http\Controllers\Controller;
use App\RegisterApplication;
use App\Services\PublicRegisterService;
use App\Support\PositionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| สมัครสมาชิกผ่านลิงก์ (ไม่ต้อง login) แบบ wizard
|--------------------------------------------------------------------------
|
| ขั้นตอน: 1 ผู้แนะนำ/ทีมงาน -> 2 ข้อมูลสมาชิก -> 3 ที่อยู่/รายได้ -> 4 เอกสาร -> 5 เลือกสินค้า/ชำระเงิน
|
| ยังไม่สร้างสมาชิกจนกว่า PaySo จะยืนยันการชำระเงิน (ดู PaySoOrderController::handleRegisterPostback
| และ PublicRegisterService::activate)
|
*/
class PublicRegisterController extends Controller
{
    public function index($sponsor = null, $type = null)
    {
        $yeay_thai = date('Y') + 543 - 17;
        $arr_year = [];
        for ($i = 1; $i < 61; $i++) {
            $arr_year[] = $yeay_thai - $i;
        }
        rsort($arr_year);

        $day = range(1, 31);
        $bank = DB::table('dataset_bank')->get();
        $province = AddressProvince::orderBy('province_name', 'ASC')->get();
        $region = DB::table('dataset_business_location')->get();
        $products = PublicRegisterService::products();

        $sponsor_info = null;
        if ($sponsor) {
            $row = Customers::select('user_name', 'name', 'last_name')->where('user_name', $sponsor)->first();
            $sponsor_info = $row ? $row->user_name : null;
        }

        return view('frontend/join')
            ->with('day', $day)
            ->with('arr_year', $arr_year)
            ->with('bank', $bank)
            ->with('province', $province)
            ->with('region', $region)
            ->with('products', $products)
            ->with('pv_min', PositionService::LADDER[1]['pv'])
            ->with('sponsor', $sponsor_info)
            ->with('type', in_array($type, ['A', 'B']) ? $type : null);
    }

    /*
    |--------------------------------------------------------------------------
    | ตรวจรหัสผู้แนะนำ (ใช้แสดงชื่อในขั้นตอนที่ 1)
    |--------------------------------------------------------------------------
    */
    public function checkSponsor(Request $request)
    {
        $row = Customers::select('user_name', 'name', 'last_name', 'status_customer')
            ->where('user_name', $request->sponsor)->first();

        if (!$row || $row->status_customer == 'cancel') {
            return response()->json(['status' => 'fail', 'message' => 'ไม่พบรหัสผู้แนะนำ']);
        }

        return response()->json(['status' => 'success', 'name' => $row->name . ' ' . $row->last_name]);
    }

    /*
    |--------------------------------------------------------------------------
    | กติกาตรวจข้อมูลแต่ละขั้นตอน
    |--------------------------------------------------------------------------
    */
    private function rules($step, Request $request)
    {
        $required = 'กรุณากรอกข้อมูล';
        $rules = [];

        if ($step == 1) {
            $rules = ['sponsor' => 'required', 'type' => 'required|in:A,B'];
        }

        if ($step == 2) {
            $rules = [
                'prefix_name' => 'required',
                'name' => 'required',
                'last_name' => 'required',
                'gender' => 'required',
                'business_name' => 'required',
                'day' => 'required',
                'month' => 'required',
                'year' => 'required',
                'nation_id' => 'required',
                'id_card' => 'required|min:13',
                'phone' => 'required|numeric|digits:10',
                'email' => 'nullable|email',
            ];
        }

        if ($step == 3) {
            $rules = [
                'card_address' => 'required',
                'card_moo' => 'required',
                'card_soi' => 'required',
                'card_road' => 'required',
                'card_province' => 'required',
                'card_district' => 'required',
                'card_tambon' => 'required',
                'card_zipcode' => 'required',
                'same_address' => 'required',
                'same_moo' => 'required',
                'same_soi' => 'required',
                'same_road' => 'required',
                'same_province' => 'required',
                'same_district' => 'required',
                'same_tambon' => 'required',
                'same_zipcode' => 'required',
            ];

            // บัญชีธนาคาร: ไม่บังคับ แต่ถ้ากรอกอย่างใดอย่างหนึ่งต้องครบ
            if ($request->filled('bank_name') || $request->filled('bank_branch') || $request->filled('bank_no') || $request->filled('account_name')) {
                $rules['bank_name'] = 'required';
                $rules['bank_branch'] = 'required';
                $rules['bank_no'] = 'required|numeric';
                $rules['account_name'] = 'required';
            }

            // ผู้รับผลประโยชน์: ไม่บังคับ แต่ถ้ากรอกต้องครบ
            if ($request->filled('name_benefit') || $request->filled('last_name_benefit') || $request->filled('involved')) {
                $rules['name_benefit'] = 'required';
                $rules['last_name_benefit'] = 'required';
                $rules['involved'] = 'required';
            }
        }

        if ($step == 4) {
            $rules = ['file_card' => 'required|image|mimes:jpeg,jpg,png|max:5120'];
            $bank_filled = $request->filled('bank_name') || $request->filled('bank_no');
            $rules['file_bank'] = ($bank_filled ? 'required|' : 'nullable|') . 'image|mimes:jpeg,jpg,png|max:5120';
        }

        $messages = [
            'required' => $required,
            'in' => 'กรุณาเลือกขา A หรือ B',
            'numeric' => 'ใส่เฉพาะตัวเลขเท่านั้น',
            'digits' => 'กรุณากรอกให้ครบ :digits หลัก',
            'min' => 'กรุณากรอกให้ครบ 13 หลัก',
            'email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'image' => 'รองรับไฟล์รูปภาพ jpeg, jpg, png เท่านั้น',
            'mimes' => 'รองรับไฟล์นามสกุล jpeg, jpg, png เท่านั้น',
            'max' => 'ไฟล์ต้องมีขนาดไม่เกิน 5 MB',
        ];

        return [$rules, $messages];
    }

    private function validateStep($step, Request $request)
    {
        [$rules, $messages] = $this->rules($step, $request);
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return ['message' => 'กรุณากรอกข้อมูลให้ครบถ้วน', 'errors' => $validator->errors()];
        }

        if ($step == 1) {
            $sponsor = Customers::select('status_customer')->where('user_name', $request->sponsor)->first();
            if (!$sponsor || $sponsor->status_customer == 'cancel') {
                $msg = 'ไม่พบรหัสผู้แนะนำ';
                return ['message' => $msg, 'errors' => ['sponsor' => [$msg]]];
            }
        }

        if ($step == 2) {
            if (Customers::where('id_card', $request->id_card)->exists()) {
                $msg = 'เลขบัตรประชาชนนี้ลงทะเบียนครบ 1 รหัสแล้ว ไม่สามารถลงทะเบียนเพิ่มได้';
                return ['message' => $msg, 'errors' => ['id_card' => [$msg]]];
            }
        }

        return null;
    }

    public function validateStepAjax(Request $request)
    {
        $step = (int) $request->step;
        if ($step < 1 || $step > 4) {
            return response()->json(['status' => 'fail', 'message' => 'ขั้นตอนไม่ถูกต้อง'], 422);
        }

        $fail = $this->validateStep($step, $request);
        if ($fail) {
            return response()->json(['status' => 'fail'] + $fail, 422);
        }

        return response()->json(['status' => 'success']);
    }

    /*
    |--------------------------------------------------------------------------
    | คำนวณราคา / PV / ตำแหน่งที่จะได้ (ขั้นตอนที่ 5)
    |--------------------------------------------------------------------------
    */
    public function quote(Request $request)
    {
        $quote = PublicRegisterService::quote((array) $request->items, $request->zipcode);
        $quote['pv_min'] = PositionService::LADDER[1]['pv'];

        return response()->json($quote);
    }

    /*
    |--------------------------------------------------------------------------
    | บันทึกใบสมัคร แล้วส่งไปชำระเงินที่ PaySo
    |--------------------------------------------------------------------------
    */
    public function submit(Request $request)
    {
        foreach ([1, 2, 3, 4] as $step) {
            $fail = $this->validateStep($step, $request);
            if ($fail) {
                return response()->json(['status' => 'fail', 'step' => $step] + $fail, 422);
            }
        }

        $pay_method = $request->pay_method === 'slip' ? 'slip' : 'payso';
        if ($pay_method === 'slip') {
            $v = Validator::make($request->all(), ['file_slip' => 'required|image|mimes:jpeg,jpg,png|max:5120'], [
                'required' => 'กรุณาแนบสลิปการชำระเงิน',
                'image' => 'รองรับไฟล์รูปภาพ jpeg, jpg, png เท่านั้น',
                'mimes' => 'รองรับไฟล์นามสกุล jpeg, jpg, png เท่านั้น',
                'max' => 'ไฟล์ต้องมีขนาดไม่เกิน 5 MB',
            ]);
            if ($v->fails()) {
                return response()->json(['status' => 'fail', 'step' => 5, 'message' => 'กรุณาแนบสลิปการชำระเงิน', 'errors' => $v->errors()], 422);
            }
        }

        $quote = PublicRegisterService::quote((array) $request->items, $request->same_zipcode);
        $pv_min = PositionService::LADDER[1]['pv'];

        if (empty($quote['lines'])) {
            return response()->json(['status' => 'fail', 'step' => 5, 'message' => 'กรุณาเลือกสินค้า'], 422);
        }
        if ($quote['pv_total'] < $pv_min) {
            return response()->json(['status' => 'fail', 'step' => 5, 'message' => 'PV รวมขั้นต่ำในการสมัครคือ ' . number_format($pv_min) . ' PV'], 422);
        }
        if ($quote['total_price'] <= 0) {
            return response()->json(['status' => 'fail', 'step' => 5, 'message' => 'ยอดชำระไม่ถูกต้อง'], 422);
        }

        $token = Str::random(40);

        // เก็บไฟล์ไว้ชั่วคราวใน public/images/register_applications/ แล้วย้ายเข้าโฟลเดอร์สมาชิกตอนชำระเงินสำเร็จ
        $dir = 'images/register_applications/' . date('Ym');
        $files = [];
        foreach (['file_card', 'file_bank', 'file_slip'] as $field) {
            if ($request->hasFile($field)) {
                $name = $token . '_' . $field . '.' . $request->file($field)->extension();
                $request->file($field)->move(public_path($dir), $name);
                $files[$field] = $dir . '/' . $name;
            }
        }

        $form = $request->except(['file_card', 'file_bank', 'file_slip', 'items', '_token', 'step', 'pay_method']);
        $form['sponsor'] = $request->sponsor;

        $app = RegisterApplication::create([
            'token' => $token,
            'payso_refno' => $this->generateRefNo(),
            'sponsor_user_name' => $request->sponsor,
            // ผู้สมัครเลือกแค่ขา A/B ส่วน upline ปล่อยว่าง ระบบวิ่งไปสุดสายของขานั้นตอนสร้างสมาชิก (PublicRegisterService::activate)
            'upline_user_name' => '',
            'type_upline' => $request->type,
            'id_card' => $request->id_card,
            'form_data' => json_encode($form, JSON_UNESCAPED_UNICODE),
            'items' => json_encode($quote['lines'], JSON_UNESCAPED_UNICODE),
            'files' => json_encode($files),
            'pv_total' => $quote['pv_total'],
            'sum_price' => $quote['sum_price'],
            'shipping_price' => $quote['shipping'],
            'total_price' => $quote['total_price'],
            'position' => $quote['position'],
            'pay_method' => $pay_method,
            'status' => $pay_method === 'slip' ? 'slip_pending' : 'pending',
        ]);

        $request->session()->put('join_app_token', $token);

        Log::channel('payment')->info('Public register application created', [
            'application_id' => $app->id,
            'payso_refno' => $app->payso_refno,
            'sponsor' => $app->sponsor_user_name,
            'amount' => $app->total_price,
            'pv' => $app->pv_total,
        ]);

        if ($pay_method === 'slip') {
            return response()->json([
                'status' => 'success',
                'method' => 'slip',
                'redirect' => route('join.result', ['token' => $app->token]),
            ]);
        }

        $paymentUrl = config('payso.payment_url');
        if (empty($paymentUrl)) {
            return response()->json(['status' => 'fail', 'step' => 5, 'message' => 'ยังไม่ได้ตั้งค่า PaySo Payment URL'], 500);
        }

        return response()->json([
            'status' => 'success',
            'method' => 'payso',
            'payment_url' => $paymentUrl,
            'payload' => [
                'customeremail' => filter_var($request->email, FILTER_VALIDATE_EMAIL) ?: config('payso.default_customer_email', 'no-reply@maruay.co.th'),
                'productdetail' => 'Register ' . $app->payso_refno,
                'refno' => $app->payso_refno,
                'merchantid' => config('payso.merchant_id'),
                'cc' => config('payso.currency_code', '00'),
                'total' => $this->formatTotal($app->total_price),
                'lang' => config('payso.lang', 'TH'),
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | หน้าผลการสมัคร (PaySo return) + ตรวจสถานะ
    |--------------------------------------------------------------------------
    */
    public function result(Request $request, $token = null)
    {
        $token = $token ?: $request->session()->get('join_app_token');
        $app = $token ? RegisterApplication::where('token', $token)->first() : null;

        return view('frontend/join_result')->with('app', $app);
    }

    public function status($token)
    {
        $app = RegisterApplication::where('token', $token)->first();
        if (!$app) {
            return response()->json(['status' => 'notfound'], 404);
        }

        $data = ['status' => $app->status];
        if ($app->status === 'paid') {
            $c = Customers::select('prefix_name', 'name', 'last_name', 'business_name', 'user_name', 'qualification_id')
                ->where('user_name', $app->customer_user_name)->first();
            $data['user_name'] = $app->customer_user_name;
            $data['full_name'] = $c ? $c->prefix_name . $c->name . ' ' . $c->last_name : '';
            $data['business_name'] = $c ? $c->business_name : '';
            $data['position'] = $app->position;
            $data['pv_total'] = $app->pv_total;
            $data['code_order'] = $app->code_order;
        }

        return response()->json($data);
    }

    private function generateRefNo()
    {
        do {
            $refNo = date('ymdHi') . random_int(10, 99);
        } while (
            RegisterApplication::where('payso_refno', $refNo)->exists()
            || \App\Orders::where('payso_refno', $refNo)->exists()
        );

        return $refNo;
    }

    private function formatTotal($amount)
    {
        $amount = round((float) $amount, 2);
        if (floor($amount) == $amount) {
            return (string) (int) $amount;
        }

        return rtrim(rtrim(number_format($amount, 2, '.', ''), '0'), '.');
    }
}
