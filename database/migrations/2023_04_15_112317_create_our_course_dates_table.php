<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('our_course_dates', function (Blueprint $table) {
            $table->id();
         
            // Foreign key to "users" table
            $table->foreignId('our_course_id')->constrained('our_courses')->onDelete('cascade');

            // Foreign key to "levels" table
            $table->foreignId('level_id')->constrained('levels')->onDelete('cascade');

            $table->integer('lessons')->default(0);
            $table->float('lesson_price')->default(0);
            $table->dateTime('class_date')->nullable();

            // Foreign key to "users" table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

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