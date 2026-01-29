<?php

namespace App\Mail;

use App\Models\SupportTicket;
use App\Models\TicketMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TicketReplyNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $ticket;
    public $message;

    /**
     * Create a new message instance.
     */
    public function __construct(SupportTicket $ticket, TicketMessage $message)
    {
        $this->ticket = $ticket;
        $this->message = $message;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('New Reply to Your Support Ticket #' . $this->ticket->id)
                    ->view('emails.ticket-reply');
    }
}