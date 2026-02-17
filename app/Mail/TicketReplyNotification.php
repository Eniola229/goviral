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
    public $ticketMessage;

    public function __construct(SupportTicket $ticket, TicketMessage $message)
    {
        $this->ticket = $ticket;
        $this->ticketMessage = $message; 
    }

    public function build()
    {
        return $this->subject('New Reply to Your Support Ticket #' . $this->ticket->id)
                    ->view('emails.ticket-reply');
    }
}