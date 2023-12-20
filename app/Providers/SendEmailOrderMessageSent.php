<?php

namespace App\Providers;

use App\Providers\OrderMessageSent;
use App\Mail\ShowNewOrderMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailOrderMessageSent
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
     * @param  \App\Providers\OrderMessageSent  $event
     * @return void
     */

    public function handle(OrderMessageSent $event)
    {
         //
         Mail::to($event->user_to_email)->queue( 
            new ShowNewOrderMessage($event->user_to_email, $event->user_from_name, $event->message_title, $event->message_content, $event->message_created_at, $event->order_id, $event->service_name, $event->app_name, $event->logo_sys, $event->about_us, $event->app_address, $event->app_email, $event->app_phone)
        );
    }
}
