<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\CUser;
use DB;
use App\eWallet;
use App\eWallet_tranfer;
use App\Customers;

class ApiFunction3Controller extends Controller
{

    public function deposit(Request $request)
    {
        // Data validation rules and messages
        $rules = [
            'amt' => 'required|numeric|gte:100',
            'upload' => 'required|mimes:jpeg,jpg,png',
            'user_id' => 'required|exists:customers,id',
            'username' => 'required',
        ];

        $messages = [
            'amt.required' => 'กรุณากรอกข้อมูล',
            'amt.numeric' => 'กรุณากรอกเป็นตัวเลขเท่านั้น',
            'amt.gte' => 'ยอดขั้นต่ำในการทำรายการฝาก 100 บาท',
            'upload.required' => 'กรุณาแนบสลิปการโอนเงิน',
            'upload.mimes' => 'รองรับไฟล์นามสกุล jpeg,jpg,png เท่านั้น',
            'user_id.required' => 'กรุณากรอกข้อมูลผู้ใช้',
            'user_id.exists' => 'ไม่พบข้อมูลผู้ใช้ในระบบ',
            'username.required' => 'กรุณากรอกชื่อผู้ใช้',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'ข้อมูลไม่ถูกต้อง',
                'status' => 'error',
                'code' => 'ER01',
                'data' => $validator->errors(),
            ], 404);
        }

        $customers_id_fk = $request->user_id;
        $customers = Customers::where('id', $customers_id_fk)->first();

        // Generate transaction code
        $count_eWallet = \App\Http\Controllers\Frontend\FC\RunCodeController::db_code_wallet();

        if ($request->hasFile('upload')) {
            $url = 'local/public/images/eWllet/deposit/' . date('Ym');
            $imageName = $request->upload->getClientOriginalExtension();
            $filenametostore = date("YmdHis") . $customers_id_fk . "." . $imageName;
            $request->upload->move($url, $filenametostore);

            $dataPrepare = [
                'transaction_code' => $count_eWallet,
                'customers_id_fk' => $customers_id_fk,
                'customer_username' => $customers->user_name,
                'url' => $url,
                'file_ewllet' => $filenametostore,
                'amt' => $request->amt,
                'type' => 1,
                'status' => 1,
            ];

            try {
                DB::beginTransaction();
                eWallet_tranfer::create($dataPrepare);
                DB::commit();

                return response()->json([
                    'message' => 'success',
                    'status' => 'success',
                    'code' => 'S01',
                    'data' => null
                ], 200);
            } catch (Exception $e) {
                DB::rollback();

                return response()->json([
                    'message' => 'เกิดข้อผิดพลาดกรุณาทำรายการไหม่',
                    'status' => 'error',
                    'code' => 'ER02',
                    'data' => null,
                ], 500);
            }
        }

        return response()->json([
            'message' => 'การอัพโหลดล้มเหลว',
            'status' => 'error',
            'code' => 'ER03',
            'data' => null,
        ], 400);
    }
}
