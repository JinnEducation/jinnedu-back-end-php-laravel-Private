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
        Schema::create('user_certifications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('subject_id')->default(0);
            $table->String('certificate')->nullable();
            $table->String('description')->nullable();
            $table->String('issued_by')->nullable();
            $table->String('attachment')->nullable();
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
        Schema::dropIfExists('user_certifications');
    }
};
