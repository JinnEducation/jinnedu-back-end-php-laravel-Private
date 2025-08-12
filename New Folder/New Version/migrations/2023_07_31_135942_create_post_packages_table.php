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
        Schema::create('post_packages', function (Blueprint $table) {
            $table->id();
            // Foreign key to "posts" table
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');

            $table->decimal('price', $precision = 8, $scale = 2);
            $table->integer('group_class_count');
            $table->integer('our_course_count');
            $table->integer('private_lesson_count');
            // Foreign key to "users" table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

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
        Schema::dropIfExists('post_packages');
    }
};
