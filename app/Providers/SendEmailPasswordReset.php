<?php

namespace App\Providers;

use App\Providers\UserPasswordReset;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailPasswordReset
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
     * @param  \App\Providers\UserPasswordReset  $event
     * @return void
     */
    public function handle(UserPasswordReset $event)
    {
        //
    }
}
