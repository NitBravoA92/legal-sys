<?php

namespace App\Providers;

use App\Mail\ShowUserBlocked;
use App\Providers\UserBlock;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailUserBlocked
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
     * @param  \App\Providers\UserBlock  $event
     * @return void
     */
    public function handle(UserBlock $event)
    {
        //
        Mail::to($event->email)->queue( 
            new ShowUserBlocked($event->name, $event->email, $event->app_name, $event->logo_sys, $event->about_us, $event->app_address, $event->app_email, $event->app_phone)
        );
        
    }
}
