<?php

namespace App\Providers;

use App\Providers\OrderUpdated;
use App\Mail\ShowOrderUpdated;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailOrderUpdated
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
     * @param  \App\Providers\OrderUpdated  $event
     * @return void
     */
    public function handle(OrderUpdated $event)
    {
        //
        Mail::to($event->client_email)->queue( 
            new ShowOrderUpdated($event->client_name, $event->client_email, $event->order_id, $event->service_name, $event->date_updated, $event->status, $event->comment, $event->app_name, $event->logo_sys, $event->about_us, $event->app_address, $event->app_email, $event->app_phone)
        );
    }
}


