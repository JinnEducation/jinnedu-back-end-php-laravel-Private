<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            if (! Schema::hasColumn('conferences', 'student_feedback_notification_sent_at')) {
                $table->timestamp('student_feedback_notification_sent_at')->nullable()->after('trial_followup_sent_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('conferences', function (Blueprint $table) {
            if (Schema::hasColumn('conferences', 'student_feedback_notification_sent_at')) {
                $table->dropColumn('student_feedback_notification_sent_at');
            }
        });
    }
};
