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
        Schema::create('our_course_dates', function (Blueprint $table) {
            $table->id();
            $table->integer('course_id')->default(0);
            $table->integer('level_id')->default(0);
            $table->integer('lessons')->default(0);
            $table->float('lesson_price')->default(0);
            $table->dateTime('class_date')->nullable();
            
            $table->integer('user_id');
            $table->String('ipaddress')->nullable();
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
        Schema::dropIfExists('our_course_dates');
    }
};
