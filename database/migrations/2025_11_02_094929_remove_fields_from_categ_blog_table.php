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
        Schema::table('categ_blog', function (Blueprint $table) {
             $table->dropColumn(['name', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categ_blog', function (Blueprint $table) {
        $table->string('name');
        $table->string('slug')->unique();
        });
    }
};
