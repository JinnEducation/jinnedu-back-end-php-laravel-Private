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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->integer('ref_type')->default(0);
            // need to be reviewed
            $table->integer('ref_id')->default(0);
            //
            $table->decimal('price', $precision = 8, $scale = 2)->default(0);
            $table->integer('status')->default(0);

            $table->string('token')->nullable();
            $table->text('payment')->nullable();
            $table->text('metadata')->nullable();

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
        Schema::dropIfExists('orders');
    }
};
