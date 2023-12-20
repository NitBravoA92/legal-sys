<?php

namespace App\Providers;

use App\Providers\WorkerRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailNewWorkerRegistered
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
     * @param  \App\Providers\WorkerRegistered  $event
     * @return void
     */
    public function handle(WorkerRegistered $event)
    {
        //
    }
}
