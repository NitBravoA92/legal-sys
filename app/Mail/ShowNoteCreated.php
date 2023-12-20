<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ShowNoteCreated extends Mailable
{
    use Queueable, SerializesModels;

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
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user_email, $note_created_by, $note_client, $note_created_at, $title, $description, $app_name, $logo_sys, $about_us, $app_address, $app_email, $app_phone)
    {
        //
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
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        return $this->markdown('emails.new-note')->from($this->app_email, $this->app_name)->subject('New Management Note');
    }
}
