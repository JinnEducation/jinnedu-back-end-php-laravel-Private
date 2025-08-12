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
        Schema::create('payouts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', $precision = 8, $scale = 2)->default(0);
            $table->string('method')->nullable();
            $table->string('status')->nullable()->comment('R for review, P for paid');
            $table->string('bank_name')->nullable();
            $table->string('account_no')->nullable();
            $table->string('paypal_account')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payouts');
    }
};
