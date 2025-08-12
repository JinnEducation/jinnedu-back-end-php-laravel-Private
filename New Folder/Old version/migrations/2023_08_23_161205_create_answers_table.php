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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->integer('exam_id');
            $table->integer('user_id');
            $table->tinyInteger('is_true')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('answers_langs', function (Blueprint $table) {
            $table->id();
            $table->integer('exam_id');
            $table->integer('answer_id');
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
        Schema::dropIfExists('answers');
        Schema::dropIfExists('answers_langs');
    }
};