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
        Schema::create('our_course_tutors', function (Blueprint $table) {
            $table->id();
            // Foreign key to "our_courses" table
            $table->foreignId('our_course_id')->constrained('our_courses')->onDelete('cascade');

            // Foreign key to "tutors" table
            // $table->foreignId('tutor_id')->constrained('tutors')->onDelete('cascade');
            $table->integer('tutor_id');
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
        Schema::dropIfExists('our_course_tutors');
    }
};