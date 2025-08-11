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
        Schema::create('tutor_tranfers', function (Blueprint $table) {
            $table->id();
            // Foreign key to "tutors" table
            // $table->foreignId('tutor_id')->constrained('tutors')->onDelete('cascade');
            $table->integer('tutor_id');
            // Foreign key to "orders" table
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');

            $table->integer('type')->default(0);
            $table->decimal('percentage', $precision = 8, $scale = 2)->default(0);
            $table->decimal('amount', $precision = 8, $scale = 2)->default(0);
            $table->boolean('status')->default(0);
            $table->string('note')->nullable();
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
        Schema::dropIfExists('tutor_tranfers');
    }
};
