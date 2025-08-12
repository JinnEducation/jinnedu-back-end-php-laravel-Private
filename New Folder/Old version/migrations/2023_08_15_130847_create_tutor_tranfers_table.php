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
        Schema::create('tutor_tranfers', function (Blueprint $table) {
            $table->id();
            $table->integer('tutor_id')->default(0);
            $table->integer('order_id')->default(0);
            $table->integer('type')->default(0);
            $table->decimal('percentage', $precision = 8, $scale = 2)->default(0);
            $table->decimal('amount', $precision = 8, $scale = 2)->default(0);
            $table->integer('status')->default(0);
            $table->String('note')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tutor_tranfers');
    }
};
