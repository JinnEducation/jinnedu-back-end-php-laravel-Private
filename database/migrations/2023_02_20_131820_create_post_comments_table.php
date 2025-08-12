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
        Schema::create('post_comments', function (Blueprint $table) {
            $table->id();

            // Foreign key to "posts" table
            $table->foreignId('post_id')->constrained('posts')->onDelete('cascade');

            // Foreign key to "languages" table
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');

            $table->boolean('status')->default(0);
            $table->boolean('sortable')->default(0);

            // need to be reviewed
            $table->integer('readmore')->default(0);
            //

            $table->integer('likes')->default(0);
            $table->integer('views')->default(0);

            $table->string('title')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('country')->nullable();
            $table->string('url')->nullable();
            $table->string('mobile')->nullable();
            $table->string('phone')->nullable();
            $table->text('comment')->nullable();

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
        Schema::dropIfExists('post_comments');
    }
};