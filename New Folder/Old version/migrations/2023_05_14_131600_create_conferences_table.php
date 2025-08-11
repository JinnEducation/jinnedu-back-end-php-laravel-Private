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
        Schema::create('conferences', function (Blueprint $table) {
            $table->id();
            $table->integer('ref_type')->default(0);
            $table->integer('ref_id')->default(0);
            $table->integer('order_id')->default(0);
            $table->integer('status')->default(0);

            $table->String('title')->nullable();
            $table->String('date')->nullable();
            $table->String('start_time')->nullable();
            $table->String('end_time')->nullable();
            $table->integer('record')->default(0);
            $table->integer('timezone')->default(0);

            $table->String('type')->nullable();
            $table->text('response')->nullable();

            $table->text('notes')->nullable();

            $table->integer('tutor_id');
            $table->integer('student_id');
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
        Schema::dropIfExists('conferences');
    }
};
