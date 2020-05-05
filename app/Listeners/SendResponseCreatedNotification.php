<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Mail\SimpleMail;
use Mail;
use App\User;
use Illuminate\Support\Facades\URL;

class SendResponseCreatedNotification
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
            $subject = 'Новое сообщение по заявке';

            if ($attachment) {
                $attachment = [
                    'disk' => 'public',
                    'path' => $attachment->filename
                ];
            } else {
                $attachment = null;
            }

            if ($message->is_from_manager) {
                $client = User::whereId($ticket->client_id)->first();

                $to = $client->email;

                $text = 'Новое сообщение от службы поддержки по заявке «' . $ticket->subject . '»:'
                        . '<br><br>'
                        . $message->text;
            } else {
                $manager = User::whereId($ticket->manager_id)->first();

                $to = $manager->email;

                $link = URL::signedRoute('check', [
                    'user' => $manager->id,
                    'ticket' => $ticket->id
                ]);

                $text = 'Пользователь оставил новое сообщение по заявке «' . $ticket->subject . '»:'
                        . '<br><br>'
                        . $message->text
                        . "<br><br><a href=\"$link\">Перейти к заявке</a>";
            }

            Mail::to($to)->queue(new SimpleMail($subject, $text, $attachment));
        });
    }
}
