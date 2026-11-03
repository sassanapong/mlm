<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 1 Code 1 World: ข้อมูลยืนยันตัวตนของสมาชิกเดิม (รอแอดมินตรวจสอบ)
 * status: 0=รอตรวจสอบ, 1=ผ่าน, 2=ไม่ผ่าน
 */
class CreateCustomersKycTable extends Migration
{
    public function up()
    {
        Schema::create('customers_kyc', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customers_id')->unique();
            $table->string('user_name', 50)->index();
            $table->string('id_card', 20)->index();
            $table->string('img_card', 255);
            $table->string('bank_no', 30)->index();
            $table->unsignedBigInteger('bank_id_fk')->nullable();
            $table->string('bank_name', 100)->nullable();
            $table->string('img_bank', 255);
            $table->string('phone', 20)->index();
            $table->timestamp('terms_accepted_at')->nullable();
            $table->unsignedTinyInteger('status')->default(0)->index();
            $table->string('note', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers_kyc');
    }
}
