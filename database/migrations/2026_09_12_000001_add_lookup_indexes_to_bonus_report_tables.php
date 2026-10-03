<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * report_bonus_active / report_bonus_register มีแต่ PRIMARY KEY
 * การค้นด้วย code / code_bonus จึงเป็น full table scan (active มีล้านกว่าแถว)
 * กระทบทั้งโค้ดเดิมและการอ่านแถวชั้นที่ 1 ของ STAR ReCash
 */
class AddLookupIndexesToBonusReportTables extends Migration
{
    public function up()
    {
        if (Schema::hasTable('report_bonus_active') && !self::hasIndex('report_bonus_active', 'rba_code_g_idx')) {
            DB::statement('ALTER TABLE `report_bonus_active` ADD INDEX `rba_code_g_idx` (`code`, `g`)');
        }

        if (Schema::hasTable('report_bonus_register') && !self::hasIndex('report_bonus_register', 'rbr_codebonus_regis_g_idx')) {
            DB::statement('ALTER TABLE `report_bonus_register` ADD INDEX `rbr_codebonus_regis_g_idx` (`code_bonus`, `regis_user_name`, `g`)');
        }
    }

    public function down()
    {
        if (Schema::hasTable('report_bonus_active') && self::hasIndex('report_bonus_active', 'rba_code_g_idx')) {
            DB::statement('ALTER TABLE `report_bonus_active` DROP INDEX `rba_code_g_idx`');
        }

        if (Schema::hasTable('report_bonus_register') && self::hasIndex('report_bonus_register', 'rbr_codebonus_regis_g_idx')) {
            DB::statement('ALTER TABLE `report_bonus_register` DROP INDEX `rbr_codebonus_regis_g_idx`');
        }
    }

    private static function hasIndex($table, $index)
    {
        return count(DB::select("SHOW INDEX FROM `{$table}` WHERE Key_name = ?", [$index])) > 0;
    }
}
