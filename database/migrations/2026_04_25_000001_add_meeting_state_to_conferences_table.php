<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('conferences', function (Blueprint $table) {
            if (! Schema::hasColumn('conferences', 'meeting_started_at')) {
                $table->timestamp('meeting_started_at')->nullable();
            }
            if (! Schema::hasColumn('conferences', 'started_notification_sent_at')) {
                $table->timestamp('started_notification_sent_at')->nullable();
            }
            if (! Schema::hasColumn('conferences', 'recording_reminder_sent_at')) {
                $table->timestamp('recording_reminder_sent_at')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('conferences', function (Blueprint $table) {
            if (Schema::hasColumn('conferences', 'meeting_started_at')) {
                $table->dropColumn('meeting_started_at');
            }
            if (Schema::hasColumn('conferences', 'started_notification_sent_at')) {
                $table->dropColumn('started_notification_sent_at');
            }
            if (Schema::hasColumn('conferences', 'recording_reminder_sent_at')) {
                $table->dropColumn('recording_reminder_sent_at');
            }
        });
    }
};
