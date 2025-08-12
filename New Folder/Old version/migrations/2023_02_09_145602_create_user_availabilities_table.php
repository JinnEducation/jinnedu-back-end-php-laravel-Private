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
        Schema::create('user_availabilities', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('timezone_id')->default(0);
            $table->integer('day_id')->default(0);
            $table->integer('status')->default(0);
            $table->String('hour_from')->nullable();
            $table->String('hour_to')->nullable();
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
        Schema::dropIfExists('user_availabilities');
    }
};
