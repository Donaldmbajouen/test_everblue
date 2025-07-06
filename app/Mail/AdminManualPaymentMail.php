<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminManualPaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $input;
    public $email;
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

        $mail = $this->subject($subject)
            ->markdown('emails.manual_payment_request_mail')
            ->with($this->input);

        if ($this->input['attachment']) {
            $mail->attach($this->input['attachment']);
        }

        return $mail;
    }
}
