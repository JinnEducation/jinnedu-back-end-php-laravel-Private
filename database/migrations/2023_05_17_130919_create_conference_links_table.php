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
        Schema::create('conference_links', function (Blueprint $table) {
            $table->id();
            $table->integer('ref_type')->default(0);
            // need to be reviewed
            $table->integer('ref_id')->default(0);
            //
            // Foreign key to "orders" table
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            // Foreign key to "conferences" table
            $table->foreignId('conference_id')->constrained('conferences')->onDelete('cascade');

            $table->integer('status')->default(0);

            // need to be reviewed
            $table->string('class_id')->nullable();
            //
            
            
            $table->string('user_name')->nullable();
            $table->string('is_teacher')->nullable();
            $table->string('lesson_name')->nullable();
            $table->string('course_name')->nullable();

            $table->string('type')->nullable();
            $table->text('response')->nullable();

            $table->text('notes')->nullable();

            // Foreign key to "users" table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

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
        Schema::dropIfExists('conference_links');
    }
};