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
        if (!Schema::hasTable('group_class_tutors')) {
            Schema::create('group_class_tutors', function (Blueprint $table) {
                $table->id();
                $table->foreignId('group_class_id')->constrained('group_classes')->onDelete('cascade');
                $table->integer('tutor_id');
                $table->enum('status', ['in_review', 'rejected', 'approved'])->default('in_review');
                $table->timestamps();
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
        Schema::dropIfExists('group_class_tutors');
    }
};
