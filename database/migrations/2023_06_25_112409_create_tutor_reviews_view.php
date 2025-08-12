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
            CREATE OR REPLACE VIEW tutor_reviews 
            AS
            SELECT
                reviews.*, reviews.ref_id as conference_id, conferences.tutor_id
            FROM
                reviews
            LEFT JOIN conferences on (reviews.ref_id = conferences.id);
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tutor_reviews');
    }
};
