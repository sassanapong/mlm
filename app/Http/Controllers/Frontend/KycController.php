<?php

namespace App\Http\Controllers\Frontend;

use App\Customers;
use App\CustomersBank;
use App\CustomersKyc;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

// 1 Code 1 World: สมาชิกเดิมยืนยันตัวตนผ่าน Pop-up หลัง Login
class KycController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer');
    }

    // หน้ายืนยันตัวตน 1 Code 1 World
    public function page()
    {
        $user = Auth::guard('c_user')->user();
        $kyc = CustomersKyc::where('customers_id', $user->id)->first();

        $status = 'none';
        if ($kyc) {
            $status = [CustomersKyc::STATUS_PENDING => 'pending', CustomersKyc::STATUS_APPROVED => 'approved', CustomersKyc::STATUS_REJECTED => 'rejected'][$kyc->status];
        }
        // วันหมดเขต: ยังไม่เคยส่ง = นับจากวันเปิดฟีเจอร์, ถูกตีกลับ = นับจากวันที่ไม่อนุมัติ
        $start = $kyc ? ($kyc->approved_at ?: $kyc->updated_at) : config('kyc.start_at');
        $deadline = $start ? \Carbon\Carbon::parse($start)->addDays(config('kyc.grace_days')) : null;

        return view('frontend.kyc', [
            'status' => $status,
            'note' => $kyc->note ?? null,
            'deadline' => $deadline,
            'user' => $user,
            'banks' => DB::table('dataset_bank')->orderBy('name')->get(),
        ]);
    }

    // OTP (mock): สุ่มเลข 6 หลักแล้วส่งกลับไปแสดงบนหน้าจอ ยังไม่ได้ส่ง SMS จริง
    // เมื่อต่อผู้ให้บริการ SMS ให้ส่ง $otp ทาง SMS และเลิกส่งค่า mock_otp กลับ
    public function sendOtp(Request $request)
    {
        $user = Auth::guard('c_user')->user();
        $validator = Validator::make($request->all(), [
            'kyc_phone' => 'required|digits:10',
        ], [
            'kyc_phone.required' => 'กรุณากรอกเบอร์โทร',
            'kyc_phone.digits' => 'เบอร์โทรต้องเป็นตัวเลข 10 หลัก',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        Cache::put($this->otpKey($user->id, $request->kyc_phone), $otp, now()->addMinutes(5));

        return response()->json(['status' => 'success', 'mock_otp' => $otp]);
    }

    private function otpKey($customersId, $phone)
    {
        return 'kyc_otp_' . $customersId . '_' . $phone;
    }

    public function submit(Request $request)
    {
        $user = Auth::guard('c_user')->user();

        $validator = Validator::make($request->all(), [
            'kyc_id_card' => 'required|digits:13',
            'kyc_file_card' => 'required|mimes:jpeg,jpg,png|max:5120',
            'kyc_bank_name' => 'required',
            'kyc_bank_no' => 'required|numeric',
            'kyc_file_bank' => 'required|mimes:jpeg,jpg,png|max:5120',
            'kyc_phone' => 'required|digits:10',
            'kyc_otp' => 'required|digits:6',
            'kyc_accept' => 'required',
        ], [
            'kyc_id_card.required' => 'กรุณากรอกเลขบัตรประชาชน',
            'kyc_id_card.digits' => 'เลขบัตรประชาชนต้องเป็นตัวเลข 13 หลัก',
            'kyc_file_card.required' => 'กรุณาแนบรูปบัตรประชาชน',
            'kyc_file_card.mimes' => 'รองรับไฟล์ jpeg,jpg,png เท่านั้น',
            'kyc_file_card.max' => 'ไฟล์ต้องไม่เกิน 5 MB',
            'kyc_bank_name.required' => 'กรุณาเลือกธนาคาร',
            'kyc_bank_no.required' => 'กรุณากรอกเลขบัญชี',
            'kyc_bank_no.numeric' => 'ใส่เฉพาะตัวเลขเท่านั้น',
            'kyc_file_bank.required' => 'กรุณาแนบรูปหน้าบัญชี',
            'kyc_file_bank.mimes' => 'รองรับไฟล์ jpeg,jpg,png เท่านั้น',
            'kyc_file_bank.max' => 'ไฟล์ต้องไม่เกิน 5 MB',
            'kyc_phone.required' => 'กรุณากรอกเบอร์โทร',
            'kyc_phone.digits' => 'เบอร์โทรต้องเป็นตัวเลข 10 หลัก',
            'kyc_otp.required' => 'กรุณากรอก OTP',
            'kyc_otp.digits' => 'OTP ต้องเป็นตัวเลข 6 หลัก',
            'kyc_accept.required' => 'กรุณายอมรับข้อตกลง',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()]);
        }

        $otpKey = $this->otpKey($user->id, $request->kyc_phone);
        if (Cache::get($otpKey) !== $request->kyc_otp) {
            return response()->json(['error' => ['kyc_otp' => ['OTP ไม่ถูกต้องหรือหมดอายุ']]]);
        }

        $existing = CustomersKyc::where('customers_id', $user->id)->first();
        if ($existing && $existing->status != CustomersKyc::STATUS_REJECTED) {
            return response()->json(['error' => ['kyc_all' => ['คุณส่งข้อมูลยืนยันตัวตนแล้ว']]]);
        }

        // 1 เลข/เบอร์/บัญชี = 1 รหัส (ตรวจกับข้อมูลที่ยืนยันแล้วของรหัสอื่น และข้อมูลสมัครเดิม)
        $errors = [];
        $other = CustomersKyc::where('customers_id', '!=', $user->id);
        if ((clone $other)->where('id_card', $request->kyc_id_card)->exists()
            || Customers::where('id_card', $request->kyc_id_card)->where('id', '!=', $user->id)->exists()) {
            $errors['kyc_id_card'] = ['เลขบัตรประชาชนนี้ถูกใช้งานแล้ว'];
        }
        if ((clone $other)->where('phone', $request->kyc_phone)->exists()
            || Customers::where('phone', $request->kyc_phone)->where('id', '!=', $user->id)->exists()) {
            $errors['kyc_phone'] = ['เบอร์โทรนี้ถูกใช้งานแล้ว'];
        }
        if ((clone $other)->where('bank_no', $request->kyc_bank_no)->exists()
            || CustomersBank::where('bank_no', $request->kyc_bank_no)->where('customers_id', '!=', $user->id)->exists()) {
            $errors['kyc_bank_no'] = ['เลขบัญชีนี้ถูกใช้งานแล้ว'];
        }
        if ($errors) {
            return response()->json(['error' => $errors]);
        }

        $bank = DB::table('dataset_bank')->where('id', $request->kyc_bank_name)->first();
        if (!$bank) {
            return response()->json(['error' => ['kyc_bank_name' => ['กรุณาเลือกธนาคาร']]]);
        }

        $dir = 'images/customers_kyc/' . date('Ym');
        $cardFile = date('YmdHis') . '.' . $user->id . '.card.' . $request->file('kyc_file_card')->extension();
        $bankFile = date('YmdHis') . '.' . $user->id . '.bank.' . $request->file('kyc_file_bank')->extension();
        $request->file('kyc_file_card')->move($dir, $cardFile);
        $request->file('kyc_file_bank')->move($dir, $bankFile);

        // ส่งใหม่หลังถูกตีกลับ: เก็บข้อมูลและรูปรอบก่อนไว้ในประวัติ (ไม่ลบไฟล์เก่า)
        if ($existing) {
            DB::table('customers_kyc_history')->insert([
                'customers_id' => $existing->customers_id,
                'user_name' => $existing->user_name,
                'id_card' => $existing->id_card,
                'img_card' => $existing->img_card,
                'bank_no' => $existing->bank_no,
                'bank_name' => $existing->bank_name,
                'img_bank' => $existing->img_bank,
                'phone' => $existing->phone,
                'status' => $existing->status,
                'note' => $existing->note,
                'approved_by' => $existing->approved_by,
                'submitted_at' => $existing->created_at,
                'decided_at' => $existing->approved_at,
                'created_at' => now(),
            ]);
        }

        CustomersKyc::updateOrCreate(['customers_id' => $user->id], [
            'user_name' => $user->user_name,
            'id_card' => $request->kyc_id_card,
            'img_card' => $dir . '/' . $cardFile,
            'bank_no' => $request->kyc_bank_no,
            'bank_id_fk' => $bank->id,
            'bank_name' => $bank->name,
            'img_bank' => $dir . '/' . $bankFile,
            'phone' => $request->kyc_phone,
            'terms_accepted_at' => now(),
            'status' => CustomersKyc::STATUS_PENDING,
            'note' => null,
        ]);

        Cache::forget($otpKey);

        return response()->json(['status' => 'success'], 200);
    }
}
