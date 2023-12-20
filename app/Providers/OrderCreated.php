<?php

namespace App\Providers;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    
    public $user_name;
    public $user_email;
    
    public $client_name;
    public $client_email;
    public $order_id;
    public $service_name;
    public $date_created;

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
    public function __construct($user_name, $user_email, $client_name, $order_id, $service_name, $date_created, $app_name, $logo_sys, $about_us, $app_address, $app_email, $app_phone)
    {
        //
        $this->user_name = $user_name;
        $this->user_email = $user_email;

         $this->client_name = $client_name;
         $this->order_id = $order_id;
         $this->service_name = $service_name;
         $this->date_created = $date_created;

         $this->app_name = $app_name;
         $this->logo_sys = $logo_sys;
         $this->about_us = $about_us;
         $this->app_address = $app_address;
         $this->app_email = $app_email;
         $this->app_phone = $app_phone;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
