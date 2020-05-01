<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use App\Events\TicketCreated;
use App\Listeners\SendTicketCreatedNotification;
use App\Events\TicketClosed;
use App\Listeners\SendTicketClosedNotification;
use App\Events\ResponseCreated;
use App\Listeners\SendResponseCreatedNotification;
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
            SendEmailVerificationNotification::class,
        ],
        TicketCreated::class => [
            SendTicketCreatedNotification::class
        ],
        TicketClosed::class => [
            SendTicketClosedNotification::class
        ],
        ResponseCreated::class => [
            SendResponseCreatedNotification::class
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
