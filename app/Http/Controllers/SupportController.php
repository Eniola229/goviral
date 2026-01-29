<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use App\Models\TicketMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupportController extends Controller
{
    /**
     * Display a listing of user's support tickets
     */
    public function index()
    {
        $tickets = SupportTicket::where('user_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('support.index', compact('tickets'));
    }

    /**
     * Show a specific ticket
     */
    public function show($id)
    {
        $ticket = SupportTicket::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();
        
        // Get messages ordered oldest to newest
        $messages = $ticket->messages()
            ->with(['user', 'admin'])
            ->orderBy('created_at', 'asc')
            ->get();
        
        return view('support.show', compact('ticket', 'messages'));
    }

    /**
     * Send a reply to the ticket
     */
    public function reply(Request $request, $id)
    {
        $ticket = SupportTicket::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        // Don't allow replies to closed tickets
        if ($ticket->status === 'closed') {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'This ticket is closed. Please contact admin to reopen it.'
                ], 400);
            }
            
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'message' => 'This ticket is closed. Please contact admin to reopen it.'
            ]);
        }

        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $message = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'admin_id' => null,
            'is_admin' => false,
            'message' => $request->message,
        ]);

        // Update ticket timestamp
        $ticket->touch();

        // Check if AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Your reply has been sent!'
            ]);
        }

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'message' => 'Your reply has been sent!'
        ]);
    }

    /**
     * Fetch messages via AJAX
     */
    public function fetchMessages($id)
    {
        $ticket = SupportTicket::where('id', $id)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        $messages = $ticket->messages()
            ->with(['user', 'admin'])
            ->orderBy('created_at', 'asc')
            ->get();

        $html = view('support.partials.messages', compact('messages'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'messageCount' => $messages->count(),
            'lastMessageId' => $messages->last()->id ?? null,
        ]);
    }

    /**
     * Create a new support ticket
     */
    public function create()
    {
        return view('support.create');
    }

    /**
     * Store a new support ticket
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:5000',
        ]);

        $ticket = SupportTicket::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'status' => 'open',
        ]);

        // Create the first message
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'admin_id' => null,
            'is_admin' => false,
            'message' => $request->message,
        ]);

        return redirect()->route('support.show', $ticket->id)->with('alert', [
            'type' => 'success',
            'message' => 'Your support ticket has been created successfully!'
        ]);
    }
}