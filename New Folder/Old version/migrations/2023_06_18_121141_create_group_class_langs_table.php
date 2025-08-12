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
        Schema::create('group_class_langs', function (Blueprint $table) {
            $table->id();
            $table->string('classid')->default(0);
            $table->string('langid')->default(0);
            
            $table->integer('status')->default(0);
            $table->integer('sortable')->default(0);

            $table->integer('likes')->default(0);
            $table->integer('views')->default(0);
            
            $table->String('slug')->nullable();
            $table->String('title')->nullable();
            $table->text('about')->nullable();
            $table->text('headline')->nullable();
            $table->text('information')->nullable();
            
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
        Schema::dropIfExists('group_class_langs');
    }
};
