<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifyParticipantOfQuizCompletion extends Mailable
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
        $subject = __('messages.quiz_report.completed') . ' !! - ' . __('messages.mail.you_just_successfully_completed_the_quiz') . ':"' . $this->input['quiz_title'] . '"';

        $mail = $this->subject($subject)
            ->markdown('emails.quiz_completion_participant_notification')
            ->with(['input' => $this->input]);

        return $mail;
    }
}
