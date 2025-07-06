<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyQuizOwnerOnCompletion extends Mailable
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
        $subject = $this->input['participant_name'] . ' ' . __('messages.mail.just_completed_quiz') . ' : "' . $this->input['quiz_title'] . '" !!!';

        $mail = $this->subject($subject)
            ->markdown('emails.quiz_owner_completion_notification')
            ->with(['input' => $this->input]);

        return $mail;
    }
}
