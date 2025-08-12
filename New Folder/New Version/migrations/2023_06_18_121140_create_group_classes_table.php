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
        Schema::create('group_classes', function (Blueprint $table) {
            $table->id();

            $table->string('name')->nullable();
            // Foreign key to "categories" table
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');

            // Foreign key to "levels" table
            $table->foreignId('level_id')->constrained('levels')->onDelete('cascade');

            $table->integer('classes')->default(0);
            $table->integer('class_length')->default(0);
            // Foreign key to "frequencies" table
            $table->foreignId('frequency_id')->constrained('frequencies')->onDelete('cascade');

            $table->integer('min_size')->default(0);
            $table->boolean('status')->default(0);
            // need to be reviewed
            $table->integer('image')->default(0);
            //
            $table->boolean('sortable')->default(0);
            $table->boolean('publish')->default(0);
            $table->dateTime('publish_date')->nullable();

            $table->decimal('price', $precision = 8, $scale = 2)->default(0);

            $table->integer('likes')->default(0);
            $table->integer('views')->default(0);

            $table->string('token')->nullable();
            $table->text('embed')->nullable();
            $table->text('metadata')->nullable();

            // Foreign key to "tutors" table
            // $table->foreignId('tutor_id')->constrained('tutors')->onDelete('cascade');

            $table->integer('tutor_id');
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
        Schema::dropIfExists('group_classes');
    }
};
