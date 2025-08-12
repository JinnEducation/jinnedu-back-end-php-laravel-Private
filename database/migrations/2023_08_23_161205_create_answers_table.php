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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            // Foreign key to "exams" table
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');

            // Foreign key to "users" table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->boolean('is_true')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('answers_langs', function (Blueprint $table) {
            $table->id();
            // Foreign key to "exams" table
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');

            // Foreign key to "answers" table
            $table->foreignId('answer_id')->constrained('answers')->onDelete('cascade');

            // Foreign key to "languages" table
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');

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
