<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddSourcesToReportBonusStarRecash extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('report_bonus_star_recash')) {
            return;
        }

        // รองรับต้นทางเพิ่ม: อัพตำแหน่ง (jangpv) และ ยืนยันสิทธิ์ (active)
        DB::statement("ALTER TABLE `report_bonus_star_recash`
            MODIFY `type` ENUM('register','register_url','jangpv','active') NULL DEFAULT 'register'");

        if (!Schema::hasColumn('report_bonus_star_recash', 'ref_code')) {
            Schema::table('report_bonus_star_recash', function (Blueprint $table) {
                // code ของ jang_pv สำหรับต้นทาง jangpv / active ใช้กันจ่ายซ้ำ
                $table->string('ref_code', 255)->nullable()->after('transaction_code')->index();
            });
        }
    }

    public function down()
    {
        if (!Schema::hasTable('report_bonus_star_recash')) {
            return;
        }

        if (Schema::hasColumn('report_bonus_star_recash', 'ref_code')) {
            Schema::table('report_bonus_star_recash', function (Blueprint $table) {
                $table->dropIndex(['ref_code']);
                $table->dropColumn('ref_code');
            });
        }

        DB::statement("ALTER TABLE `report_bonus_star_recash`
            MODIFY `type` ENUM('register','register_url') NULL DEFAULT 'register'");
    }
}
