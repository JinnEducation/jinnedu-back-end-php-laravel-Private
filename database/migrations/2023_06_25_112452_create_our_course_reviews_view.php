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
        \DB::statement("
            CREATE OR REPLACE VIEW our_course_reviews 
            AS
            SELECT
                reviews.*, reviews.ref_id as conference_id, conferences.tutor_id, our_courses.id as course_id
            FROM
                reviews
            LEFT JOIN conferences on (reviews.ref_id = conferences.id)
            LEFT JOIN our_courses on (conferences.ref_id = our_courses.id)
            WHERE conferences.ref_type=2;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('our_course_reviews');
    }
};
