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
        Schema::create('blog_langs', function (Blueprint $table) {
            $table->id();
             $table->foreignId('blog_id')->nullable()->constrained('blog')->nullOnDelete();

            // Foreign key to "languages" table
           $table->foreignId('language_id')->nullable()->constrained('languages')->nullOnDelete();

            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');


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
        Schema::dropIfExists('blog_langs');
    }
};
