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
        Schema::create('conference_recordings', function (Blueprint $table) {
            $table->id();
             $table->foreignId('conference_id')
                ->constrained('conferences')
                ->cascadeOnDelete();
            $table->string('source_type'); // upload | url
            $table->string('media_url');   // uploaded path OR external URL
            $table->timestamps();       
            $table->index('conference_id');
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
        Schema::dropIfExists('conference_recordings');
    }
};
