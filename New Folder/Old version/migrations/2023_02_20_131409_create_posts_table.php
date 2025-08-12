<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
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
            $table->integer('depid')->default(0);
            $table->integer('image')->default(0);
            $table->integer('status')->default(0);
            $table->integer('publish')->default(0);
            $table->dateTime('publish_date')->nullable();
            $table->integer('sortable')->default(0);
            $table->integer('template')->default(0);
            
            $table->integer('rating')->default(0);
            $table->integer('sum_rating')->default(0);
            $table->integer('num_rating')->default(0);
            
            $table->integer('readmore')->default(0);
            $table->integer('content_type')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('views')->default(0);
            $table->integer('main')->default(0);
            
            $table->integer('authorid')->default(0);
            $table->integer('variant')->default(0);
            
            $table->String('metadata')->nullable();
            $table->text('embed')->nullable();
            
            $table->String('token')->nullable();
            $table->integer('user_id');
            $table->String('ipaddress')->nullable();
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
