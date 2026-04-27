<?php

namespace App\Services;

use App\Mail\NotifyBookedClassMail;
use App\Models\Conference;
use App\Models\GroupClass;
use App\Models\GroupClassStudent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ConferenceScheduleNotificationService
{
    public function notifyGroupClassScheduleChanged(GroupClass $groupClass, array $oldSchedule, array $newSchedule): void
    {
        $users = $this->groupClassRecipients($groupClass);

        if ($users->isEmpty()) {
            return;
        }

        $title = $groupClass->name ?: 'Class Schedule Changed';
        $message = 'The schedule for class "'.$title.'" has been changed.';
        $details = $this->scheduleDetails($oldSchedule, $newSchedule);

        if ($details) {
            $message .= ' '.$details;
        }

        $this->notifyUsers($users, $title, $message, 'Group Class Schedule Changed', [
            'type' => 'conference_schedule_changed',
            'group_class_id' => $groupClass->id,
            'url' => '/dashboard/conferences/student',
            'icon' => 'fa fa-calendar',
            'color' => 'warning',
        ]);
    }

    public function notifyConferenceScheduleChanged(Conference $conference, ?string $oldStartDateTime = null, ?int $excludeUserId = null): void
    {
        $users = $this->conferenceRecipients($conference, $excludeUserId);

        if ($users->isEmpty()) {
            return;
        }

        $title = $conference->title ?: 'Class Schedule Changed';
        $message = 'The schedule for class "'.$title.'" has been changed.';
        $details = $this->scheduleDetails(
            $oldStartDateTime ? [$oldStartDateTime] : [],
            $conference->start_date_time ? [$conference->start_date_time] : []
        );

        if ($details) {
            $message .= ' '.$details;
        }

        $this->notifyUsers($users, $title, $message, 'Class Schedule Changed', [
            'type' => 'conference_schedule_changed',
            'conference_id' => $conference->id,
            'url' => '/dashboard/conferences/student',
            'icon' => 'fa fa-calendar',
            'color' => 'warning',
        ]);
    }

    private function groupClassRecipients(GroupClass $groupClass): Collection
    {
        $studentIds = GroupClassStudent::where('class_id', $groupClass->id)->pluck('student_id');
        $userIds = $studentIds->push($groupClass->tutor_id)->filter()->unique()->values();

        return User::whereIn('id', $userIds)->get();
    }

    private function conferenceRecipients(Conference $conference, ?int $excludeUserId = null): Collection
    {
        if ((int) $conference->ref_type === 1) {
            $studentIds = GroupClassStudent::where('class_id', $conference->ref_id)->pluck('student_id');
            $userIds = $studentIds->push($conference->tutor_id);
        } else {
            $userIds = collect([$conference->student_id, $conference->tutor_id]);
        }

        $userIds = $userIds->filter()->unique();

        if ($excludeUserId) {
            $userIds = $userIds->reject(fn ($userId) => (int) $userId === $excludeUserId);
        }

        return User::whereIn('id', $userIds->values())->get();
    }

    private function notifyUsers(Collection $users, string $title, string $message, string $subject, array $info): void
    {
        foreach ($users as $user) {
            try {
                $userInfo = $info;
                if ((int) $user->type === 2) {
                    $userInfo['url'] = '/dashboard/conferences/tutor';
                }

                sendUserDashboardNotification($user, $title, $message, $userInfo);

                if ($user->email) {
                    Mail::to($user->email)->send(new NotifyBookedClassMail([
                        'user_name' => $user->name ?: $user->full_name,
                        'message' => $message,
                        'subject' => $subject,
                    ]));
                }
            } catch (\Throwable $e) {
                Log::error('Failed to send conference schedule change notification: '.$e->getMessage(), [
                    'user_id' => $user->id,
                    'type' => $info['type'] ?? null,
                    'conference_id' => $info['conference_id'] ?? null,
                    'group_class_id' => $info['group_class_id'] ?? null,
                ]);
            }
        }
    }

    private function scheduleDetails(array $oldSchedule, array $newSchedule): string
    {
        $old = $this->formatSchedule($oldSchedule);
        $new = $this->formatSchedule($newSchedule);

        if ($old && $new) {
            return 'Previous time: '.$old.'. New time: '.$new.'.';
        }

        if ($new) {
            return 'New time: '.$new.'.';
        }

        return '';
    }

    private function formatSchedule(array $schedule): string
    {
        $dates = collect($schedule)
            ->filter()
            ->map(function ($date) {
                return Carbon::parse($date)->format('Y-m-d h:i A');
            })
            ->unique()
            ->values();

        if ($dates->isEmpty()) {
            return '';
        }

        if ($dates->count() > 3) {
            return $dates->take(3)->implode(', ').' and '.($dates->count() - 3).' more sessions';
        }

        return $dates->implode(', ');
    }
}
