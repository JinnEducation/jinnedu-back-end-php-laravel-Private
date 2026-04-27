<?php

namespace App\Listeners;

use App\Events\NotifyBookedClassEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyBookedClassMail;
use App\Models\GroupClassStudent;
use App\Models\User;

class SendNotificationAndEmailListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\NotifyBookedClassEvent  $event
     * @return void
     */
    public function handle(NotifyBookedClassEvent $event)
    {
        $conference = $event->conference;
        $message = $event->message;

        $users = collect();
        if ((int) $conference->ref_type === 1) {
            $studentIds = GroupClassStudent::where('class_id', $conference->ref_id)->pluck('student_id');
            $users = User::whereIn('id', $studentIds)->get();
        } elseif ($conference->student) {
            $users = collect([$conference->student]);
        }

        foreach ($users as $user) {
            sendUserDashboardNotification(
                $user,
                $conference->title,
                $message,
                [
                    'type' => 'conference_reminder',
                    'conference_id' => $conference->id,
                    'url' => '/dashboard/conferences/student',
                    'icon' => 'fa fa-calendar',
                    'color' => 'info',
                ]
            );

            if ($user->email) {
                Mail::to($user->email)->send(new NotifyBookedClassMail([
                    'user_name' => $user->name,
                    'message' => $message,
                    'subject' => 'Upcoming Class Reminder',
                ]));
            }
        }
    }
}
