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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            // Foreign key to "levels" table
            $table->foreignId('level_id')->constrained('levels')->onDelete('cascade');

            // Foreign key to "categories" table
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');

            // Foreign key to "users" table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('exams_langs', function (Blueprint $table) {
            $table->id();
            // Foreign key to "exams" table
            $table->foreignId('exam_id')->constrained('exams')->onDelete('cascade');

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
        Schema::dropIfExists('exams');
        Schema::dropIfExists('exams_langs');
    }
};
