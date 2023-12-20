<?php

namespace App\Providers;

use App\Mail\ShowNoteCreated;
use App\Providers\NoteCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailNoteCreated
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
     * @param  \App\Providers\NoteCreated  $event
     * @return void
     */
    public function handle(NoteCreated $event)
    {
        Mail::to($event->user_email)->queue( 
            new ShowNoteCreated($event->user_email, $event->note_created_by, $event->note_client, $event->note_created_at, $event->title, $event->description, $event->app_name, $event->logo_sys, $event->about_us, $event->app_address, $event->app_email, $event->app_phone)
        );
    }
}
