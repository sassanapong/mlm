<?php

// 1 Code 1 World: คัดกรองสมาชิก
// KYC_ENABLED=true เปิดฟีเจอร์: สมาชิกที่ยังไม่ส่งข้อมูล/ถูกตีกลับ จะถูกพาไปหน้า kyc และใช้หน้าอื่นไม่ได้จนกว่าจะส่ง
// KYC_START_AT = วัน-เวลาที่เปิด (ใช้แสดงวันหมดเขต 3 วัน) การระงับรหัสเมื่อครบกำหนดทำด้วย SQL ด้วยมือ
return [
    'enabled' => env('KYC_ENABLED', false),
    'start_at' => env('KYC_START_AT'),
    'grace_days' => 3,

    // route ที่ใช้ได้แม้ยังไม่ยืนยัน
    'allow_routes' => ['kyc', 'kyc_submit', 'kyc_send_otp', 'logout', 'changeLang'],
];
