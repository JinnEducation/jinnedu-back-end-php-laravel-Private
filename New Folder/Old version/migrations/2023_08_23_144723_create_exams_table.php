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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->integer('level_id');
            $table->integer('category_id');
            $table->integer('user_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('exams_langs', function (Blueprint $table) {
            $table->id();
            $table->integer('exam_id');
            $table->integer('lang_id');
            $table->string('title');
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
        Schema::dropIfExists('exams');
        Schema::dropIfExists('exams_langs');
    }
};
