<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Drop old exam-related tables in correct order (respecting foreign keys)
        Schema::dropIfExists('group_class_answers_langs');
        Schema::dropIfExists('group_class_answers');
        Schema::dropIfExists('group_class_question_langs');
        Schema::dropIfExists('group_class_question');
        Schema::dropIfExists('group_class_exam');
        
        Schema::dropIfExists('answers_langs');
        Schema::dropIfExists('answers');
        Schema::dropIfExists('exams_langs');
        Schema::dropIfExists('exams');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Cannot restore dropped tables without data
        // This migration is destructive and cannot be reversed
    }
};

