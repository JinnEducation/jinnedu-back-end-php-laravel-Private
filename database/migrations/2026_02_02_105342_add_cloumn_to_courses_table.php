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
        Schema::table('courses', function (Blueprint $table) {
            $table->string('course_image')->nullable()->after('instructor_id');
            $table->decimal('course_duration_hours', 10, 2)->default(0)->nullable()->after('course_image');
            $table->string('certificate_image')->nullable()->after('course_duration_hours');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('course_image');
            $table->dropColumn('course_duration_hours');
            $table->dropColumn('certificate_image');
        });
    }
};
