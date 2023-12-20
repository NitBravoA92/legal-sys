<?php

namespace App\Providers;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderMessageSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_to_email;
    public $user_from_name;
    public $message_title;
    public $message_content;
    public $message_created_at;

    public $order_id;
    public $service_name;

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
    public function __construct($user_to_email, $user_from_name, $message_title, $message_content, $message_created_at, $order_id, $service_name, $app_name, $logo_sys, $about_us, $app_address, $app_email, $app_phone)
    {
        $this->user_to_email = $user_to_email;
        $this->user_from_name = $user_from_name;
        $this->message_title = $message_title;
        $this->message_content = $message_content;
        $this->message_created_at = $message_created_at;

        $this->order_id = $order_id;
        $this->service_name = $service_name;

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
