<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminRegist extends Mailable
{
    use Queueable, SerializesModels;

    protected $name;
    protected $mail;
    protected $pass;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $mail, $pass)
    {
        $this->name     = $name;
        $this->mail     = $mail;
        $this->pass     = $pass;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '【檀家管理】システム管理者登録のお知らせ',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'admin.emails.admin',
            with: [
                'name' => $this->name,
                'mail' => $this->mail,
                'pass' => $this->pass,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
