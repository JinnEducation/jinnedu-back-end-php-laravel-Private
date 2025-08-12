<?php

namespace App\Listeners;

use App\Events\NotifyBookedClassEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\NotifyBookedClassMail;
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
        $user = $event->conference->student;
        $message = $event->message;
        sendFCMNotification($conference->title, $message, $user->fcm);
        Mail::to($user->email)->send(new NotifyBookedClassMail(['user_name'=>$user->name,'message'=>$message]));
    }
}
