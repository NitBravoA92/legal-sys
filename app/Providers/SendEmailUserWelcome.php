<?php

namespace App\Providers;

use App\Providers\UserWelcome;
use App\Mail\ShowWelcomeUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailUserWelcome
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
     * @param  \App\Providers\UserWelcome  $event
     * @return void
     */
    public function handle(UserWelcome $event)
    {
        //
         Mail::to($event->user_email)->queue( 
            new ShowWelcomeUser($event->user_name, $event->user_email, $event->app_name, $event->logo_sys, $event->about_us, $event->app_address, $event->app_email, $event->app_phone)
        );
    }
}
