<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */

    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class
        ],
        ClientRegistered::class => [
            SendEmailNewClientRegistered::class
        ],
        WorkerRegistered::class => [
            SendEmailNewWorkerRegistered::class
        ],
        ProductCreated::class => [
            SendEmailNewProductCreated::class
        ],
        OrderCreated::class => [
            SendEmailNewOrderCreated::class
        ],
        OrderUpdated::class => [
            SendEmailOrderUpdated::class
        ],
        UserBlock::class => [
            SendEmailUserBlocked::class
        ],
        UserActive::class => [
            SendEmailUserActive::class
        ],
        NoteCreated::class => [
            SendEmailNoteCreated::class
        ],
        OrderMessageSent::class => [
            SendEmailOrderMessageSent::class
        ],
        UserWelcome::class => [
            SendEmailUserWelcome::class
        ],
        UserPasswordReset::class => [
            SendEmailPasswordReset::class
        ],
        OrderToValidate::class => [
            SendEmailOrderToValidate::class
        ],
        'Illuminate\Auth\Events\Verified' => [
            'App\Listeners\LogVerifiedUser',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
