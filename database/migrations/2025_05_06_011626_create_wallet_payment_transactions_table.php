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
        Schema::create('wallet_payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_status', ['initiated', 'created', 'completed', 'canceled'])->default('initiated');
            $table->enum('status', ['active', 'not_active'])->default('not_active');
            $table->string('payment_channel')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('reference_id')->nullable();
            $table->text('response')->nullable();
            $table->decimal('current_wallet', 10, 2);
            $table->integer('currency_id')->default(1);
            $table->String('currency_code')->default('USD');
            $table->decimal('currency_exchange', 10, 2)->default(1);
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
        Schema::dropIfExists('wallet_payment_transactions');
    }
};
