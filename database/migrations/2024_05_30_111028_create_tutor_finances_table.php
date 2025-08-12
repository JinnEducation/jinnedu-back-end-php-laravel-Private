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
        Schema::create('tutor_finances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tutor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->integer('ref_type')->default(0)->comment('1=>group_class, 4=>private_lesson');
            $table->integer('ref_id')->default(0);
            $table->decimal('percentage', $precision = 8, $scale = 2)->default(0);
            $table->decimal('fee', $precision = 8, $scale = 2)->default(0);
            $table->timestamp('class_date')->nullable(); // private lesson class date or the last class date of the group class
            $table->enum('status', ['pending', 'transferred', 'rejected'])->default('pending');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('tutor_finances');
    }
};
