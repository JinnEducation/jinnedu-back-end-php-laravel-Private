<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Conference;
use App\Events\NotifyBookedClassEvent;
use App\Models\GroupClassStudent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class NotifyBookedClass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'alarm:bookedClass';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify users for upcoming Conferences';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $now = Carbon::now();

        $this->notifyOneHourBefore($now);
        $this->notifyClassStarted($now);
        $this->notifyTutorToUploadRecording($now);
    }

    private function notifyOneHourBefore(Carbon $now): void
    {
        $conferences = Conference::whereIn('ref_type', [1,2,3,4])
            ->whereNull('reminder_sent_at')
            ->where('start_date_time', '>', $now)
            ->where('start_date_time', '<=', $now->copy()->addHour())
            ->get();

        foreach ($conferences as $conference) {
            $message = "You have class after an hour";
            event(new NotifyBookedClassEvent($conference, $message));
            $conference->reminder_sent_at = Carbon::now();
            $conference->save();
        }
    }

    private function notifyClassStarted(Carbon $now): void
    {
        $conferences = Conference::whereIn('ref_type', [1,2,3,4])
            ->whereNull('started_notification_sent_at')
            ->where('start_date_time', '<=', $now)
            ->where('end_date_time', '>', $now)
            ->get();

        foreach ($conferences as $conference) {
            $message = 'Your class has started. You can join now.';
            foreach ($this->conferenceStudents($conference) as $student) {
                sendUserDashboardNotification($student, $conference->title, $message, [
                    'type' => 'conference_started',
                    'conference_id' => $conference->id,
                    'url' => '/dashboard/conferences/student',
                    'icon' => 'fa fa-video-camera',
                    'color' => 'success',
                ]);
                $this->sendEmail($student, 'Class Started', $message);
            }

            $conference->started_notification_sent_at = Carbon::now();
            $conference->save();
        }
    }

    private function notifyTutorToUploadRecording(Carbon $now): void
    {
        $conferences = Conference::whereIn('ref_type', [1,2,3,4])
            ->whereNull('recording_reminder_sent_at')
            ->where('end_date_time', '<=', $now)
            ->whereDoesntHave('recordings')
            ->get();

        foreach ($conferences as $conference) {
            if (! $conference->tutor) {
                continue;
            }

            $message = 'Your class has ended. Please upload the class recording.';
            sendUserDashboardNotification($conference->tutor, $conference->title, $message, [
                'type' => 'upload_recording_reminder',
                'conference_id' => $conference->id,
                'url' => '/dashboard/conferences/tutor',
                'icon' => 'fa fa-upload',
                'color' => 'warning',
            ]);
            $this->sendEmail($conference->tutor, 'Upload Class Recording', $message);

            $conference->recording_reminder_sent_at = Carbon::now();
            $conference->save();
        }
    }

    private function conferenceStudents(Conference $conference)
    {
        if ((int) $conference->ref_type === 1) {
            $studentIds = GroupClassStudent::where('class_id', $conference->ref_id)->pluck('student_id');
            return User::whereIn('id', $studentIds)->get();
        }

        return $conference->student ? collect([$conference->student]) : collect();
    }

    private function sendEmail($user, string $subject, string $message): void
    {
        if (! $user?->email) {
            return;
        }

        Mail::to($user->email)->send(new \App\Mail\NotifyBookedClassMail([
            'user_name' => $user->name,
            'message' => $message,
            'subject' => $subject,
        ]));
    }
}
