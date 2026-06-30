<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProgressToBonusAllSalePreviewRuns extends Migration
{
    public function up()
    {
        Schema::table('bonus_all_sale_preview_runs', function (Blueprint $table) {
            $table->unsignedInteger('total_count')->default(0)->after('last_processed_id');
            $table->unsignedInteger('remaining_count')->default(0)->after('processed_pv_count');
            $table->unsignedInteger('estimated_batches')->default(0)->after('remaining_count');
            $table->unsignedInteger('batch_limit')->default(500)->after('estimated_batches');
        });
    }

    public function down()
    {
        Schema::table('bonus_all_sale_preview_runs', function (Blueprint $table) {
            $table->dropColumn([
                'total_count',
                'remaining_count',
                'estimated_batches',
                'batch_limit',
            ]);
        });
    }
}
