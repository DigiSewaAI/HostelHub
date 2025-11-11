<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactFormSubmitted extends Mailable
{
    use SerializesModels;

    /**
     * Contact form को डाटा
     */
    public $data;

    /**
     * Create a new message instance.
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = isset($this->data['subject'])
            ? $this->data['subject'] . ' - ' . config('app.name')
            : 'नयाँ सम्पर्क सन्देश - ' . config('app.name');

        return new Envelope(
            to: config('mail.admin_address'),
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact',
            with: [
                'name' => $this->data['name'],
                'email' => $this->data['email'],
                'phone' => $this->data['phone'] ?? 'उपलब्ध छैन',
                'message' => $this->data['message'],
                'subject' => $this->data['subject'] ?? 'General Inquiry'
            ]
        );
    }

    /**
     * Get the attachments for the message.
     */
    public function attachments(): array
    {
        return [];
    }
}
