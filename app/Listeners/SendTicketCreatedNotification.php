<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\SimpleMail;
use Mail;
use App\User;
use Illuminate\Support\Facades\URL;

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

            User::managers()->chunk(1000, function($managers) use($ticket, $subject, $text, $attachment) {
                foreach ($managers as $manager) {
                    $to = $manager->email;

                    $link = URL::signedRoute('check', [
                        'user' => $manager->id,
                        'ticket' => $ticket->id
                    ]);

                    $_text = $text . "<br><br><a href=\"$link\">Перейти к заявке</a>";
    
                    Mail::to($to)->queue(new SimpleMail($subject, $_text, $attachment));
                }
            });
        });
    }
}
