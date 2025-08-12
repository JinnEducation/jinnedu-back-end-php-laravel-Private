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
        Schema::create('conference_complaints', function (Blueprint $table) {
            $table->id();
            // Foreign key to "conferences" table
            $table->foreignId('conference_id')->constrained('conferences')->onDelete('cascade');

            $table->string('subject')->nullable();
            $table->text('note')->nullable();
            $table->string('name')->nullable();
            $table->string('file')->nullable();
            $table->string('type')->nullable();
            $table->string('extention')->nullable();
            $table->string('path')->nullable();
            $table->integer('size')->default(0);

            $table->boolean('status')->default(0);
            $table->boolean('sortable')->default(0);

            $table->string('token')->nullable();

            // need to be reviewed
            $table->integer('reply_id')->default(0);
            $table->integer('student_id')->default(0);
            //
            // Foreign key to "tutors" table
            // $table->foreignId('tutor_id')->constrained('tutors')->onDelete('cascade');

            $table->integer('tutor_id');
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
        Schema::dropIfExists('conference_complaints');
    }
};
