<?php
namespace App\Notifications\Auth;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class QueuedVerifyEmail extends VerifyEmail implements ShouldQueue
{
    use Queueable;
    
    public function __construct()
    {
        //uncomment to override the queue
        //$this->queue = 'verify';

        //uncomment to override the connection
        //$this->connection = 'verify';
    }
}