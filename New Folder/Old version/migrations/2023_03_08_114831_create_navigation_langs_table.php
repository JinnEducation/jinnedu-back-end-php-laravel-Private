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
        Schema::create('navigation_langs', function (Blueprint $table) {
            $table->id();
           
            $table->string('navid')->default(0);
            $table->string('langid')->default(0);
            
            $table->integer('status')->default(0);
            $table->integer('main')->default(0);
            $table->integer('sortable')->default(0);

            $table->integer('readmore')->default(0);
            $table->integer('likes')->default(0);
            $table->integer('views')->default(0);
            
            $table->String('slug')->nullable();
            $table->String('title')->nullable();
            $table->text('summary')->nullable();
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();
            
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
        Schema::dropIfExists('navigation_langs');
    }
};
