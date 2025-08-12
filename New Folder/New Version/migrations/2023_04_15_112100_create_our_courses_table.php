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
        Schema::create('our_courses', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            // Foreign key to "categories" table
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');

            $table->integer('lessons')->default(0);
            $table->integer('class_length')->default(0);
            $table->boolean('status')->default(0);
            $table->boolean('image')->default(0);
            $table->boolean('sortable')->default(0);

            $table->integer('likes')->default(0);
            $table->integer('views')->default(0);

            $table->string('token')->nullable();
            $table->text('embed')->nullable();
            $table->text('metadata')->nullable();

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
        Schema::dropIfExists('our_courses');
    }
};
