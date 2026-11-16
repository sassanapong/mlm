<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
| ตัวนับเลขรหัส (order/bonus/pv/wallet) 1 แถวต่อ prefix ต่อวัน
| แทนการ insert ทุกรายการลง db_code_* (ตารางไม่บวม)
*/
class CreateRunningCountersTable extends Migration
{
    public function up()
    {
        Schema::create('running_counters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('prefix', 32)->unique();
            $table->unsignedBigInteger('current_number')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('running_counters');
    }
}
