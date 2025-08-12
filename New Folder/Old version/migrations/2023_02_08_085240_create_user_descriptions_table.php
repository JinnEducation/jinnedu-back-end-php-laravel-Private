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
        Schema::create('user_descriptions', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->String('headline')->nullable();
            $table->String('interests')->nullable();
            $table->String('experience')->nullable();
            $table->String('specialization')->nullable();
            $table->String('methodology')->nullable();
            $table->String('motivation')->nullable();
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
        Schema::dropIfExists('user_descriptions');
    }
};
