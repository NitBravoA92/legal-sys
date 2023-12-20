<?php

namespace App\Providers;

use App\Providers\UserActive;
use App\Mail\ShowUserActive;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailUserActive
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
     * @param  \App\Providers\UserActive  $event
     * @return void
     */
    public function handle(UserActive $event)
    {
        //
        Mail::to($event->email)->queue( 
            new ShowUserActive($event->name, $event->email, $event->app_name, $event->logo_sys, $event->about_us, $event->app_address, $event->app_email, $event->app_phone)
        );
    }
}
