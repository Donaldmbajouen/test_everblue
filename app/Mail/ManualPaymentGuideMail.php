<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ManualPaymentGuideMail extends Mailable
{
    use Queueable, SerializesModels;

    public $input, $email;
    /**
     * Create a new message instance.
     */
    public function __construct($input, $email)
    {
        $this->input = $input;
        $this->email = $email;
    }

    public function build(): static
    {
        $subject = __('messages.mail.manual_payment_request');

        return $this->subject($subject)
            ->markdown('emails.manual_payment_guide')
            ->with($this->input);
    }
}
