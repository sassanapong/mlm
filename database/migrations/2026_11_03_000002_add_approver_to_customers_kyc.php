<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddApproverToCustomersKyc extends Migration
{
    public function up()
    {
        Schema::table('customers_kyc', function (Blueprint $table) {
            $table->unsignedBigInteger('approved_by')->nullable()->after('note');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });
    }

    public function down()
    {
        Schema::table('customers_kyc', function (Blueprint $table) {
            $table->dropColumn(['approved_by', 'approved_at']);
        });
    }
}
