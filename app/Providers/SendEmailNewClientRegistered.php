<?php

namespace App\Providers;

use App\Mail\ShowClientRegistered;
use App\Providers\ClientRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailNewClientRegistered
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
     * @param  \App\Providers\ClientRegistered  $event
     * @return void
     */
    public function handle(ClientRegistered $event)
    {
        //
        Mail::to($event->user_email)->queue( 
            new ShowClientRegistered($event->user_email, $event->name, $event->email, $event->phone, $event->date_registered, $event->app_name, $event->logo_sys, $event->about_us, $event->app_address, $event->app_email, $event->app_phone)
        );
    }
}
