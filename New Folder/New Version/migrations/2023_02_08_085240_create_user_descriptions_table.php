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
        Schema::create('user_descriptions', function (Blueprint $table) {
            // Foreign key to "users" table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Foreign key to "specializations" table
            $table->foreignId('specialization_id')->nullable()->constrained('specializations')->nullOnDelete();

            $table->text('headline')->nullable();
            $table->text('interests')->nullable();
            $table->text('experience')->nullable();
            $table->text('specialization')->nullable();
            $table->text('methodology')->nullable();
            $table->text('motivation')->nullable();



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
        Schema::dropIfExists('user_descriptions');
    }
};
