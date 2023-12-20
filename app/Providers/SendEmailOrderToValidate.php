<?php

namespace App\Providers;

use App\Providers\OrderToValidate;
use App\Mail\ShowOrderToValidate;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailOrderToValidate
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
     * @param  \App\Providers\OrderToValidate  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        //
        Mail::to($event->user_email)->queue( 
            new ShowOrderToValidate($event->user_name, $event->user_email, $event->client_name, $event->order_id, $event->service_name, $event->date_created, $event->app_name, $event->logo_sys, $event->about_us, $event->app_address, $event->app_email, $event->app_phone)
        );
    }
}
