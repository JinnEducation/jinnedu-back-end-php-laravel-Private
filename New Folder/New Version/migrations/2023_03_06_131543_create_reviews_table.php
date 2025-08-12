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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->string('type')->nullable();
            // need to be reviewed
            $table->integer('ref_id')->default(0);
            //

            $table->boolean('stars')->default(0);
            $table->boolean('status')->default(0);
            $table->boolean('sortable')->default(0);

            $table->text('comment')->nullable();

            // Foreign key to "users" table
            $table->foreignId('users_id')->constrained('users')->onDelete('cascade');

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
        Schema::dropIfExists('reviews');
    }
};
