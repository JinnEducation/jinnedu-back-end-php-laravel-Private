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
        Schema::create('user_abouts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->String('first_name')->nullable();
            $table->String('last_name')->nullable();
            $table->String('phone')->nullable();
            $table->integer('country_id');
            $table->integer('level_id');
            $table->integer('language_id');
            $table->integer('subject_id');
            $table->integer('experience_id');
            $table->integer('situation_id');
            $table->integer('age');
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
        Schema::dropIfExists('users_abouts');
    }
};