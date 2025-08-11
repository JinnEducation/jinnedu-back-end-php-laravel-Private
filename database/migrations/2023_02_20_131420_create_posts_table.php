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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            $table->string('name')->default(0);

            // Foreign key to "departments" table
            $table->foreignId('department_id')->nullable()->constrained('departments')->nullOnDelete();

            // need to be reviewed
            $table->integer('image')->default(0);
            $table->integer('template')->default(0);
            $table->integer('readmore')->default(0);
            $table->integer('content_type')->default(0);
            $table->integer('authorid')->default(0);
            $table->integer('variant')->default(0);

            $table->boolean('status')->default(0);
            $table->boolean('publish')->default(0);
            $table->dateTime('publish_date')->nullable();
            $table->boolean('sortable')->default(0);


            $table->integer('rating')->default(0);
            $table->integer('sum_rating')->default(0);
            $table->integer('num_rating')->default(0);

            $table->integer('likes')->default(0);
            $table->integer('views')->default(0);
            $table->integer('main')->default(0);


            $table->string('metadata')->nullable();
            $table->text('embed')->nullable();

            $table->string('token')->nullable();

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
        Schema::dropIfExists('posts');
    }
};
