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
        Schema::create('user_languages', function (Blueprint $table) {
            $table->id();
            // Foreign key to "users" table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Foreign key to "levels" table
            $table->foreignId('level_id')->nullable()->constrained('levels')->nullOnDelete();

            // Foreign key to "specializations" table
            $table->foreignId('language_id')->nullable()->constrained('languages')->nullOnDelete();


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
        Schema::dropIfExists('user_languages');
    }
};