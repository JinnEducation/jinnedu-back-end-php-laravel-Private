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
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();

            // ارتباط 1:1 مع المستخدم
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->unique();

            // معلومات عامة مشتركة
            $table->string('first_name', 100);
            $table->string('last_name', 100);
            $table->string('email_display')->nullable();
            $table->string('country', 100)->nullable();
            $table->string('contact_number', 50)->nullable();
            $table->string('avatar_path', 255)->nullable();
            $table->boolean('terms_agreed')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_profiles');
    }
};
