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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            // Foreign key to "users" table
            $table->foreignId('from_user')->nullable()->constrained('users')->nullOnDelete();

            // Foreign key to "users" table
            $table->foreignId('to_user')->nullable()->constrained('users')->nullOnDelete();

            // need to be reviewed
            // $table->integer('from_user');
            // $table->integer('to_user');
            //
            $table->text('message')->nullable();

            $table->boolean('status')->default(0);
            $table->boolean('fav')->default(0);
            $table->boolean('seen')->default(0);
            $table->dateTime('seen_date')->nullable();

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
        Schema::dropIfExists('chats');
    }
};