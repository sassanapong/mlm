<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIntroduceIdToBonusAllSalePreviewDetails extends Migration
{
    public function up()
    {
        if (Schema::hasTable('bonus_all_sale_preview_details') && !Schema::hasColumn('bonus_all_sale_preview_details', 'introduce_id')) {
            Schema::table('bonus_all_sale_preview_details', function (Blueprint $table) {
                $table->string('introduce_id', 50)->nullable()->after('user_name')->index();
            });
        }
    }

    public function down()
    {
        if (Schema::hasTable('bonus_all_sale_preview_details') && Schema::hasColumn('bonus_all_sale_preview_details', 'introduce_id')) {
            Schema::table('bonus_all_sale_preview_details', function (Blueprint $table) {
                $table->dropIndex(['introduce_id']);
                $table->dropColumn('introduce_id');
            });
        }
    }
}
