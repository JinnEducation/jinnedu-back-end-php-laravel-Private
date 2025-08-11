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
        Schema::table('user_descriptions', function (Blueprint $table) {
            $table->text('headline')->change();
            $table->text('interests')->change();
            $table->text('experience')->change();
            $table->text('specialization')->change();
            $table->text('methodology')->change();
            $table->text('motivation')->change();
            $table->integer('specialization_id')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_descriptions', function (Blueprint $table) {
            //
        });
    }
};
