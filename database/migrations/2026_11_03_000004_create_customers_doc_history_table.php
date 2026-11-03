<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ประวัติเอกสารบัตร/บัญชีที่แอดมินแก้ไข ไม่ผ่าน หรือลบ (เก็บค่าเก่าและ path รูป ไม่ลบไฟล์)
 * type: card / bank, action: reject / delete / edit
 */
class CreateCustomersDocHistoryTable extends Migration
{
    public function up()
    {
        Schema::create('customers_doc_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('customers_id')->index();
            $table->string('user_name', 50)->nullable()->index();
            $table->string('type', 10);
            $table->string('action', 10);
            $table->longText('old_data')->nullable();
            $table->longText('new_data')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers_doc_history');
    }
}
