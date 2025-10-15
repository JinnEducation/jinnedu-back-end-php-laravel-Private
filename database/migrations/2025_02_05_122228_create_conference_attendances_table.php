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
    public function up(): void
    {
        if (!Schema::hasTable('conference_attendances')) {
        Schema::create('conference_attendances', function (Blueprint $table) {
            $table->id();
            $table->integer('ref_type')->default(0)->comment('1=>group_class, 4=>private_lesson');
            $table->integer('ref_id')->default(0);
            $table->foreignId('conference_id')->constrained('conferences')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('conference_attendances');
    }
};
