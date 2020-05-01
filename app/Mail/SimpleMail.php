<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SimpleMail extends Mailable
{
    use Queueable, SerializesModels;

    public $text;
    public $attachment;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $subject, string $text, ?array $attachment = null)
    {
        $this->subject = $subject;
        $this->text = $text;
        $this->attachment = $attachment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $mail = $this->subject($this->subject)->view('emails.simple');

        if ($this->attachment) {
            $mail = $mail->attachFromStorageDisk($this->attachment['disk'], $this->attachment['path']);
        }

        return $mail;
    }
}
