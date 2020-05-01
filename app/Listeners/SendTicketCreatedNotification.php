<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\SimpleMail;
use Mail;
use App\User;

class SendTicketCreatedNotification
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
        $message = $event->message;
        $attachment = $event->attachment;

        dispatch(function() use($ticket, $message, $attachment) {
            $subject = 'Новая заявка';

            $text = 'Тема: ' . $ticket->subject
                    . '<br><br>'
                    . 'Сообщение:'
                    . '<br><br>'
                    . $message->text;

            if ($attachment) {
                $attachment = [
                    'disk' => 'public',
                    'path' => $attachment->filename
                ];
            } else {
                $attachment = null;
            }

            User::managers()->chunk(1000, function($managers) use($subject, $text, $attachment) {
                foreach ($managers as $manager) {
                    $to = $manager->email;
    
                    Mail::to($to)->queue(new SimpleMail($subject, $text, $attachment));
                }
            });
        });
    }
}
