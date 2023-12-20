<?php

namespace App\Providers;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NoteCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user_email;

    public $note_created_by;
    public $note_client;
    public $note_created_at;

    public $title;
    public $description;

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

    public function __construct($user_email, $note_created_by, $note_client, $note_created_at, $title, $description, $app_name, $logo_sys, $about_us, $app_address, $app_email, $app_phone)
    {
        $this->user_email = $user_email;

        $this->note_created_by = $note_created_by;
        $this->note_client = $note_client;
        $this->note_created_at = $note_created_at;

        $this->title = $title;
        $this->description = $description;

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
