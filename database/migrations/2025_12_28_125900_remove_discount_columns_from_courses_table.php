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
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
            'discount_type',
            'discount_value',
            'discount_starts_at',
            'discount_ends_at',
        ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            Schema::table('courses', function (Blueprint $table) {
        $table->enum('discount_type', ['percent', 'fixed'])->nullable();
        $table->decimal('discount_value', 10, 2)->nullable();
        $table->timestamp('discount_starts_at')->nullable();
        $table->timestamp('discount_ends_at')->nullable();
    });
        });
    }
};
