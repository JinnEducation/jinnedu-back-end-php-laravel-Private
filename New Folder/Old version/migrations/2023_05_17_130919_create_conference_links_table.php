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
        Schema::create('conference_links', function (Blueprint $table) {
            $table->id();
            $table->integer('ref_type')->default(0);
            $table->integer('ref_id')->default(0);
            $table->integer('order_id')->default(0);
            $table->integer('conference_id')->default(0);
            $table->integer('status')->default(0);

            $table->String('class_id')->nullable();
            $table->String('user_name')->nullable();
            $table->String('is_teacher')->nullable();
            $table->String('lesson_name')->nullable();
            $table->String('course_name')->nullable();

            $table->String('type')->nullable();
            $table->text('response')->nullable();
            
            $table->text('notes')->nullable();
            
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
        Schema::dropIfExists('conference_links');
    }
};
