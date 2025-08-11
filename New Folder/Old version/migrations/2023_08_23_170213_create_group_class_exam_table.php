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
        Schema::create('group_class_exam', function (Blueprint $table) {
            $table->id();
            $table->Integer('class_id');
            $table->Integer('student_id');
            $table->Integer('question_no')->default(0);
            $table->Integer('success_rate')->default(0);
            $table->float('result')->default(0);
            $table->timestamps();
        });

        Schema::create('group_class_question', function (Blueprint $table) {
            $table->id();
            $table->Integer('class_id');
            $table->Integer('exam_id');
            $table->Integer('group_class_exam_id');
            $table->timestamps();
        });

        Schema::create('group_class_question_langs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->Integer('lang_id');
            $table->Integer('group_class_question_id');
            $table->Integer('group_class_exam_id');
            $table->timestamps();
        });

        Schema::create('group_class_answers', function (Blueprint $table) {
            $table->id();
            $table->Integer('class_id');
            $table->tinyInteger('is_true')->default(0);
            $table->tinyInteger('student_answer')->default(0);
            $table->Integer('group_class_exam_id');
            $table->Integer('group_class_question_id');
            $table->timestamps();
        });

        Schema::create('group_class_answers_langs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->Integer('lang_id');
            $table->Integer('group_class_answer_id');
            $table->Integer('group_class_exam_id');
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
        Schema::dropIfExists('group_class_exam');
        Schema::dropIfExists('group_class_question');
        Schema::dropIfExists('group_class_question_langs');
        Schema::dropIfExists('group_class_answers');
        Schema::dropIfExists('group_class_answers_langs');
    }
};
