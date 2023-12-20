<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShowClientRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $user_email;

    public $name;
    public $email;
    public $phone;
    public $date_registered;

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
    public function __construct($user_email, $name, $email, $phone, $date_registered, $app_name, $logo_sys, $about_us, $app_address, $app_email, $app_phone)
    {
        $this->user_email = $user_email;
        $this->name = $name;
        $this->email = $email;
        $this->phone = $phone;
        $this->date_registered = $date_registered;

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
        return $this->markdown('emails.new-client')->from($this->app_email, $this->app_name)->subject('New Client Registered');
    }
}
