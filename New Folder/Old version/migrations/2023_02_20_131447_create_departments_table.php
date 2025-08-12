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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            
            $table->string('name')->default(0);
            $table->integer('parent_id')->default(0);
            $table->integer('image')->default(0);
            $table->integer('icon')->default(0);
            $table->integer('banner')->default(0);
            $table->String('class')->nullable();
            $table->String('color')->nullable();
            $table->integer('status')->default(0);
            $table->integer('main')->default(0);
            $table->integer('sortable')->default(0);
            $table->integer('template')->default(0);
            
            $table->integer('childrens')->default(0);
            $table->integer('posts')->default(0);

            $table->integer('readmore')->default(0);
            $table->integer('content_type')->default(0);
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
        Schema::dropIfExists('departments');
    }
};
