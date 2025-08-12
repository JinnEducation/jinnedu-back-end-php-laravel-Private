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
            CREATE OR REPLACE VIEW group_class_reviews 
            AS
            SELECT
                reviews.*, reviews.ref_id as conference_id, conferences.tutor_id, group_classes.id as class_id
            FROM
                reviews
            LEFT JOIN conferences on (reviews.ref_id = conferences.id)
            LEFT JOIN group_classes on (conferences.ref_id = group_classes.id)
            WHERE conferences.ref_type=1;
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_class_reviews');
    }
};
