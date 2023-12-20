<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShowProductCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $name;
    public $email;
    public $product_name;
    public $product_description;
    public $product_link;

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

    public function __construct($name, $email, $product_name, $product_description, $product_link, $app_name, $logo_sys, $about_us, $app_address, $app_email, $app_phone)
    {
        //
        $this->name = $name;
        $this->email = $email;
        $this->product_name = $product_name;
        $this->product_description = $product_description;
        $this->product_link = $product_link;

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
        return $this->markdown('emails.new-product')->from($this->app_email, $this->app_name)->subject('New service offered');
    }
}
