<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\Middleware\ThrottlesExceptions; // Import this

class NewsletterEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $subjectLine;
    public $bodyContent;

    /**
     * Create a new message instance.
     */
    public function __construct($subjectLine, $bodyContent)
    {
        $this->subjectLine = $subjectLine;
        $this->bodyContent = $bodyContent;
    }

    public function middleware(): array
    {
        // If an error happens, allow 3 attempts, but wait 5 seconds between them.
        // This is perfect for staying under Mailtrap's limit.
        return [new ThrottlesExceptions(3, 10)];
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject($this->subjectLine)
                    ->view('emails.newsletter')
                    ->with([
                        'bodyContent' => $this->bodyContent,
                    ]);
    }
}
