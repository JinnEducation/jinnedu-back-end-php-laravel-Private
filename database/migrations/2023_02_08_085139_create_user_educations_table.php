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
        Schema::create('user_educations', function (Blueprint $table) {
            $table->id();

            // Foreign key to "users" table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Foreign key to "degree_types" table
            $table->foreignId('degree_type_id')->nullable()->constrained('degree_types')->nullOnDelete();
            // Foreign key to "specializations" table
            $table->foreignId('specialization_id')->nullable()->constrained('specializations')->nullOnDelete();

            $table->string('university')->nullable();
            $table->string('degree')->nullable();
            $table->string('attachment')->nullable();
            $table->integer('years_from');
            $table->integer('years_to');
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
        Schema::dropIfExists('user_educations');
    }
};
