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
        Schema::create('notification_infos', function (Blueprint $table) {
            $table->id();
            $table->String('n_title')->nullable();
            $table->text('n_details')->nullable();
            $table->String('n_url')->nullable();
            $table->String('n_icon')->nullable();
            $table->String('n_color')->nullable();
            $table->integer('n_seen')->default(0);
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
        Schema::dropIfExists('notification_infos');
    }
};