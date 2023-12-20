<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShowOrderUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $client_name;
    public $client_email;
    public $order_id;
    public $service_name;
    public $date_updated;
    public $status;
    public $comment;

    public $app_name;
    public $logo_sys;
    public $about_us;
    public $app_address;
    public $app_email;
    public $app_phone;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($client_name, $client_email, $order_id, $service_name, $date_updated, $status, $comment, $app_name, $logo_sys, $about_us, $app_address, $app_email, $app_phone)
    {
        //
        $this->client_name = $client_name;
        $this->client_email = $client_email;
        $this->order_id = $order_id;
        $this->service_name = $service_name;
        $this->date_updated = $date_updated;
        $this->status = $status;
        $this->comment = $comment;

        $this->app_name = $app_name;
        $this->logo_sys = $logo_sys;
        $this->about_us = $about_us;
        $this->app_address = $app_address;
        $this->app_email = $app_email;
        $this->app_phone = $app_phone;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.order-updated')->from($this->app_email, $this->app_name)->subject('Service Order Updated');
    }
}
