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
            // need to be reviewed
            $table->integer('ref_id')->default(0);
            //

            // Foreign key to "orders" table
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            $table->boolean('status')->default(0);

            $table->string('title')->nullable();
            $table->string('date')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->integer('record')->default(0);
            $table->integer('timezone')->default(0);

            $table->string('type')->nullable();
            $table->text('response')->nullable();

            $table->text('notes')->nullable();

            // Foreign key to "users" table
            // $table->foreignId('tutor_id')->constrained('tutors')->onDelete('cascade');

            // need to be reviewed (table doesn't exist)
            $table->integer('tutor_id');
            $table->integer('student_id');
            //
            $table->string('ipaddress')->nullable();
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