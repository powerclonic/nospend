<?php

namespace App\Mail;

use App\Models\Expense;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ExpensePaid extends Mailable
{
    use Queueable, SerializesModels;

    public string $expenseUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Expense $expense
    ) {
        $this->expenseUrl = config("app.frontend_url") . "/expenses?expense=" . $this->expense->id; 
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('app.expense.paid', ['name' => $this->expense->name]),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'mail.expenses.paid',
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
