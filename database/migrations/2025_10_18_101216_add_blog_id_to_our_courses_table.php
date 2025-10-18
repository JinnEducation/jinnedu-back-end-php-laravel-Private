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
        Schema::table('our_courses', function (Blueprint $table) {
            $table->foreignId('blog_id')
                ->nullable()
                ->constrained('blog')   
                ->nullOnDelete()
                ->after('category_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('our_courses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('blog_id');
        });
    }
};
