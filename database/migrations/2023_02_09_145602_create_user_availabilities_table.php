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
        Schema::create('user_availabilities', function (Blueprint $table) {
            $table->id();
            // Foreign key to "users" table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            // Foreign key to "world_timezones" table
            $table->foreignId('timezone_id')->nullable()->constrained('world_timezones')->nullOnDelete();

            // Foreign key to "week_days" table
            $table->foreignId('day_id')->nullable()->constrained('week_days')->nullOnDelete();

            $table->boolean('status')->default(0);
            $table->string('hour_from')->nullable();
            $table->string('hour_to')->nullable();
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
        Schema::dropIfExists('user_availabilities');
    }
};
