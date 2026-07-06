<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPaysoFieldsToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('db_orders', function (Blueprint $table) {
            if (!Schema::hasColumn('db_orders', 'payso_refno')) {
                $table->string('payso_refno', 20)->nullable()->after('pay_type')->index();
            }

            if (!Schema::hasColumn('db_orders', 'payment_gateway')) {
                $table->string('payment_gateway', 50)->nullable()->after('payso_refno')->index();
            }

            if (!Schema::hasColumn('db_orders', 'gateway_transaction_id')) {
                $table->string('gateway_transaction_id', 100)->nullable()->after('payment_gateway');
            }

            if (!Schema::hasColumn('db_orders', 'gateway_status')) {
                $table->string('gateway_status', 50)->nullable()->after('gateway_transaction_id');
            }

            if (!Schema::hasColumn('db_orders', 'gateway_payload')) {
                $table->text('gateway_payload')->nullable()->after('gateway_status');
            }

            if (!Schema::hasColumn('db_orders', 'paid_at')) {
                $table->dateTime('paid_at')->nullable()->after('gateway_payload');
            }
        });
    }

    public function down()
    {
        Schema::table('db_orders', function (Blueprint $table) {
            foreach (['paid_at', 'gateway_payload', 'gateway_status', 'gateway_transaction_id', 'payment_gateway', 'payso_refno'] as $column) {
                if (Schema::hasColumn('db_orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
}
