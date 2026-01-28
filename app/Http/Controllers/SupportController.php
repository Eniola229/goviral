<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketMessage;
use App\Notifications\NewTicketReply;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index()
    {
        $tickets = auth()->user()->tickets()->latest()->get();
        return view('support.index', compact('tickets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // 1. Create Ticket
        $ticket = auth()->user()->tickets()->create([
            'subject' => $request->subject,
            'status' => 'open',
        ]);

        // 2. Create Initial Message (NO need to pass ticket_id, it's automatic now)
        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'is_admin' => false,
            'message' => $request->message,
        ]);

        return redirect()->route('support.show', $ticket->id)->with('alert', [
            'type' => 'success',
            'message' => 'Ticket created successfully.'
        ]);
    }

    // Show Ticket Conversation
    public function show($id)
    {
        $ticket = SupportTicket::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        return view('support.show', compact('ticket'));
    }

    // Reply to Ticket
    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket = SupportTicket::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        // User Reply (NO need to pass ticket_id)
        $ticket->messages()->create([
            'user_id' => auth()->id(),
            'is_admin' => false,
            'message' => $request->message,
        ]);

        // Update Status to Open (if it was closed)
        $ticket->update(['status' => 'open']);

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'message' => 'Reply sent successfully.'
        ]);
    }
}