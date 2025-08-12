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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            $table->integer('ref_type')->default(0);
            $table->integer('ref_id')->default(0);
            $table->decimal('price', $precision = 8, $scale = 2)->default(0);
            $table->integer('status')->default(0);

            $table->String('token')->nullable();
            $table->text('payment')->nullable();
            $table->text('metadata')->nullable();
            
            $table->integer('user_id');
            $table->String('ipaddress')->nullable();
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
