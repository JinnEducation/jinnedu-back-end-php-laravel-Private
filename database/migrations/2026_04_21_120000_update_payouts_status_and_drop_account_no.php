<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            if (! Schema::hasColumn('payouts', 'payout_status')) {
                $table->enum('payout_status', ['pending', 'transferred'])
                    ->default('pending')
                    ->after('status');
            }

            if (Schema::hasColumn('payouts', 'account_no')) {
                $table->dropColumn('account_no');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            if (! Schema::hasColumn('payouts', 'account_no')) {
                $table->string('account_no')->nullable()->after('bank_account_name');
            }

            if (Schema::hasColumn('payouts', 'payout_status')) {
                $table->dropColumn('payout_status');
            }
        });
    }
};
