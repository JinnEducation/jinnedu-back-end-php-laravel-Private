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
        Schema::create('currency_latest', function (Blueprint $table) {
            $table->id();
            $table->integer('currency_id')->default(0);
            $table->String('currency_code')->nullable();
            $table->decimal('exchange', $precision = 8, $scale = 2)->default(0);
            $table->String('last_updated_at')->nullable();
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
        Schema::dropIfExists('currency_latest');
    }
};
