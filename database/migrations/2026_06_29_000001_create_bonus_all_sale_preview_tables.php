<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBonusAllSalePreviewTables extends Migration
{
    public function up()
    {
        Schema::create('bonus_all_sale_preview_runs', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->unsignedTinyInteger('route');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('status', 20)->default('pending');
            $table->unsignedBigInteger('last_processed_id')->default(0);
            $table->unsignedInteger('processed_count')->default(0);
            $table->unsignedInteger('processed_pv_count')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('generated_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();

            $table->unique(['year', 'month', 'route'], 'bonus_preview_runs_round_unique');
            $table->index(['status', 'updated_at'], 'bonus_preview_runs_status_index');
        });

        Schema::create('bonus_all_sale_preview_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('year');
            $table->unsignedTinyInteger('month');
            $table->unsignedTinyInteger('route');
            $table->unsignedBigInteger('run_id');
            $table->string('user_name', 50);
            $table->string('g1_user_name', 50)->default('SELF');
            $table->string('g1_name')->nullable();
            $table->string('g1_qualification', 50)->nullable();
            $table->date('g1_expire_date_bonus')->nullable();
            $table->decimal('personal_pv', 15, 2)->default(0);
            $table->decimal('organization_pv', 15, 2)->default(0);
            $table->unsignedTinyInteger('all_sale_rate')->default(0);
            $table->unsignedTinyInteger('next_rate')->default(0);
            $table->decimal('pv_to_next_rate', 15, 2)->default(0);
            $table->timestamp('generated_at')->nullable();
            $table->timestamps();

            $table->unique(['year', 'month', 'route', 'user_name', 'g1_user_name'], 'bonus_preview_details_owner_g1_unique');
            $table->index(['user_name', 'year', 'month', 'route'], 'bonus_preview_details_owner_round_index');
            $table->index(['run_id'], 'bonus_preview_details_run_index');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bonus_all_sale_preview_details');
        Schema::dropIfExists('bonus_all_sale_preview_runs');
    }
}
