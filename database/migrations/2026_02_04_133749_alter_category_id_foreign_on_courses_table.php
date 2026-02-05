<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        // 1. حذف FK القديم بالقوة (حتى لو Laravel ما شايفه)
        DB::statement('ALTER TABLE courses DROP FOREIGN KEY courses_category_id_foreign');

        Schema::table('courses', function (Blueprint $table) {
            $table->foreign('category_id','fk_courses_category_id_foreign')
                ->references('id')
                ->on('course_categories')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {});
    }
};
