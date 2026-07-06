<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaySoOrderController extends Controller
{
    public function postback(Request $request)
    {
        $payload = $request->all();
        Log::channel('payment')->info('PaySo order postback received', $this->capturePaySoRequest($request));

        $reference = $this->getReference($request);
        if (empty($reference)) {
            Log::channel('payment')->warning('PaySo order postback missing reference', ['payload' => $payload]);
            return response()->json(['status' => 'error', 'message' => 'reference not found'], 404);
        }

        if (!$this->signatureIsValid($request)) {
            Log::channel('payment')->warning('PaySo order postback signature invalid', [
                'reference' => $reference,
                'payload' => $payload,
            ]);
            return response()->json(['status' => 'error', 'message' => 'invalid signature'], 422);
        }

        $order = Orders::where('payso_refno', $reference)
            ->where('payment_gateway', 'payso')
            ->first();

        if (!$order) {
            Log::channel('payment')->warning('PaySo order not found', ['reference' => $reference]);
            return response()->json(['status' => 'error', 'message' => 'order not found'], 404);
        }

        if ((string) $order->order_status_id_fk === '5') {
            Log::channel('payment')->info('PaySo duplicate paid order postback ignored', [
                'reference' => $reference,
                'code_order' => $order->code_order,
            ]);
            return response()->json(['status' => 'success', 'message' => 'already paid']);
        }

        $status = $this->getGatewayStatus($request);
        $amount = $this->getGatewayAmount($request);
        $gatewayTransactionId = $this->getGatewayTransactionId($request);
        $gatewayPayload = json_encode($payload, JSON_UNESCAPED_UNICODE);

        if ($amount !== null && round((float) $amount, 2) !== round((float) $order->total_price, 2)) {
            $order->gateway_status = $status;
            $order->gateway_payload = $gatewayPayload;
            $order->save();

            Log::channel('payment')->warning('PaySo order amount mismatch', [
                'reference' => $reference,
                'code_order' => $order->code_order,
                'order_amount' => $order->total_price,
                'gateway_amount' => $amount,
            ]);

            return response()->json(['status' => 'error', 'message' => 'amount mismatch'], 422);
        }

        if ($this->isFailedStatus($status)) {
            $order->order_status_id_fk = 2;
            $order->gateway_transaction_id = $gatewayTransactionId;
            $order->gateway_status = $status;
            $order->gateway_payload = $gatewayPayload;
            $order->save();

            return response()->json(['status' => 'success']);
        }

        if (!$this->isSuccessStatus($status)) {
            $order->gateway_transaction_id = $gatewayTransactionId;
            $order->gateway_status = $status ?: 'pending';
            $order->gateway_payload = $gatewayPayload;
            $order->save();

            return response()->json(['status' => 'success', 'message' => 'pending']);
        }

        $checkPro2 = $this->orderHasPromotion2($order->code_order);
        $paymentResult = (new ConfirmCartController())->run_payment($order->code_order, $checkPro2, 'payso', [
            'gateway_transaction_id' => $gatewayTransactionId,
            'gateway_status' => $status,
            'gateway_payload' => $gatewayPayload,
            'paid_at' => now()->format('Y-m-d H:i:s'),
        ]);

        if ($paymentResult['status'] !== 'success') {
            Log::channel('payment')->error('PaySo order paid but order update failed', [
                'reference' => $reference,
                'code_order' => $order->code_order,
                'message' => $paymentResult['message'] ?? null,
            ]);

            return response()->json(['status' => 'error', 'message' => $paymentResult['message'] ?? 'order update failed'], 500);
        }

        if ($checkPro2) {
            $bonusResult = ConfirmCartController::runbonus_faststart($order->customers_user_name, $order->pv_total);
            if ($bonusResult['status'] !== 'success') {
                Log::channel('payment')->error('PaySo order faststart bonus failed', [
                    'reference' => $reference,
                    'code_order' => $order->code_order,
                    'message' => $bonusResult['message'] ?? null,
                ]);
            }
        }

        Log::channel('payment')->info('PaySo order paid and updated', [
            'reference' => $reference,
            'code_order' => $order->code_order,
            'gateway_transaction_id' => $gatewayTransactionId,
        ]);

        return response()->json(['status' => 'success']);
    }

    public function paysoReturn(Request $request)
    {
        $reference = $this->getReference($request);
        $order = $this->resolveReturnOrder($request, $reference);

        Log::channel('payment')->info('PaySo order return received', array_merge($this->capturePaySoRequest($request), [
            'reference' => $reference,
            'resolved_reference' => $order ? $order->payso_refno : null,
            'resolved_code_order' => $order ? $order->code_order : null,
        ]));

        if (!$order) {
            return redirect('order_history')->withSuccess('ระบบกำลังตรวจสอบการชำระเงิน');
        }

        if ((string) $order->order_status_id_fk === '5') {
            $request->session()->forget('payso_order_refno');
            $request->session()->forget('payso_order_code');
            return redirect('order_history')->withSuccess('ชำระเงินสำเร็จ');
        }

        if ((string) $order->order_status_id_fk === '2') {
            $request->session()->forget('payso_order_refno');
            $request->session()->forget('payso_order_code');
            return redirect('order_history')->withError('ชำระเงินไม่สำเร็จ');
        }

        return redirect('order_history')->withSuccess('ระบบกำลังตรวจสอบการชำระเงิน');
    }

    public static function hasOrderReference($reference)
    {
        if (empty($reference)) {
            return false;
        }

        return Orders::where('payso_refno', $reference)
            ->where('payment_gateway', 'payso')
            ->exists();
    }

    public static function hasSessionOrder(Request $request)
    {
        return !empty($request->session()->get('payso_order_refno'));
    }

    private function resolveReturnOrder(Request $request, $reference)
    {
        if (!empty($reference)) {
            $order = Orders::where('payso_refno', $reference)
                ->where('payment_gateway', 'payso')
                ->first();

            if ($order) {
                return $order;
            }
        }

        $sessionReference = $request->session()->get('payso_order_refno');
        if (!empty($sessionReference)) {
            return Orders::where('payso_refno', $sessionReference)
                ->where('payment_gateway', 'payso')
                ->first();
        }

        if (Auth::guard('c_user')->check()) {
            return Orders::where('customers_id_fk', Auth::guard('c_user')->user()->id)
                ->where('payment_gateway', 'payso')
                ->where('created_at', '>=', now()->subHours(2))
                ->orderBy('id', 'DESC')
                ->first();
        }

        return null;
    }

    private function orderHasPromotion2($codeOrder)
    {
        return DB::table('db_order_products_list')
            ->where('code_order', $codeOrder)
            ->where('product_name', 'like', '$%')
            ->exists();
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
}
