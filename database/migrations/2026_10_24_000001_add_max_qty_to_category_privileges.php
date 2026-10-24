<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
| จำนวนที่ซื้อได้รายคน (แทนค่ากลางของหมวด dataset_categories.max_qty_per_user)
| ว่าง = ใช้ค่ากลางของหมวด
*/
class AddMaxQtyToCategoryPrivileges extends Migration
{
    public function up()
    {
        Schema::table('category_privileges', function (Blueprint $table) {
            $table->unsignedInteger('max_qty')->nullable()->after('user_name');
        });
    }

    public function down()
    {
        Schema::table('category_privileges', function (Blueprint $table) {
            $table->dropColumn('max_qty');
        });
    }
}
