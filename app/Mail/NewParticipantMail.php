<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewParticipantMail extends Mailable
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
        $subject = $this->input['participant_name'] . ' ' . __('messages.mail.has_just_started_the_quiz') . ': "' . $this->input['quiz_title'] . '"!!!';

        $mail = $this->subject($subject)
            ->markdown('emails.new_participant_mail')
            ->with(['input' => $this->input]);

        return $mail;
    }
}
