<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Conference;
use App\Events\NotifyBookedClassEvent;
use App\Models\GroupClassStudent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

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
        $this->notifyTrialLessonEndedFollowup($now);
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

    private function notifyTrialLessonEndedFollowup(Carbon $now): void
    {
        if (! Schema::hasColumn('conferences', 'trial_followup_sent_at')) {
            return;
        }

        $conferences = Conference::where('ref_type', 3)
            ->whereNull('trial_followup_sent_at')
            ->whereNotNull('meeting_started_at')
            ->where('end_date_time', '<=', $now)
            ->with(['student', 'tutor.profile'])
            ->get();

        foreach ($conferences as $conference) {
            try {
                $student = $conference->student;
                $tutor = $conference->tutor;

                if (! $student || ! $tutor) {
                    $conference->trial_followup_sent_at = Carbon::now();
                    $conference->save();
                    continue;
                }

                $tutorUrl = route('site.tutor_jinn', ['id' => $tutor->id]);
                $title = __('site.Trial lesson completed');
                $message = __('site.Your trial lesson has ended. Would you like to book a real lesson with your tutor?');
                $actionText = __('site.Book a real lesson');

                sendUserDashboardNotification($student, $title, $message, [
                    'type' => 'trial_lesson_followup',
                    'conference_id' => $conference->id,
                    'tutor_id' => $tutor->id,
                    'url' => $tutorUrl,
                    'icon' => 'fa fa-calendar-plus',
                    'color' => 'primary',
                ]);

                if ($student->email) {
                    Mail::to($student->email)->send(new \App\Mail\NotifyBookedClassMail([
                        'user_name' => $student->name ?: $student->full_name,
                        'message' => $message,
                        'subject' => $title,
                        'action_url' => $tutorUrl,
                        'action_text' => $actionText,
                    ]));
                }

                $conference->trial_followup_sent_at = Carbon::now();
                $conference->save();
            } catch (\Throwable $e) {
                Log::error('Failed to send trial lesson follow-up notification: '.$e->getMessage(), [
                    'conference_id' => $conference->id,
                    'student_id' => $conference->student_id,
                    'tutor_id' => $conference->tutor_id,
                ]);
            }
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

        try {
            Mail::to($user->email)->send(new \App\Mail\NotifyBookedClassMail([
                'user_name' => $user->name,
                'message' => $message,
                'subject' => $subject,
            ]));
        } catch (\Throwable $e) {
            Log::error('Failed to send booked class email: '.$e->getMessage(), [
                'user_id' => $user->id,
                'subject' => $subject,
            ]);
        }
    }
}
