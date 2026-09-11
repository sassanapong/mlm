<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportBonusStarRecashTable extends Migration
{
    public function up()
    {
        if (Schema::hasTable('report_bonus_star_recash')) {
            return;
        }

        Schema::create('report_bonus_star_recash', function (Blueprint $table) {
            $table->increments('id');

            $table->string('code_bonus', 255)->nullable()->index();
            $table->string('transaction_code', 255)->nullable();

            // สมาชิกที่สมัครไหม่ (ต้นทางของโบนัส)
            $table->string('regis_user_name', 50)->nullable()->index();
            $table->string('regis_name', 255)->nullable();
            $table->string('pv', 20)->nullable();

            // ชั้นที่ 1 ที่ได้โบนัสขยายธุรกิจไปแล้ว (เป็นตัวกำหนดส่วนต่าง)
            $table->string('g1_user_name', 50)->nullable()->index();
            $table->string('g1_name', 255)->nullable();
            $table->string('g1_qualification', 20)->nullable();
            $table->decimal('g1_percen', 10, 2)->nullable();

            // ผู้รับโบนัส STAR ReCash (คนแรกด้านบนที่ถือตำแหน่ง STAR ขึ้นไป)
            $table->string('user_name_g', 50)->nullable()->index();
            $table->string('name_g', 255)->nullable();
            $table->string('qualification', 20)->nullable();
            $table->string('qualification_name', 50)->nullable();
            $table->date('expire_date_bonus')->nullable();
            $table->integer('level_up')->nullable();

            // ยอดจ่าย
            $table->decimal('percen', 10, 2)->nullable();
            $table->integer('tax_percen')->nullable()->default(3);
            $table->decimal('bonus_full', 20, 2)->nullable();
            $table->decimal('tax_total', 20, 2)->nullable();
            $table->decimal('bonus', 20, 2)->nullable();

            $table->enum('type', ['register', 'register_url'])->nullable()->default('register');
            $table->enum('status', ['panding', 'success', 'notfound', 'cancel'])->nullable()->default('panding');
            $table->string('note', 255)->nullable();

            $table->timestamps();
            $table->timestamp('deleted_at')->nullable();

            $table->unique(['code_bonus', 'regis_user_name'], 'report_bonus_star_recash_code_regis_unique');
        });
    }

    public function down()
    {
        Schema::dropIfExists('report_bonus_star_recash');
    }
}
