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
        Schema::create('tutor_profiles', function (Blueprint $table) {
            $table->id();

            // ارتباط 1:1 مع المستخدم
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->unique();

            // تبويبات 3 → 8
            $table->date('dob')->nullable();
            $table->string('tutor_country', 100)->nullable();
            $table->string('native_language', 100)->nullable();
            $table->string('teaching_subject', 150)->nullable();
            $table->string('teaching_experience', 50)->nullable();
            $table->string('situation', 100)->nullable();

            // نصوص تعريفية
            $table->text('headline')->nullable();
            $table->text('interests')->nullable();
            $table->text('motivation')->nullable();

            // تخصصات (CSV مؤقتاً)
            $table->string('specializations', 255)->nullable();

            // أقسام المحتوى
            $table->text('experience_bio')->nullable();
            $table->text('methodology')->nullable();

            // جدول التوفر والشهادات بصيغة JSON
            $table->text('availability_json')->nullable();
            $table->string('hourly_rate', 50)->nullable();
            $table->text('certifications_json')->nullable();

            // الفيديو
            $table->string('video_path', 255)->nullable();
            $table->boolean('video_terms_agreed')->default(false);

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
        Schema::dropIfExists('tutor_profiles');
    }
};
