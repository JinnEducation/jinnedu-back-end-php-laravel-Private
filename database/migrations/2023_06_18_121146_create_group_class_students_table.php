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
        Schema::create('group_class_students', function (Blueprint $table) {
            $table->id();
            // need to be reviewed
            $table->string('class_id')->default(0);
            $table->integer('student_id');
            //
            // Foreign key to "orders" table
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            $table->boolean('status')->default(0);
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
        Schema::dropIfExists('group_class_students');
    }
};
