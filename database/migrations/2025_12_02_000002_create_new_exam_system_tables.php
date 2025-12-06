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
        // 1. exams - الامتحان الرئيسي
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('level_id')->constrained('levels')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('group_class_id')->constrained('group_classes')->onDelete('cascade');
            $table->integer('duration_minutes')->default(60);
            $table->integer('pass_percentage')->default(50);
            $table->integer('total_marks')->default(100);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. exams_langs - ترجمة الامتحان
        Schema::create('exams_langs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
            $table->text('title');
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->timestamps();
        });

        // 3. exam_questions - أسئلة الامتحان
        Schema::create('exam_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->integer('question_no')->default(1);
            $table->string('type')->default('mcq'); // mcq, true_false, text
            $table->integer('score')->default(1);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 4. exam_question_langs - ترجمة نص السؤال
        Schema::create('exam_question_langs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('exam_questions')->onDelete('cascade');
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
            $table->text('title');
            $table->text('explanation')->nullable();
            $table->timestamps();
        });

        // 5. exam_answers - خيارات الإجابة
        Schema::create('exam_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('exam_questions')->onDelete('cascade');
            $table->boolean('is_correct')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // 6. exam_answer_langs - ترجمة نص خيار الإجابة
        Schema::create('exam_answer_langs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('answer_id')->constrained('exam_answers')->onDelete('cascade');
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');
            $table->text('title');
            $table->timestamps();
        });

        // 7. exam_attempts - محاولة الطالب للامتحان
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');
            $table->foreignId('class_id')->constrained('group_classes')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->integer('attempt_no')->default(1);
            $table->integer('success_rate')->default(0);
            $table->string('result')->default('not_completed'); // passed, failed, not_completed
            $table->integer('total_score')->default(0);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();
        });

        // 8. exam_attempt_answers - إجابات الطالب على كل سؤال
        Schema::create('exam_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('exam_attempts')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('exam_questions')->onDelete('cascade');
            $table->foreignId('answer_id')->nullable()->constrained('exam_answers')->onDelete('cascade');
            $table->boolean('is_correct')->default(false);
            $table->text('selected_value')->nullable();
            $table->integer('time_spent_sec')->default(0);
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
        Schema::dropIfExists('exam_attempt_answers');
        Schema::dropIfExists('exam_attempts');
        Schema::dropIfExists('exam_answer_langs');
        Schema::dropIfExists('exam_answers');
        Schema::dropIfExists('exam_question_langs');
        Schema::dropIfExists('exam_questions');
        Schema::dropIfExists('exams_langs');
        Schema::dropIfExists('exams');
    }
};

