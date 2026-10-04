<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| ใบสมัครสมาชิกผ่านลิงก์สาธารณะ (/join)
|--------------------------------------------------------------------------
|
| เก็บข้อมูลผู้สมัครไว้ก่อนชำระเงิน แล้วค่อยสร้างสมาชิกจริงตอน PaySo postback สำเร็จ
| status: pending (รอชำระ PaySo) / slip_pending (แนบสลิปแล้ว รอตรวจ) / paid (สร้างสมาชิกแล้ว) / failed (ชำระไม่สำเร็จ) / error (จ่ายแล้วแต่สร้างไม่สำเร็จ)
|
*/
class CreateRegisterApplicationsTable extends Migration
{
    public function up()
    {
        Schema::create('register_applications', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('token', 64)->unique();
            $table->string('payso_refno', 50)->nullable()->unique();

            $table->string('sponsor_user_name', 50)->index();
            $table->string('upline_user_name', 50);
            $table->string('type_upline', 1);

            $table->string('id_card', 20)->index();
            $table->longText('form_data');      // ข้อมูลฟอร์มทั้งหมด (JSON)
            $table->longText('items');          // รายการสินค้า (JSON) คำนวณราคา/PV ฝั่ง server
            $table->longText('files')->nullable(); // path ไฟล์เอกสาร (JSON)

            $table->decimal('pv_total', 14, 2)->default(0);
            $table->decimal('sum_price', 14, 2)->default(0);
            $table->decimal('shipping_price', 14, 2)->default(0);
            $table->decimal('total_price', 14, 2)->default(0);
            $table->string('position', 20)->nullable();

            $table->string('pay_method', 10)->default('payso'); // payso / slip
            $table->string('status', 20)->default('pending')->index();
            $table->string('gateway_transaction_id', 100)->nullable();
            $table->string('gateway_status', 50)->nullable();
            $table->longText('gateway_payload')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->string('customer_user_name', 50)->nullable();
            $table->string('code_order', 50)->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('register_applications');
    }
}
