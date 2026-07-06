<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaysoFieldsToEwalletTables extends Migration
{
    public function up()
    {
        foreach (['ewallet_tranfer', 'ewallet'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (!Schema::hasColumn($tableName, 'payment_method')) {
                    $table->string('payment_method')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'payment_gateway')) {
                    $table->string('payment_gateway')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'gateway_transaction_id')) {
                    $table->string('gateway_transaction_id')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'gateway_status')) {
                    $table->string('gateway_status')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'gateway_payload')) {
                    $table->longText('gateway_payload')->nullable();
                }
                if (!Schema::hasColumn($tableName, 'paid_at')) {
                    $table->dateTime('paid_at')->nullable();
                }
            });
        }
    }

    public function down()
    {
        foreach (['ewallet_tranfer', 'ewallet'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                foreach (['payment_method', 'payment_gateway', 'gateway_transaction_id', 'gateway_status', 'gateway_payload', 'paid_at'] as $column) {
                    if (Schema::hasColumn($tableName, $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
}
