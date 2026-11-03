<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * 1 Code 1 World: ประวัติการส่งยืนยันตัวตนรอบก่อนๆ (เก็บรูปเก่าไว้ ไม่ลบ)
 */
class CreateCustomersKycHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('customers_kyc_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customers_id')->index();
            $table->string('user_name', 50)->index();
            $table->string('id_card', 20);
            $table->string('img_card', 255);
            $table->string('bank_no', 30);
            $table->string('bank_name', 100)->nullable();
            $table->string('img_bank', 255);
            $table->string('phone', 20);
            $table->unsignedTinyInteger('status');
            $table->string('note', 255)->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('decided_at')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers_kyc_history');
    }
}
