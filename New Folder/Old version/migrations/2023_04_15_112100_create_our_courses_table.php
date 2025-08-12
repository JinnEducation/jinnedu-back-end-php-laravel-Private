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
        Schema::create('our_courses', function (Blueprint $table) {
            $table->id();
            
            $table->string('name')->nullable();
            $table->integer('category_id')->default(0);
            $table->integer('lessons')->default(0);
            $table->integer('class_length')->default(0);
            $table->integer('status')->default(0);
            $table->integer('image')->default(0);
            $table->integer('sortable')->default(0);

            $table->integer('likes')->default(0);
            $table->integer('views')->default(0);
            
            $table->String('token')->nullable();
            $table->text('embed')->nullable();
            $table->text('metadata')->nullable();
            
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
        Schema::dropIfExists('our_courses');
    }
};
