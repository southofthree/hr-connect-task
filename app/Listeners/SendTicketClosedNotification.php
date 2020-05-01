<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\SimpleMail;
use Mail;
use App\User;

class SendTicketClosedNotification
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
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $ticket = $event->ticket;

        if (!$ticket->manager_id) return;

        dispatch(function() use($ticket) {
            $manager = User::whereId($ticket->manager_id)->first();

            $subject = 'Заявка закрыта';
    
            $text = 'Заявка «' . $ticket->subject . '» была закрыта.';
    
            Mail::to($manager->email)->queue(new SimpleMail($subject, $text));
        });
    }
}
