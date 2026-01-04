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
        Schema::dropIfExists('our_course_reviews');
        Schema::dropIfExists('our_course_tutors');
        Schema::dropIfExists('our_course_levels');
        Schema::dropIfExists('our_course_langs');
        Schema::dropIfExists('our_course_dates');
        Schema::dropIfExists('our_courses');

        Schema::dropIfExists('course_langs');
        Schema::dropIfExists('courses');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
