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
        Schema::create('course_live_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained('course_items')->cascadeOnDelete();
            $table->foreignId('instructor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('zoom_meeting_id')->nullable();
            $table->string('join_url_host')->nullable();
            $table->string('join_url_attendee')->nullable();

            $table->foreignId('recording_item_id')->nullable()->constrained('course_items')->nullOnDelete();
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
        Schema::dropIfExists('course_live_sessions');
    }
};
