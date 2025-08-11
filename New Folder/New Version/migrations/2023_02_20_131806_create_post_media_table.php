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
        Schema::create('post_media', function (Blueprint $table) {
            $table->id();

            // Foreign key to "posts" table
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');

            // Foreign key to "media" table
            $table->foreignId('media_id')->constrained('media')->onDelete('cascade');

            // Foreign key to "users" table
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');

            $table->boolean('status')->default(0);
            $table->boolean('main')->default(0);
            $table->boolean('sortable')->default(0);

            $table->integer('likes')->default(0);
            $table->integer('views')->default(0);

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
        Schema::dropIfExists('post_media');
    }
};