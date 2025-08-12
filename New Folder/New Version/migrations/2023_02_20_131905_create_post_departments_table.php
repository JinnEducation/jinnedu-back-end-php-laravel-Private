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
        Schema::create('post_departments', function (Blueprint $table) {
            $table->id();
            // Foreign key to "posts" table
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');

            // Foreign key to "departments" table
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');


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
        Schema::dropIfExists('post_departments');
    }
};
