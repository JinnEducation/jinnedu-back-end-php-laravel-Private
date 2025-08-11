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
        Schema::create('conference_notes', function (Blueprint $table) {
            $table->id();
            // Foreign key to "conferences" table
            $table->foreignId('conference_id')->constrained('conferences')->onDelete('cascade');

            $table->text('note')->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('sortable')->default(0);

            $table->string('token')->nullable();

            // Foreign key to "users" table
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('ipaddress')->nullable();
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
        Schema::dropIfExists('conference_notes');
    }
};
