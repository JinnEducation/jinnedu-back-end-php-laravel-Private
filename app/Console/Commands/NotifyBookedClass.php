<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Conference;
use App\Events\NotifyBookedClassEvent;
use DateTime;

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
        $currentDateTime = new DateTime();

        $conferences = Conference::whereIn('ref_type', [1,2,3,4])
            ->where(function ($query) use ($currentDateTime) {
                $query->whereRaw('TIMESTAMPDIFF(HOUR, ?, CONCAT(date, " ", start_time)) = 1', [$currentDateTime->format("Y-m-d h:iA")]);
            })
            ->get();

        foreach ($conferences as $conference) {
            $message = "You have class after an hour";
            event(new NotifyBookedClassEvent($conference, $message));
        }
    }
}
