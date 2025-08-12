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
        Schema::create('our_course_langs', function (Blueprint $table) {
            $table->id();
            // Foreign key to "our_courses" table
            $table->foreignId('our_course_id')->constrained('our_courses')->onDelete('cascade');

            // Foreign key to "users" table
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');


            $table->boolean('status')->default(0);
            $table->boolean('sortable')->default(0);

            $table->integer('likes')->default(0);
            $table->integer('views')->default(0);

            $table->string('slug')->nullable();
            $table->string('title')->nullable();
            $table->text('about')->nullable();
            $table->text('headline')->nullable();
            $table->text('information')->nullable();

            // Foreign key to "users" table
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');

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
        Schema::dropIfExists('our_course_langs');
    }
};
