<?php

namespace App\Listeners;

use DateTime;
use DateTimeZone;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogLastLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        //
        $user = $event->user;
        $user->last_login = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
        $user->save();
    }
}
