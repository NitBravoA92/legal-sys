<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShowWelcomeUser extends Mailable
{
    use Queueable, SerializesModels;

    public $user_name;
    public $user_email;

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
    public function __construct($user_name, $user_email, $app_name, $logo_sys, $about_us, $app_address, $app_email, $app_phone)
    {
        //
        $this->user_name = $user_name;
        $this->user_email = $user_email;
        
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
        return $this->markdown('emails.new-welcome')->from($this->app_email, $this->app_name)->subject('Welcome to ' . $this->app_name);
    }
}
