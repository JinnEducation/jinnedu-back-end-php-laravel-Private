<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('supports', function (Blueprint $table) {
            $table->id();
            $table->String('subject')->nullable();
            $table->text('note')->nullable();
            $table->String('name')->nullable();
            $table->String('email')->nullable();
            $table->String('file')->nullable();
            $table->String('type')->nullable();
            $table->String('extention')->nullable();
            $table->String('path')->nullable();
            $table->integer('size')->default(0);

            $table->integer('status')->default(0);
            $table->integer('sortable')->default(0);

            $table->String('token')->nullable();

            $table->integer('reply_id')->default(0);
            $table->integer('country_id')->default(0);
            $table->integer('user_id');
            $table->String('ipaddress')->nullable();
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
        Schema::dropIfExists('supports');
    }
};
