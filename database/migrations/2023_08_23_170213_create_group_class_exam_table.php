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
        Schema::create('group_class_exam', function (Blueprint $table) {
            $table->id();
            // need to be reviewed
            $table->Integer('class_id');
            $table->Integer('student_id');
            //
            $table->Integer('question_no')->default(0);
            $table->Integer('success_rate')->default(0);
            $table->float('result')->default(0);
            $table->timestamps();
        });

        Schema::create('group_class_question', function (Blueprint $table) {
            $table->id();
            // need to be reviewed
            $table->Integer('class_id');
            //
            // Foreign key to "exams" table
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');

            // Foreign key to "group_class_exam" table
            $table->foreignId('group_class_exam_id')->constrained('group_class_exam')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('group_class_question_langs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            // Foreign key to "languages" table
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');

            // Foreign key to "group_class_question" table
            $table->foreignId('group_class_question_id')->constrained('group_class_question')->onDelete('cascade');

            // Foreign key to "group_class_exam" table
            $table->foreignId('group_class_exam_id')->constrained('group_class_exam')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('group_class_answers', function (Blueprint $table) {
            $table->id();
            // need to be reviewed
            $table->Integer('class_id');
            //
            $table->boolean('is_true')->default(0);
            $table->tinyInteger('student_answer')->default(0);
            // Foreign key to "group_class_exam" table
            $table->foreignId('group_class_exam_id')->constrained('group_class_exam')->onDelete('cascade');

            // Foreign key to "group_class_question" table
            $table->foreignId('group_class_question_id')->constrained('group_class_question')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('group_class_answers_langs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->Integer('lang_id');
            // Foreign key to "languages" table
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');

            // Foreign key to "group_class_answer" table
            $table->foreignId('group_class_answer_id')->constrained('group_class_answers')->onDelete('cascade');

            // Foreign key to "group_class_exam" table
            $table->foreignId('group_class_exam_id')->constrained('group_class_exam')->onDelete('cascade');

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
