<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EditNullToMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->text('svg')->nullable()->change();
            $table->string('route')->nullable()->change();
            $table->string('slug')->nullable()->change();
            $table->string('icon')->nullable()->change();
            $table->string('image')->nullable()->change();
            $table->string('font')->nullable()->change();
            $table->string('color')->nullable()->change();
            $table->string('note')->nullable()->change();
            $table->integer('status')->default(0)->change();
            $table->integer('p_id')->default(0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            //
        });
    }
}
