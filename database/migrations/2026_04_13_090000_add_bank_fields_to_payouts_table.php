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
            if (! Schema::hasColumn('payouts', 'bank_account_name')) {
                $table->string('bank_account_name')->nullable()->after('bank_name');
            }

            if (! Schema::hasColumn('payouts', 'iban')) {
                $table->string('iban')->nullable()->after('account_no');
            }

            if (! Schema::hasColumn('payouts', 'swift_code')) {
                $table->string('swift_code')->nullable()->after('iban');
            }

            if (! Schema::hasColumn('payouts', 'country')) {
                $table->string('country')->nullable()->after('swift_code');
            }

            if (! Schema::hasColumn('payouts', 'response')) {
                $table->text('response')->nullable()->after('note');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            if (Schema::hasColumn('payouts', 'bank_account_name')) {
                $table->dropColumn('bank_account_name');
            }

            if (Schema::hasColumn('payouts', 'iban')) {
                $table->dropColumn('iban');
            }

            if (Schema::hasColumn('payouts', 'swift_code')) {
                $table->dropColumn('swift_code');
            }

            if (Schema::hasColumn('payouts', 'country')) {
                $table->dropColumn('country');
            }

            if (Schema::hasColumn('payouts', 'response')) {
                $table->dropColumn('response');
            }
        });
    }
};
