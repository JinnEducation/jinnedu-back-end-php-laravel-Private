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
        Schema::create('categ_blog_langs', function (Blueprint $table) {
            $table->id();
             $table->foreignId('categ_blog_id')->nullable()->constrained('categ_blog')->nullOnDelete();
            $table->foreignId('language_id')->nullable()->constrained('languages')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categ_blog_langs');
    }
};
