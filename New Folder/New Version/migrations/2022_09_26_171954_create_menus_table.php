<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('p_id')->nullable();
            $table->string('title');
            $table->string('name')->nullable();
            $table->string('route')->nullable();
            $table->text('slug')->nullable();
            $table->string('svg')->nullable();
            $table->string('icon')->nullable();
            $table->string('image')->nullable();
            $table->string('font')->nullable();
            $table->string('color')->nullable();
            $table->string('note')->nullable();
            $table->string('status')->nullable();
            $table->boolean('invisible')->default(0);
            $table->string('type')->nullable();
            $table->text('active_routes')->nullable();
            $table->boolean('sortable')->default(0);
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
        Schema::dropIfExists('menus');
    }
}