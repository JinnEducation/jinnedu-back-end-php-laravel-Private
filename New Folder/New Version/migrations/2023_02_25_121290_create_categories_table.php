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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            $table->string('name')->default(0);

            // Foreign key to "categories" table
            $table->foreignId('parent_id')->nullable()->constrained('categories')->nullOnDelete();

            // need to be reviewed
            $table->integer('image')->default(0);
            $table->integer('icon')->default(0);
            $table->integer('template')->default(0);
            $table->integer('childrens')->default(0);
            $table->integer('posts')->default(0);
            $table->integer('readmore')->default(0);
            $table->integer('content_type')->default(0);
            //

            $table->integer('banner')->default(0);
            $table->string('class')->nullable();
            $table->string('color')->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('main')->default(0);
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
        Schema::dropIfExists('categories');
    }
};
