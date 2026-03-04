<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if (!Schema::hasColumn('tutor_finances', 'conference_id')) {
            Schema::table('tutor_finances', function (Blueprint $table) {
                $table->foreignId('conference_id')->nullable()->after('order_id')->constrained('conferences')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('tutor_finances', function (Blueprint $table) {
            $table->dropForeign(['conference_id']);
        });
    }
};
