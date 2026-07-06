<?php

namespace App\Http\Controllers\Frontend;

use App\Customers;
use App\eWallet;
use App\eWallet_tranfer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaySoWalletController extends Controller
{
    public function __construct()
    {
        $this->middleware('customer')->only(['create', 'checkStatus']);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'amount' => 'required|numeric|gt:0',
        ], [
            'amount.required' => 'กรุณากรอกจำนวนเงิน',
            'amount.numeric' => 'จำนวนเงินไม่ถูกต้อง',
            'amount.gt' => 'จำนวนเงินต้องมากกว่า 0 บาท',
        ]);

        if ($validator->fails()) {
            return redirect('home')->withError($validator->errors()->first('amount'));
        }

        $paymentUrl = config('payso.payment_url');

        if (empty($paymentUrl)) {
            Log::channel('payment')->error('PaySo payment URL is not configured');
            return redirect('home')->withError('ยังไม่ได้ตั้งค่า PaySo Payment URL');
        }

        $customer = Auth::guard('c_user')->user();
        $amount = round((float) $request->amount, 2);
        $transactionCode = $this->generatePaySoRefNo();

        $deposit = DB::transaction(function () use ($customer, $amount, $transactionCode, $request) {
            $record = eWallet_tranfer::create([
                'transaction_code' => $transactionCode,
                'customers_id_fk' => $customer->id,
                'customer_username' => $customer->user_name,
                'amt' => $amount,
                'type' => 1,
                'status' => 1,
                'pay_type' => 'Bank',
                'payment_method' => 'payso',
                'payment_gateway' => 'payso',
                'gateway_status' => 'pending',
                'gateway_payload' => json_encode([
                    'source' => 'create',
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ], JSON_UNESCAPED_UNICODE),
            ]);

            Log::channel('payment')->info('PaySo deposit created', [
                'ewallet_tranfer_id' => $record->id,
                'transaction_code' => $record->transaction_code,
                'customers_id_fk' => $record->customers_id_fk,
                'amount' => $record->amt,
            ]);

            return $record;
        });

        $payload = $this->buildPaymentPayload($deposit);
        $request->session()->put('payso_last_refno', $deposit->transaction_code);

        Log::channel('payment')->info('Redirecting customer to PaySo', [
            'transaction_code' => $deposit->transaction_code,
            'amount' => $deposit->amt,
            'payment_url' => $paymentUrl,
            'payload' => $payload,
        ]);

        return view('frontend.payso.redirect', [
            'paymentUrl' => $paymentUrl,
            'payload' => $payload,
        ]);
    }

    public function postback(Request $request)
    {
        $payload = $request->all();
        Log::channel('payment')->info('PaySo postback received', $this->capturePaySoRequest($request));

        $reference = $this->getReference($request);
        if (empty($reference)) {
            Log::channel('payment')->warning('PaySo postback missing reference', ['payload' => $payload]);
            return response()->json(['status' => 'error', 'message' => 'reference not found'], 404);
        }

        if (!$this->signatureIsValid($request)) {
            Log::channel('payment')->warning('PaySo postback signature invalid', [
                'reference' => $reference,
                'payload' => $payload,
            ]);
            return response()->json(['status' => 'error', 'message' => 'invalid signature'], 422);
        }

        $depositExists = eWallet_tranfer::where('transaction_code', $reference)
            ->where('payment_gateway', 'payso')
            ->exists();

        if (!$depositExists && PaySoOrderController::hasOrderReference($reference)) {
            return app(PaySoOrderController::class)->postback($request);
        }

        $status = $this->getGatewayStatus($request);
        $amount = $this->getGatewayAmount($request);
        $gatewayTransactionId = $this->getGatewayTransactionId($request);

        $result = DB::transaction(function () use ($reference, $payload, $status, $amount, $gatewayTransactionId) {
            $deposit = eWallet_tranfer::where('transaction_code', $reference)
                ->where('payment_gateway', 'payso')
                ->lockForUpdate()
                ->first();

            if (!$deposit) {
                Log::channel('payment')->warning('PaySo deposit not found', ['reference' => $reference]);
                return ['http' => 404, 'body' => ['status' => 'error', 'message' => 'transaction not found']];
            }

            if ((string) $deposit->status === '2') {
                Log::channel('payment')->info('PaySo duplicate paid postback ignored', [
                    'reference' => $reference,
                    'ewallet_tranfer_id' => $deposit->id,
                ]);
                return ['http' => 200, 'body' => ['status' => 'success', 'message' => 'already paid']];
            }

            if ($amount !== null && round((float) $amount, 2) !== round((float) $deposit->amt, 2)) {
                $deposit->gateway_status = $status;
                $deposit->gateway_payload = json_encode($payload, JSON_UNESCAPED_UNICODE);
                $deposit->save();

                Log::channel('payment')->warning('PaySo amount mismatch', [
                    'reference' => $reference,
                    'deposit_amount' => $deposit->amt,
                    'gateway_amount' => $amount,
                ]);

                return ['http' => 422, 'body' => ['status' => 'error', 'message' => 'amount mismatch']];
            }

            if ($this->isSuccessStatus($status)) {
                $customer = Customers::where('id', $deposit->customers_id_fk)->lockForUpdate()->first();
                if (!$customer) {
                    return ['http' => 404, 'body' => ['status' => 'error', 'message' => 'customer not found']];
                }

                $oldBalance = (float) $customer->ewallet;
                $oldTransferBalance = (float) $customer->ewallet_tranfer;
                $depositAmount = (float) $deposit->amt;
                $paidAt = now();

                $deposit->receive_date = $paidAt->format('Y-m-d');
                $deposit->receive_time = $paidAt->format('H:i:s');
                $deposit->code_refer = $gatewayTransactionId ?: $reference;
                $deposit->old_balance = $oldBalance;
                $deposit->balance = $oldBalance + $depositAmount;
                $deposit->date_mark = $paidAt->format('Y-m-d H:i:s');
                $deposit->status = 2;
                $deposit->gateway_transaction_id = $gatewayTransactionId;
                $deposit->gateway_status = $status;
                $deposit->gateway_payload = json_encode($payload, JSON_UNESCAPED_UNICODE);
                $deposit->paid_at = $paidAt->format('Y-m-d H:i:s');
                $deposit->save();

                eWallet::create([
                    'transaction_code' => $deposit->transaction_code,
                    'customers_id_fk' => $deposit->customers_id_fk,
                    'customer_username' => $deposit->customer_username,
                    'url' => $deposit->url,
                    'file_ewllet' => $deposit->file_ewllet,
                    'amt' => $deposit->amt,
                    'receive_date' => $deposit->receive_date,
                    'receive_time' => $deposit->receive_time,
                    'code_refer' => $deposit->code_refer,
                    'old_balance' => $oldBalance,
                    'balance' => $oldBalance + $depositAmount,
                    'edit_amt' => 0,
                    'date_mark' => $deposit->date_mark,
                    'type' => $deposit->type,
                    'status' => 2,
                    'payment_method' => 'payso',
                    'payment_gateway' => 'payso',
                    'gateway_transaction_id' => $gatewayTransactionId,
                    'gateway_status' => $status,
                    'gateway_payload' => json_encode($payload, JSON_UNESCAPED_UNICODE),
                    'paid_at' => $paidAt->format('Y-m-d H:i:s'),
                ]);

                $customer->ewallet = $oldBalance + $depositAmount;
                $customer->ewallet_tranfer = $oldTransferBalance + $depositAmount;
                $customer->save();

                Log::channel('payment')->info('PaySo postback paid and eWallet updated', [
                    'reference' => $reference,
                    'customers_id_fk' => $customer->id,
                    'amount' => $depositAmount,
                    'old_balance' => $oldBalance,
                    'new_balance' => $customer->ewallet,
                ]);

                return ['http' => 200, 'body' => ['status' => 'success']];
            }

            if ($this->isFailedStatus($status)) {
                $deposit->status = 3;
                $deposit->gateway_transaction_id = $gatewayTransactionId;
                $deposit->gateway_status = $status;
                $deposit->gateway_payload = json_encode($payload, JSON_UNESCAPED_UNICODE);
                $deposit->date_mark = now()->format('Y-m-d H:i:s');
                $deposit->save();

                Log::channel('payment')->info('PaySo postback marked failed', [
                    'reference' => $reference,
                    'status' => $status,
                ]);

                return ['http' => 200, 'body' => ['status' => 'success']];
            }

            $deposit->gateway_status = $status ?: 'pending';
            $deposit->gateway_payload = json_encode($payload, JSON_UNESCAPED_UNICODE);
            $deposit->save();

            return ['http' => 200, 'body' => ['status' => 'success', 'message' => 'pending']];
        });

        return response()->json($result['body'], $result['http']);
    }

    public function checkStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|integer',
        ]);

        $deposit = eWallet_tranfer::where('id', $request->id)
            ->where('customers_id_fk', Auth::guard('c_user')->user()->id)
            ->where('payment_gateway', 'payso')
            ->first();

        if (!$deposit) {
            return response()->json([
                'status' => 'error',
                'message' => 'ไม่พบรายการ PaySolutions',
            ], 404);
        }

        if ((string) $deposit->status === '2') {
            return response()->json([
                'status' => 'paid',
                'message' => 'รายการนี้ชำระสำเร็จแล้ว',
            ]);
        }

        $inquiryUrl = config('payso.inquiry_url');
        if (empty($inquiryUrl)) {
            Log::channel('payment')->info('PaySo inquiry skipped because inquiry URL is not configured', [
                'transaction_code' => $deposit->transaction_code,
                'ewallet_tranfer_id' => $deposit->id,
            ]);

            return response()->json([
                'status' => 'pending',
                'message' => 'ยังไม่พบ API สำหรับเช็คสถานะจาก PaySolutions ในเอกสาร public ตอนนี้ กรุณารอ Postback หรือเพิ่ม PAYSO_INQUIRY_URL เมื่อได้ endpoint จาก PaySo',
            ]);
        }

        $payload = [
            'merchantid' => config('payso.merchant_id'),
            'refno' => $deposit->transaction_code,
            'total' => $this->formatPaySoTotal($deposit->amt),
            'api_key' => config('payso.api_key'),
        ];

        Log::channel('payment')->info('PaySo inquiry request', [
            'url' => $inquiryUrl,
            'payload' => $payload,
        ]);

        try {
            $response = Http::asForm()->timeout(15)->post($inquiryUrl, $payload);
        } catch (\Exception $e) {
            Log::channel('payment')->error('PaySo inquiry request failed', [
                'transaction_code' => $deposit->transaction_code,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'เชื่อมต่อ PaySolutions เพื่อตรวจสอบสถานะไม่สำเร็จ',
            ], 500);
        }

        $gatewayPayload = $this->parseInquiryResponse($response);
        $gatewayPayload['refno'] = $gatewayPayload['refno'] ?? $deposit->transaction_code;
        $gatewayPayload['total'] = $gatewayPayload['total'] ?? $this->formatPaySoTotal($deposit->amt);

        Log::channel('payment')->info('PaySo inquiry response', [
            'transaction_code' => $deposit->transaction_code,
            'http_status' => $response->status(),
            'payload' => $gatewayPayload,
        ]);

        if (!$response->successful()) {
            return response()->json([
                'status' => 'pending',
                'message' => 'PaySolutions ยังไม่ตอบสถานะสำเร็จ กรุณารอ Postback',
            ]);
        }

        $statusRequest = Request::create('/api/payment/payso/postback', 'POST', $gatewayPayload);
        $signature = $this->makeFieldSignature($statusRequest);
        if ($signature) {
            $statusRequest->request->set(config('payso.signature_field'), $signature);
        }

        $this->postback($statusRequest);

        $deposit->refresh();

        if ((string) $deposit->status === '2') {
            return response()->json([
                'status' => 'paid',
                'message' => 'ตรวจสอบแล้วพบว่าชำระสำเร็จ ระบบอัปเดต eWallet แล้ว',
            ]);
        }

        if ((string) $deposit->status === '3') {
            return response()->json([
                'status' => 'failed',
                'message' => 'ตรวจสอบแล้วพบว่าชำระไม่สำเร็จ',
            ]);
        }

        return response()->json([
            'status' => 'pending',
            'message' => 'ยังไม่พบสถานะชำระสำเร็จจาก PaySolutions',
        ]);
    }

    public function paysoReturn(Request $request)
    {
        $payload = $request->all();
        $reference = $this->getReference($request);

        if (PaySoOrderController::hasOrderReference($reference) || PaySoOrderController::hasSessionOrder($request)) {
            return app(PaySoOrderController::class)->paysoReturn($request);
        }

        $deposit = $this->resolveReturnDeposit($request, $reference);

        Log::channel('payment')->info('PaySo return received', array_merge($this->capturePaySoRequest($request), [
            'reference' => $reference,
            'resolved_reference' => $deposit ? $deposit->transaction_code : null,
            'resolved_ewallet_tranfer_id' => $deposit ? $deposit->id : null,
        ]));

        if (!$deposit) {
            return redirect('eWallet-TranferHistory')->withSuccess('ระบบกำลังตรวจสอบการชำระเงิน');
        }

        if ((string) $deposit->status === '2') {
            $request->session()->forget('payso_last_refno');
            return redirect('home')->withSuccess('ฝากเงินสำเร็จ');
        }

        if ((string) $deposit->status === '3') {
            $request->session()->forget('payso_last_refno');
            return redirect('home')->withError('ชำระเงินไม่สำเร็จ');
        }

        return redirect('eWallet-TranferHistory')->withSuccess('ระบบกำลังตรวจสอบการชำระเงิน');
    }

    private function resolveReturnDeposit(Request $request, $reference)
    {
        if (!empty($reference)) {
            return eWallet_tranfer::where('transaction_code', $reference)
                ->where('payment_gateway', 'payso')
                ->first();
        }

        $sessionReference = $request->session()->get('payso_last_refno');
        if (!empty($sessionReference)) {
            $deposit = eWallet_tranfer::where('transaction_code', $sessionReference)
                ->where('payment_gateway', 'payso')
                ->first();

            if ($deposit) {
                return $deposit;
            }
        }

        if (Auth::guard('c_user')->check()) {
            return eWallet_tranfer::where('customers_id_fk', Auth::guard('c_user')->user()->id)
                ->where('payment_gateway', 'payso')
                ->where('created_at', '>=', now()->subHours(2))
                ->orderBy('id', 'DESC')
                ->first();
        }

        return null;
    }

    private function buildPaymentPayload(eWallet_tranfer $deposit)
    {
        return [
            'customeremail' => $this->customerEmail($deposit),
            'productdetail' => config('payso.product_detail', 'eWallet Deposit'),
            'refno' => $deposit->transaction_code,
            'merchantid' => config('payso.merchant_id'),
            'cc' => config('payso.currency_code', '00'),
            'total' => $this->formatPaySoTotal($deposit->amt),
            'lang' => config('payso.lang', 'TH'),
        ];
    }

    private function capturePaySoRequest(Request $request)
    {
        return [
            'method' => $request->method(),
            'full_url' => $request->fullUrl(),
            'path' => $request->path(),
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'query' => $request->query(),
            'input' => $request->all(),
            'raw_body' => $request->getContent(),
            'headers' => $this->safeHeaders($request),
        ];
    }

    private function safeHeaders(Request $request)
    {
        $headers = [];
        foreach ($request->headers->all() as $key => $value) {
            if (in_array(strtolower($key), ['cookie', 'authorization', 'php-auth-pw'], true)) {
                $headers[$key] = ['[hidden]'];
                continue;
            }

            $headers[$key] = $value;
        }

        return $headers;
    }

    private function generatePaySoRefNo()
    {
        do {
            $refNo = date('ymdHi') . random_int(10, 99);
        } while (eWallet_tranfer::where('transaction_code', $refNo)->exists());

        return $refNo;
    }

    private function formatPaySoTotal($amount)
    {
        $amount = round((float) $amount, 2);
        if (floor($amount) == $amount) {
            return (string) (int) $amount;
        }

        return rtrim(rtrim(number_format($amount, 2, '.', ''), '0'), '.');
    }

    private function getReference(Request $request)
    {
        foreach (['refno', 'merchant_order_id', 'reference', 'order_id', 'invoice_no', 'transaction_code'] as $key) {
            if ($request->filled($key)) {
                return $request->input($key);
            }
        }

        return null;
    }

    private function getGatewayStatus(Request $request)
    {
        foreach (['order_status', 'status', 'statusname', 'payment_status', 'gateway_status'] as $key) {
            if ($request->filled($key)) {
                return (string) $request->input($key);
            }
        }

        return null;
    }

    private function getGatewayAmount(Request $request)
    {
        foreach (['total', 'amount', 'pay_amount', 'total_amount'] as $key) {
            if ($request->filled($key)) {
                return $request->input($key);
            }
        }

        return null;
    }

    private function customerEmail(eWallet_tranfer $deposit)
    {
        $customer = Customers::select('email')->where('id', $deposit->customers_id_fk)->first();
        if ($customer && filter_var($customer->email, FILTER_VALIDATE_EMAIL)) {
            return $customer->email;
        }

        return config('payso.default_customer_email', 'no-reply@maruay.co.th');
    }

    private function getGatewayTransactionId(Request $request)
    {
        foreach (['orderno', 'gateway_transaction_id', 'transaction_id', 'payso_transaction_id', 'payment_id', 'trans_id'] as $key) {
            if ($request->filled($key)) {
                return $request->input($key);
            }
        }

        return null;
    }

    private function isSuccessStatus($status)
    {
        return in_array(strtolower((string) $status), ['ps', 'cp', 'success', 'paid', 'complete', 'completed'], true);
    }

    private function isFailedStatus($status)
    {
        return in_array(strtolower((string) $status), ['pf', 'fail', 'failed', 'cancel', 'cancelled', 'canceled'], true);
    }

    private function signatureIsValid(Request $request)
    {
        $signature = $request->header(config('payso.signature_header')) ?: $request->input(config('payso.signature_field'));

        if (empty($signature)) {
            return !config('payso.require_signature');
        }

        $secret = config('payso.secret_key');
        if (empty($secret)) {
            return false;
        }

        $algorithm = config('payso.signature_algorithm', 'sha256');
        $rawExpected = hash_hmac($algorithm, $request->getContent(), $secret);

        $parts = [];
        foreach (config('payso.signature_fields', []) as $field) {
            $parts[] = (string) $request->input($field, '');
        }
        $fieldExpected = hash_hmac($algorithm, implode('|', $parts), $secret);

        return hash_equals($rawExpected, $signature) || hash_equals($fieldExpected, $signature);
    }

    private function parseInquiryResponse($response)
    {
        $data = $response->json();
        if (is_array($data)) {
            return $data;
        }

        parse_str($response->body(), $parsed);
        if (is_array($parsed) && count($parsed)) {
            return $parsed;
        }

        return [
            'raw_response' => $response->body(),
        ];
    }

    private function makeFieldSignature(Request $request)
    {
        $secret = config('payso.secret_key');
        if (empty($secret)) {
            return null;
        }

        $parts = [];
        foreach (config('payso.signature_fields', []) as $field) {
            $parts[] = (string) $request->input($field, '');
        }

        return hash_hmac(config('payso.signature_algorithm', 'sha256'), implode('|', $parts), $secret);
    }
}
