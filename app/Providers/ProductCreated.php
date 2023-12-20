<?php

namespace App\Providers;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ProductCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

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
     * Create a new event instance.
     *
     * @return void
     */

    public function __construct($name, $email, $product_name, $product_description, $product_link, $app_name, $logo_sys, $about_us, $app_address, $app_email, $app_phone)
    {
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
     * Get the channels the event should broadcast on.
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
