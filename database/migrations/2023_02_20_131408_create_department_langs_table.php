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
        Schema::create('department_langs', function (Blueprint $table) {
            $table->id();

            // Foreign key to "departments" table
            $table->foreignId('department_id')->constrained('departments')->onDelete('cascade');


            // Foreign key to "languages" table
            $table->foreignId('language_id')->constrained('languages')->onDelete('cascade');


            $table->boolean('status')->default(0);
            $table->boolean('main')->default(0);
            $table->boolean('sortable')->default(0);

            // need to be reviewed
            $table->integer('readmore')->default(0);
            //

            $table->integer('likes')->default(0);
            $table->integer('views')->default(0);

            $table->string('slug')->nullable();
            $table->string('title')->nullable();
            $table->text('summary')->nullable();
            $table->text('description')->nullable();
            $table->text('keywords')->nullable();

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
        Schema::dropIfExists('department_langs');
    }
};
