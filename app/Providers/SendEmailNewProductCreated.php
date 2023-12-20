<?php

namespace App\Providers;
use App\Mail\ShowProductCreated;
use App\Providers\ProductCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailNewProductCreated
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
     * @param  \App\Providers\ProductCreated  $event
     * @return void
     */
    public function handle(ProductCreated $event)
    {
        Mail::to($event->email)->queue( 
            new ShowProductCreated($event->name, $event->email, $event->product_name, $event->product_description, $event->product_link, $event->app_name, $event->logo_sys, $event->about_us, $event->app_address, $event->app_email, $event->app_phone)
        );
    }
}
