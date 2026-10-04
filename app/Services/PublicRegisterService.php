<?php

namespace App\Services;

use App\Customers;
use App\CustomersAddressCard;
use App\CustomersAddressDelivery;
use App\CustomersBank;
use App\CustomersBenefit;
use App\eWallet;
use App\Http\Controllers\Frontend\FC\RunCodeController;
use App\Http\Controllers\Frontend\ShippingController;
use App\Jang_pv;
use App\Log_insurance;
use App\Order_products_list;
use App\Orders;
use App\RegisterApplication;
use App\Support\PositionService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| PublicRegisterService
|--------------------------------------------------------------------------
|
| ใช้กับหน้าสมัครสมาชิกผ่านลิงก์สาธารณะ (/join)
|
|  - quote()    คำนวณราคา / PV / ค่าส่ง จากรายการสินค้า (ฝั่ง server เสมอ ไม่เชื่อค่าจาก client)
|  - activate() เรียกตอน PaySo ยืนยันการชำระเงิน: สร้างสมาชิก, ออเดอร์, คำนวณตำแหน่งจาก PV,
|               และจ่ายโบนัสแนะนำ 5 ชั้น (ตรรกะเดียวกับ RegisterController::store_register
|               แต่ไม่ตัด PV ของผู้แนะนำ เพราะผู้สมัครจ่ายเงินซื้อสินค้าเอง)
|
*/
class PublicRegisterService
{
    // สินค้าที่ไม่เปิดให้ผู้สมัครใหม่เลือก: หมวดที่จำกัดตามตำแหน่ง (OrderController) และสินค้าโปรที่ขึ้นต้นด้วย $ หรือ #
    const EXCLUDE_CATEGORIES = [1, 8, 11, 13];

    /*
    |--------------------------------------------------------------------------
    | รายการสินค้าที่ให้เลือก
    |--------------------------------------------------------------------------
    */
    public static function products()
    {
        return DB::table('products')
            ->select(
                'products.id as products_id',
                'products.category_id',
                'products_details.product_name',
                'products_details.descriptions',
                'products_images.img_url',
                'products_images.product_img',
                'products_cost.member_price',
                'products_cost.pv'
            )
            ->leftjoin('products_details', 'products.id', '=', 'products_details.product_id_fk')
            ->leftjoin('products_images', 'products.id', '=', 'products_images.product_id_fk')
            ->leftjoin('products_cost', 'products.id', '=', 'products_cost.product_id_fk')
            ->where('products_images.image_default', '=', 1)
            ->where('products_details.lang_id', '=', 1)
            ->where('products.status', '=', 1)
            ->where('products_cost.business_location_id', '=', 1)
            ->whereNotIn('products.category_id', self::EXCLUDE_CATEGORIES)
            ->where('products_details.product_name', 'not like', '$%')
            ->where('products_details.product_name', 'not like', '#%')
            ->orderby('products.id')
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | คำนวณราคา / PV / ค่าส่ง
    |--------------------------------------------------------------------------
    |
    | $items = [['id' => 1, 'qty' => 2], ...]
    |
    */
    public static function quote(array $items, $zipcode = null)
    {
        $allowed = self::products()->keyBy('products_id');

        $lines = [];
        $sum_price = 0;
        $pv_total = 0;
        $pv_shipping = 0;
        $quantity = 0;

        foreach ($items as $item) {
            $id = (int) ($item['id'] ?? 0);
            $qty = (int) ($item['qty'] ?? 0);

            if ($qty < 1 || $qty > 999 || !isset($allowed[$id])) {
                continue;
            }

            $p = $allowed[$id];
            $line_price = (float) $p->member_price * $qty;
            $line_pv = (float) $p->pv * $qty;

            $lines[] = [
                'id' => $id,
                'name' => $p->product_name,
                'qty' => $qty,
                'price' => (float) $p->member_price,
                'pv' => (float) $p->pv,
                'total_price' => $line_price,
                'total_pv' => $line_pv,
            ];

            $sum_price += $line_price;
            $pv_total += $line_pv;
            $quantity += $qty;

            $has_shipping = DB::table('products_cost')
                ->where('product_id_fk', $id)
                ->where('status_shipping', 'Y')
                ->exists();
            if ($has_shipping) {
                $pv_shipping += $qty * 20;
            }
        }

        $shipping = ShippingController::fc_shipping($pv_shipping);
        if (!empty($zipcode)) {
            $shipping += ShippingController::fc_shipping_zip_code($zipcode)['price'];
        }

        return [
            'lines' => $lines,
            'quantity' => $quantity,
            'sum_price' => round($sum_price, 2),
            'pv_total' => round($pv_total, 2),
            'shipping' => (float) $shipping,
            'total_price' => round($sum_price + $shipping, 2),
            'position' => PositionService::fromRegisterPv($pv_total),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | ตรวจว่า $upline อยู่ในสายงานของ $sponsor (หรือเป็นตัว sponsor เอง)
    |--------------------------------------------------------------------------
    */
    public static function uplineInSponsorTeam($sponsor, $upline)
    {
        $current = $upline;
        for ($i = 0; $i < 2000 && !empty($current); $i++) {
            if ($current === $sponsor) {
                return true;
            }
            $row = DB::table('customers')->select('upline_id')->where('user_name', $current)->first();
            if (!$row) {
                return false;
            }
            $current = $row->upline_id;
        }

        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | หาตำแหน่งวางสายงานตามขาที่เลือก (A หรือ B)
    |--------------------------------------------------------------------------
    |
    | เริ่มที่ผู้แนะนำ วิ่งลงไปตามขา $type ซ้ำๆ จนสุดสาย แล้ววางเป็นลูกขา $type ของคนสุดท้าย
    | (A = ซ้ายสุด, B = ขวาสุด) ตรรกะเดียวกับเมนูต้นไม้ under_a / under_b
    |
    */
    public static function findLegPlacement($sponsor, $type)
    {
        $node = $sponsor;

        for ($i = 0; $i < 5000; $i++) {
            $child = DB::table('customers')
                ->where('upline_id', $node)
                ->where('type_upline', $type)
                ->value('user_name');

            if (empty($child)) {
                return ['status' => 'success', 'upline_id' => $node, 'type' => $type];
            }
            $node = $child;
        }

        return ['status' => 'fail', 'ms' => 'หาตำแหน่งสุดสายไม่พบ'];
    }

    public static function legIsFree($upline, $type, $ignoreApplicationId = null)
    {
        $taken = DB::table('customers')
            ->where('upline_id', $upline)
            ->where('type_upline', $type)
            ->exists();
        if ($taken) {
            return false;
        }

        // ใบสมัครอื่นที่รอชำระเงินและจองขานี้ไว้ (ภายใน 2 ชั่วโมง)
        $reserved = RegisterApplication::where('upline_user_name', $upline)
            ->where('type_upline', $type)
            ->where(function ($q) {
                $q->where('status', 'slip_pending')
                    ->orWhere(function ($q2) {
                        $q2->where('status', 'pending')->where('created_at', '>=', now()->subHours(2));
                    });
            })
            ->when($ignoreApplicationId, function ($q) use ($ignoreApplicationId) {
                $q->where('id', '!=', $ignoreApplicationId);
            })
            ->exists();

        return !$reserved;
    }

    /*
    |--------------------------------------------------------------------------
    | ชำระเงินสำเร็จ -> สร้างสมาชิก + ออเดอร์ + โบนัส
    |--------------------------------------------------------------------------
    |
    | คืน ['status' => 'success'|'fail', 'message' => ..., 'user_name' => ...]
    | ปลอดภัยต่อการเรียกซ้ำ (idempotent): ล็อกแถวใบสมัคร และข้ามถ้าเป็น paid แล้ว
    |
    */
    public static function activate($applicationId, array $gateway = [])
    {
        try {
            DB::beginTransaction();

            $app = RegisterApplication::lockForUpdate()->find($applicationId);

            if (!$app) {
                DB::rollBack();
                return ['status' => 'fail', 'message' => 'application not found'];
            }

            if ($app->status === 'paid' || $app->status === 'failed') {
                DB::commit();
                return $app->status === 'paid'
                    ? ['status' => 'success', 'message' => 'already paid', 'user_name' => $app->customer_user_name]
                    : ['status' => 'fail', 'message' => 'application was rejected'];
            }

            $form = $app->formData();
            $files = $app->fileList();
            $items = $app->itemList();
            $pv_register = (float) $app->pv_total;
            $position = PositionService::fromRegisterPv($pv_register);

            if (Customers::where('id_card', $app->id_card)->exists()) {
                DB::rollBack();
                self::markError($app->id, 'เลขบัตรประชาชนนี้ถูกใช้สมัครแล้ว (ชำระเงินแล้ว ต้องตรวจสอบ/คืนเงิน)', $gateway);
                return ['status' => 'fail', 'message' => 'duplicate id card'];
            }

            $sponsor = Customers::where('user_name', $app->sponsor_user_name)->first();
            if (!$sponsor) {
                DB::rollBack();
                self::markError($app->id, 'ไม่พบผู้แนะนำ ' . $app->sponsor_user_name, $gateway);
                return ['status' => 'fail', 'message' => 'sponsor not found'];
            }

            // ---- สายงาน ----
            $upline_id = $app->upline_user_name;
            $type = $app->type_upline;
            $note = null;

            if (empty($upline_id)) {
                // ผู้สมัครเลือกแค่ขา A/B: วิ่งไปสุดสายของขานั้นใต้ผู้แนะนำ แล้ววางต่อท้าย
                // (ถ้าไม่มีขา ใช้ระบบหาที่ว่างอัตโนมัติแบบหน้าสมัครเดิม)
                $auto = in_array($type, ['A', 'B'])
                    ? self::findLegPlacement($sponsor->user_name, $type)
                    : \App\Http\Controllers\Frontend\FC\UplineController::uplineAB($sponsor->user_name);
                if (($auto['status'] ?? 'fail') == 'fail') {
                    DB::rollBack();
                    self::markError($app->id, 'หาสายงาน Upline อัตโนมัติไม่ได้', $gateway);
                    return ['status' => 'fail', 'message' => 'no free leg'];
                }
                $upline_id = $auto['upline_id'];
                $type = $auto['type'];
                $note = "วางสายอัตโนมัติ: ขาที่เลือก {$app->type_upline} -> ใต้ {$upline_id} ขา {$type}";
            }

            $data_uni = \App\Http\Controllers\Frontend\FC\UnilevelController::uplineAB($sponsor->user_name);
            if (($data_uni['status'] ?? 'fail') == 'fail') {
                DB::rollBack();
                self::markError($app->id, 'หาสายงาน Unilevel ไม่ได้', $gateway);
                return ['status' => 'fail', 'message' => 'no unilevel'];
            }

            // ---- รหัสสมาชิก / รหัสผ่าน ----
            do {
                $user_name = \App\Http\Controllers\Frontend\RegisterController::gencode_customer();
            } while (DB::table('customers')->where('user_name', $user_name)->exists());

            $password = substr($form['id_card'], -4);
            $birth_day = date('Y-m-d', strtotime($form['year'] . '-' . $form['month'] . '-' . $form['day']));

            // ---- วันหมดอายุ ----
            $expire = PositionService::expireDates([], $pv_register);
            $has_insurance = in_array($position, ['MO', 'VIP', 'VVIP', 'STAR']);
            $insurance_date = $has_insurance ? date('Y-m-d', strtotime('+1 years')) : null;

            $customer = [
                'user_name' => $user_name,
                'password' => md5($password),
                'expire_date' => $expire['expire_date'],
                'expire_date_bonus' => $expire['expire_date_bonus'],
                'expire_date_bonus_balance' => $expire['expire_date_bonus_balance'],
                'expire_insurance_date' => $insurance_date,
                'terms_accepted' => 'yes',
                'terms_accepted_date' => now(),
                'upline_id' => $upline_id,
                'type_upline' => $type,
                'introduce_id' => $sponsor->user_name,
                'uni_id' => $data_uni['uni_id'],
                'type_upline_uni' => $data_uni['type_upline_uni'],
                'prefix_name' => $form['prefix_name'],
                'name' => $form['name'],
                'last_name' => $form['last_name'],
                'gender' => $form['gender'],
                'business_name' => $form['business_name'],
                'birth_day' => $birth_day,
                'nation_id' => 'ไทย',
                'business_location_id' => $form['nation_id'],
                'qualification_id' => $position,
                'id_card' => $form['id_card'],
                'phone' => $form['phone'],
                'email' => $form['email'] ?? null,
                'line_id' => $form['line_id'] ?? null,
                'facebook' => $form['facebook'] ?? null,
                'pv_upgrad' => $pv_register,
                'pv_all' => $pv_register,
                'vvip_register_type' => 'register',
                'regis_doc4_status' => 0,
                'regis_doc1_status' => 3,
            ];
            $new = Customers::create($customer);

            if ($has_insurance) {
                Log_insurance::create([
                    'user_name' => $user_name,
                    'old_exprie_date' => null,
                    'new_exprie_date' => $insurance_date,
                    'position' => $position,
                    'pv' => $pv_register,
                    'status' => 'success',
                    'type' => 'register',
                ]);
            }

            // ---- เอกสาร / ที่อยู่ / ธนาคาร / ผู้รับผลประโยชน์ ----
            self::saveDocuments($new, $user_name, $form, $files);

            // ---- ออเดอร์ ----
            $code_order = self::createOrder($app, $new, $position, $items, $form, $gateway);

            // ---- โบนัสแนะนำ 5 ชั้น ----
            $code_bonus = RunCodeController::db_code_bonus(2);
            self::payReferralBonus($sponsor, $new, $pv_register, $code_bonus);
            \App\Http\Controllers\Frontend\StarReCashController::pay($code_bonus, $user_name, 'register');

            $app->status = 'paid';
            $app->customer_user_name = $user_name;
            $app->code_order = $code_order;
            $app->position = $position;
            $app->paid_at = $gateway['paid_at'] ?? now();
            $app->gateway_transaction_id = $gateway['gateway_transaction_id'] ?? $app->gateway_transaction_id;
            $app->gateway_status = $gateway['gateway_status'] ?? $app->gateway_status;
            $app->gateway_payload = $gateway['gateway_payload'] ?? $app->gateway_payload;
            $app->note = $note;
            $app->save();

            DB::commit();

            return ['status' => 'success', 'message' => 'ok', 'user_name' => $user_name];
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::channel('payment')->error('Public register activate failed', [
                'application_id' => $applicationId,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            self::markError($applicationId, 'สร้างสมาชิกไม่สำเร็จ: ' . $e->getMessage(), $gateway);

            return ['status' => 'fail', 'message' => $e->getMessage()];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | จ่ายแล้วแต่สร้างไม่สำเร็จ -> ติดสถานะ error ให้เจ้าหน้าที่ตรวจสอบ
    |--------------------------------------------------------------------------
    */
    private static function markError($applicationId, $note, array $gateway)
    {
        RegisterApplication::where('id', $applicationId)->update([
            'status' => 'error',
            'note' => $note,
            'gateway_transaction_id' => $gateway['gateway_transaction_id'] ?? null,
            'gateway_status' => $gateway['gateway_status'] ?? null,
            'gateway_payload' => $gateway['gateway_payload'] ?? null,
        ]);
    }

    private static function moveFile($path, $dir, $filename)
    {
        $from = public_path($path);
        if (!$path || !File::exists($from)) {
            return null;
        }
        if (!File::isDirectory(public_path($dir))) {
            File::makeDirectory(public_path($dir), 0755, true);
        }
        File::move($from, public_path($dir . '/' . $filename));

        return $filename;
    }

    private static function saveDocuments($new, $user_name, array $form, array $files)
    {
        // บัตรประชาชน
        $card_url = 'images/customers_card/' . date('Ym');
        $card_name = date('YmdHis') . '.' . $new->id . '.' . pathinfo($files['file_card'] ?? '', PATHINFO_EXTENSION);
        self::moveFile($files['file_card'] ?? null, $card_url, $card_name);

        CustomersAddressCard::updateOrInsert(['customers_id' => $new->id], [
            'customers_id' => $new->id,
            'user_name' => $user_name,
            'url' => $card_url,
            'img_card' => $card_name,
            'address' => $form['card_address'],
            'moo' => $form['card_moo'],
            'soi' => $form['card_soi'],
            'road' => $form['card_road'],
            'tambon' => $form['card_tambon'],
            'district' => $form['card_district'],
            'province' => $form['card_province'],
            'zipcode' => $form['card_zipcode'],
            'phone' => $form['card_phone'] ?? null,
        ]);

        // ที่อยู่จัดส่ง
        CustomersAddressDelivery::updateOrInsert(['customers_id' => $new->id], [
            'customers_id' => $new->id,
            'user_name' => $user_name,
            'address' => $form['same_address'],
            'moo' => $form['same_moo'],
            'soi' => $form['same_soi'],
            'road' => $form['same_road'],
            'tambon' => $form['same_tambon'],
            'district' => $form['same_district'],
            'province' => $form['same_province'],
            'zipcode' => $form['same_zipcode'],
            'phone' => $form['same_phone'] ?? null,
            'status' => !empty($form['status_address']) ? 1 : 2,
        ]);

        // บัญชีธนาคาร (ไม่บังคับ)
        if (!empty($files['file_bank']) && !empty($form['bank_name'])) {
            $bank_url = 'images/customers_bank/' . date('Ym');
            $bank_file = date('YmdHis') . '.' . $new->id . '.' . pathinfo($files['file_bank'], PATHINFO_EXTENSION);
            self::moveFile($files['file_bank'], $bank_url, $bank_file);

            $bank = DB::table('dataset_bank')->where('id', $form['bank_name'])->first();
            if ($bank) {
                CustomersBank::updateOrInsert(['customers_id' => $new->id], [
                    'customers_id' => $new->id,
                    'user_name' => $user_name,
                    'url' => $bank_url,
                    'img_bank' => $bank_file,
                    'bank_name' => $bank->name,
                    'bank_id_fk' => $bank->id,
                    'code_bank' => $bank->code,
                    'bank_branch' => $form['bank_branch'],
                    'bank_no' => $form['bank_no'],
                    'account_name' => $form['account_name'],
                ]);
                Customers::where('id', $new->id)->update(['regis_doc4_status' => 3]);
            }
        }

        // ผู้รับผลประโยชน์ (ไม่บังคับ)
        if (!empty($form['name_benefit'])) {
            CustomersBenefit::create([
                'customers_id' => $new->id,
                'user_name' => $user_name,
                'name' => $form['name_benefit'],
                'last_name' => $form['last_name_benefit'],
                'involved' => $form['involved'],
            ]);
        }
    }

    private static function createOrder(RegisterApplication $app, $new, $position, array $items, array $form, array $gateway)
    {
        $code_order = RunCodeController::db_code_order();
        // $items คือ snapshot รายการสินค้า ณ ตอนกดชำระเงิน (ราคา/PV ไม่เปลี่ยนตามการแก้ไขสินค้าทีหลัง)
        $quantity = array_sum(array_column($items, 'qty'));

        $business_location_id = 1;
        $vat_row = DB::table('dataset_vat')->where('business_location_id_fk', $business_location_id)->first();
        $vat = $vat_row ? $vat_row->vat : 0;
        $p_vat = $app->sum_price * ($vat / (100 + $vat));

        $shipping_zipcode = ShippingController::fc_shipping_zip_code($form['same_zipcode']);
        $shipping_id = $app->shipping_price == 0 ? 1 : ($shipping_zipcode['status'] == 'success' ? 3 : 2);
        $shipping_name = DB::table('dataset_shipping_cost')->where('id', $shipping_id)->value('shipping_name');

        $order = new Orders();
        $order->customers_id_fk = $new->id;
        $order->customers_user_name = $new->user_name;
        $order->business_location_id_fk = $business_location_id;
        $order->status_payment_sent_other = 0;
        $order->address_sent = 'other';
        $order->delivery_province_id = $form['same_province'];
        $order->house_no = $form['same_address'];
        $order->moo = $form['same_moo'];
        $order->soi = $form['same_soi'];
        $order->road = $form['same_road'];
        $order->tambon_id = $form['same_tambon'];
        $order->district_id = $form['same_district'];
        $order->province_id = $form['same_province'];
        $order->zipcode = $form['same_zipcode'];
        $order->tel = $form['same_phone'] ?? $form['phone'];
        $order->name = $form['name'] . ' ' . $form['last_name'];
        if ($app->pay_method === 'slip') {
            $order->pay_type = 'transfer';
            $order->payment_gateway = null;
            $order->gateway_status = 'slip_approved';
        } else {
            $order->pay_type = 'payso';
            $order->payment_gateway = 'payso';
            $order->payso_refno = $app->payso_refno;
        }
        $order->gateway_transaction_id = $gateway['gateway_transaction_id'] ?? null;
        $order->gateway_status = $order->gateway_status ?: ($gateway['gateway_status'] ?? null);
        $order->gateway_payload = $gateway['gateway_payload'] ?? null;
        $order->paid_at = $gateway['paid_at'] ?? now()->format('Y-m-d H:i:s');
        $order->transfer_price = $app->total_price;
        $order->ewallet_price = 0;
        $order->product_value = $app->sum_price - $p_vat;
        $order->sum_price = $app->sum_price;
        $order->shipping_price = $app->shipping_price;
        $order->shipping_cost_id_fk = $shipping_id;
        $order->shipping_cost_name = $shipping_name;
        $order->shipping_free = $app->shipping_price == 0 ? 1 : 0;
        $order->discount = 0;
        $order->bonus_percent = 0;
        $order->total_price = $app->total_price;
        $order->pv_total = $app->pv_total;
        $order->tax = $vat;
        $order->tax_total = $p_vat;
        $order->quantity = $quantity;
        $order->code_order = $code_order;
        $order->type_order = 'pv';
        $order->order_status_id_fk = 5;
        $order->position = DB::table('dataset_qualification')->where('code', $position)->value('business_qualifications');
        $order->save();

        $rows = [];
        foreach ($items as $line) {
            $rows[] = [
                'code_order' => $code_order,
                'product_id_fk' => $line['id'],
                'customers_username' => $new->user_name,
                'selling_price' => $line['price'],
                'product_name' => $line['name'],
                'amt' => $line['qty'],
                'pv' => $line['pv'],
                'total_pv' => $line['total_pv'],
                'total_price' => $line['total_price'],
            ];
        }
        Order_products_list::insert($rows);

        // บันทึก PV ที่ได้จากการสมัคร (type 4 = สมัครสมาชิก)
        Jang_pv::create([
            'code' => RunCodeController::db_code_pv(),
            'code_order' => $code_order,
            'customer_username' => $new->user_name,
            'to_customer_username' => $new->user_name,
            'position' => $position,
            'pv_old' => 0,
            'pv' => $app->pv_total,
            'pv_balance' => 0,
            'wallet' => $app->total_price,
            'old_wallet' => 0,
            'wallet_balance' => 0,
            'type' => '4',
            'status' => 'Success',
        ]);

        return $code_order;
    }

    /*
    |--------------------------------------------------------------------------
    | โบนัสแนะนำ 5 ชั้น (ตรรกะเดียวกับ RegisterController::store_register)
    |--------------------------------------------------------------------------
    |
    | ชั้น 1: MB 50% / MO 70% / VIP 90% / ตำแหน่งสูงกว่า 120%
    | ชั้น 2: 5% (MO ขึ้นไป) / ชั้น 3: 5% (VIP ขึ้นไป) / ชั้น 4: 3% / ชั้น 5: 2% (VVIP ขึ้นไป)
    | ข้ามคนที่เป็น MC หรือยกเลิกแล้ว (ไม่นับเป็นชั้น) / หักภาษี 3%
    |
    */
    private static function payReferralBonus($sponsor, $new, $pv, $code_bonus)
    {
        $sponsor_name = $sponsor->name . ' ' . $sponsor->last_name;
        $cursor = $sponsor->user_name;

        for ($level = 1; $level <= 5; $level++) {
            $member = null;
            while (!empty($cursor)) {
                $row = DB::table('customers')
                    ->select('name', 'last_name', 'user_name', 'introduce_id', 'qualification_id', 'status_customer')
                    ->where('user_name', $cursor)
                    ->first();
                if (!$row) {
                    $cursor = null;
                    break;
                }
                if (empty($row->name) || $row->qualification_id == 'MC' || $row->status_customer == 'cancel') {
                    $cursor = $row->introduce_id;
                    continue;
                }
                $member = $row;
                $cursor = $row->introduce_id;
                break;
            }

            if (!$member) {
                break;
            }

            $q = PositionService::normalize($member->qualification_id);
            $rate = 0;
            if ($level == 1) {
                $rate = ['MB' => 50, 'MO' => 70, 'VIP' => 90][$q] ?? 120;
            } elseif ($level == 2) {
                $rate = in_array($q, ['MB']) ? 0 : 5;
            } elseif ($level == 3) {
                $rate = in_array($q, ['MB', 'MO']) ? 0 : 5;
            } elseif ($level == 4) {
                $rate = in_array($q, ['MB', 'MO', 'VIP']) ? 0 : 3;
            } else {
                $rate = in_array($q, ['MB', 'MO', 'VIP']) ? 0 : 2;
            }

            $bonus_full = round($pv * $rate / 100, 3);
            $tax = round($bonus_full * 3 / 100, 3);
            $bonus = round($bonus_full - $tax, 3);

            $report = [
                'user_name' => $sponsor->user_name,
                'name' => $sponsor_name,
                'regis_user_name' => $new->user_name,
                'regis_user_introduce_id' => $member->user_name,
                'regis_name' => $new->name . ' ' . $new->last_name,
                'user_name_g' => $member->user_name,
                'name_g' => $member->name . ' ' . $member->last_name,
                'qualification' => $q,
                'g' => $level,
                'pv' => $pv,
                'percen' => $rate,
                'code_bonus' => $code_bonus,
                'type' => 'register',
                'tax_total' => $tax,
                'bonus_full' => $bonus_full,
                'bonus' => $bonus,
                'status' => 'success',
            ];

            if ($bonus > 0) {
                $wallet = DB::table('customers')
                    ->select('ewallet', 'id', 'ewallet_use', 'bonus_total')
                    ->where('user_name', $member->user_name)
                    ->lockForUpdate()
                    ->first();

                $old = (float) $wallet->ewallet;
                $balance = $old + $bonus;

                DB::table('customers')->where('user_name', $member->user_name)->update([
                    'ewallet' => $balance,
                    'ewallet_use' => (float) $wallet->ewallet_use + $bonus,
                    'bonus_total' => (float) $wallet->bonus_total + $bonus,
                ]);

                $ew = new eWallet();
                $ew->transaction_code = $code_bonus;
                $ew->customers_id_fk = $wallet->id;
                $ew->customer_username = $member->user_name;
                $ew->tax_total = $tax;
                $ew->bonus_full = $bonus_full;
                $ew->amt = $bonus;
                $ew->old_balance = $old;
                $ew->balance = $balance;
                $ew->type = 10;
                $ew->note_orther = 'โบนัสขยายธุรกิจ รหัส ' . $sponsor->user_name . 'แนะนำรหัส ' . $new->user_name;
                $ew->receive_date = now();
                $ew->receive_time = now();
                $ew->status = 2;
                $ew->save();
            }

            DB::table('report_bonus_register')->updateOrInsert(
                ['user_name' => $report['user_name'], 'regis_user_name' => $report['regis_user_name'], 'g' => $level, 'type' => 'register'],
                $report
            );
        }
    }
}
