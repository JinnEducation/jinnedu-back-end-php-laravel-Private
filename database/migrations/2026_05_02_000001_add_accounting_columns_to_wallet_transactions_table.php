<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            if (! Schema::hasColumn('wallet_transactions', 'wallet_payment_transaction_id')) {
                $table->foreignId('wallet_payment_transaction_id')
                    ->nullable()
                    ->after('order_id')
                    ->constrained('wallet_payment_transactions')
                    ->nullOnDelete();
            }

            if (! Schema::hasColumn('wallet_transactions', 'transaction_type')) {
                $table->string('transaction_type')->nullable()->after('type')->index();
            }

            if (! Schema::hasColumn('wallet_transactions', 'payment_gateway')) {
                $table->string('payment_gateway')->nullable()->after('transaction_type')->index();
            }

            if (! Schema::hasColumn('wallet_transactions', 'status')) {
                $table->string('status')->default('completed')->after('payment_gateway')->index();
            }

            if (! Schema::hasColumn('wallet_transactions', 'balance_before')) {
                $table->decimal('balance_before', 10, 2)->nullable()->after('amount');
            }

            if (! Schema::hasColumn('wallet_transactions', 'balance_after')) {
                $table->decimal('balance_after', 10, 2)->nullable()->after('balance_before');
            }

            if (! Schema::hasColumn('wallet_transactions', 'currency_code')) {
                $table->string('currency_code', 10)->default('USD')->after('balance_after');
            }

            if (! Schema::hasColumn('wallet_transactions', 'metadata')) {
                $table->json('metadata')->nullable()->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            if (Schema::hasColumn('wallet_transactions', 'wallet_payment_transaction_id')) {
                $table->dropConstrainedForeignId('wallet_payment_transaction_id');
            }

            foreach ([
                'transaction_type',
                'payment_gateway',
                'status',
                'balance_before',
                'balance_after',
                'currency_code',
                'metadata',
            ] as $column) {
                if (Schema::hasColumn('wallet_transactions', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
