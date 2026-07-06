<?php

return [
    'merchant_id' => env('PAYSO_MERCHANT_ID', '97453784'),
    'api_key' => env('PAYSO_API_KEY'),
    'secret_key' => env('PAYSO_SECRET_KEY'),
    'payment_url' => env('PAYSO_PAYMENT_URL', 'https://payments.paysolutions.asia/payment'),
    'inquiry_url' => env('PAYSO_INQUIRY_URL'),
    'return_url' => env('PAYSO_RETURN_URL', env('APP_URL') . '/payment/payso/return'),
    'postback_url' => env('PAYSO_POSTBACK_URL', env('APP_URL') . '/api/payment/payso/postback'),
    'product_detail' => env('PAYSO_PRODUCT_DETAIL', 'eWallet Deposit'),
    'default_customer_email' => env('PAYSO_DEFAULT_CUSTOMER_EMAIL', 'no-reply@maruay.co.th'),
    'currency_code' => env('PAYSO_CURRENCY_CODE', '00'),
    'lang' => env('PAYSO_LANG', 'TH'),
    'require_signature' => env('APP_ENV') === 'production' || env('PAYSO_REQUIRE_SIGNATURE', false),
    'signature_header' => env('PAYSO_SIGNATURE_HEADER', 'X-PaySo-Signature'),
    'signature_field' => env('PAYSO_SIGNATURE_FIELD', 'signature'),
    'signature_algorithm' => env('PAYSO_SIGNATURE_ALGORITHM', 'sha256'),
    'signature_fields' => array_filter(array_map('trim', explode(',', env('PAYSO_SIGNATURE_FIELDS', 'refno,total,order_status')))),
];
