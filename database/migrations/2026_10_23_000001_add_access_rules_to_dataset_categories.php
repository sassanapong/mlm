<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
|--------------------------------------------------------------------------
| ตั้งค่าสิทธิ์การมองเห็น/สั่งซื้อของหมวดสินค้า
|--------------------------------------------------------------------------
|
| access_mode        public = ทุกคน / whitelist = เฉพาะรหัสที่ได้รับสิทธิ์ (category_privileges)
| allowed_positions  JSON รหัสตำแหน่ง (dataset_qualification.code) ที่มองเห็น ว่าง = ทุกตำแหน่ง
| max_qty_per_user   ซื้อได้ไม่เกินกี่ชิ้น/คน ในหมวดนี้ (รวมทุกออเดอร์) ว่าง = ไม่จำกัด
| show_start_at / show_end_at  ช่วงเวลาแสดงหมวด ว่าง = แสดงตลอด
|
*/
class AddAccessRulesToDatasetCategories extends Migration
{
    public function up()
    {
        Schema::table('dataset_categories', function (Blueprint $table) {
            $table->string('access_mode', 20)->default('public')->after('status');
            $table->text('allowed_positions')->nullable()->after('access_mode');
            $table->unsignedInteger('max_qty_per_user')->nullable()->after('allowed_positions');
            $table->dateTime('show_start_at')->nullable()->after('max_qty_per_user');
            $table->dateTime('show_end_at')->nullable()->after('show_start_at');
        });

        Schema::create('category_privileges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('category_id')->index();
            $table->string('user_name', 50);
            $table->timestamps();

            $table->unique(['category_id', 'user_name']);
            $table->index('user_name');
        });
    }

    public function down()
    {
        Schema::dropIfExists('category_privileges');

        Schema::table('dataset_categories', function (Blueprint $table) {
            $table->dropColumn(['access_mode', 'allowed_positions', 'max_qty_per_user', 'show_start_at', 'show_end_at']);
        });
    }
}
