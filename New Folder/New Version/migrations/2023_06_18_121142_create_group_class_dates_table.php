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
        Schema::create('group_class_dates', function (Blueprint $table) {
            $table->id();
            //$table->integer('gco_id')->default(0);
            // need to be reviewed
            $table->integer('class_id')->default(0);
            //
            //$table->integer('outline_id')->default(0);
            //$table->integer('tutor_id')->default(0);
            $table->dateTime('class_date')->nullable();

            // Foreign key to "users" table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

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
        Schema::dropIfExists('group_class_dates');
    }
};
