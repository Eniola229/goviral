<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SupportTicket;
use App\Models\TicketMessage;
use App\Traits\LogsAdminActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\TicketReplyNotification;

class SupportController extends Controller
{
    use LogsAdminActivity;

    /**
     * Display a listing of all support tickets
     */
    public function index(Request $request)
    {
        // Check permission
        if (!Auth::guard('admin')->user()->canViewSupport()) {
            abort(403, 'Unauthorized access');
        }

        $status = $request->get('status', 'all');

        $query = SupportTicket::with(['user', 'latestMessage']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $tickets = $query->latest()->paginate(15);

        $stats = [
            'open' => SupportTicket::open()->count(),
            'in_progress' => SupportTicket::inProgress()->count(),
            'closed' => SupportTicket::closed()->count(),
            'total' => SupportTicket::count(),
        ];

        // Log viewing the support tickets list
        $this->logActivity(
            'viewed',
            'Viewed support tickets list (Status: ' . $status . ')',
            'support_tickets',
            null
        );

        return view('admin.support.index', compact('tickets', 'stats', 'status'));
    }

    /**
     * Display the specified ticket with messages (chat view)
     */
    public function show($id)
    {
        // Check permission
        if (!Auth::guard('admin')->user()->canViewSupport()) {
            abort(403, 'Unauthorized access');
        }

        $ticket = SupportTicket::with(['user'])->findOrFail($id);
        $messages = $ticket->messages()->with('user')->oldest()->get();

        // Log viewing specific ticket
        $this->logViewed(
            'SupportTicket',
            $ticket->id,
            'Viewed support ticket #' . $ticket->id . ': ' . $ticket->subject
        );

        return view('admin.support.show', compact('ticket', 'messages'));
    }

    /**
     * Send a reply to the ticket
     */
    public function reply(Request $request, $id)
    {
        // Check permission
        if (!Auth::guard('admin')->user()->canManageSupport()) {
            abort(403, 'Unauthorized access');
        }

        $ticket = SupportTicket::findOrFail($id);

        // Don't allow replies to closed tickets
        if ($ticket->isClosed()) {
            return redirect()->back()->with('alert', [
                'type' => 'error',
                'message' => 'Cannot reply to a closed ticket. Please reopen it first.'
            ]);
        }

        $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $message = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => null,
            'admin_id' => Auth::guard('admin')->id(), // Add admin_id
            'is_admin' => true,
            'message' => $request->message,
        ]);
        $oldStatus = $ticket->status;
        
        // Update ticket status to in_progress if it was open
        if ($ticket->status === 'open') {
            $ticket->update(['status' => 'in_progress']);
        }

        // Log the reply
        $this->logActivity(
            'replied',
            'Replied to support ticket #' . $ticket->id . ': ' . $ticket->subject,
            'SupportTicket',
            $ticket->id,
            [
                'message_preview' => substr($request->message, 0, 100),
                'old_status' => $oldStatus,
                'new_status' => $ticket->status,
            ]
        );

        // Send email notification to the user
        try {
            Mail::to($ticket->user->email)->send(new TicketReplyNotification($ticket, $message));
            
            Log::info('Email sent to user for ticket reply', [
                'ticket_id' => $ticket->id,
                'user_email' => $ticket->user->email,
                'admin_id' => Auth::guard('admin')->id()
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send ticket reply email', [
                'ticket_id' => $ticket->id,
                'error' => $e->getMessage()
            ]);
        }

        Log::info('Admin replied to ticket', [
            'ticket_id' => $ticket->id,
            'admin_id' => Auth::guard('admin')->id()
        ]);

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'message' => 'Your reply has been sent to the customer.'
        ]);
    }

    /**
     * Update ticket status
     */
    public function updateStatus(Request $request, $id)
    {
        // Check permission
        if (!Auth::guard('admin')->user()->canManageSupport()) {
            abort(403, 'Unauthorized access');
        }

        $ticket = SupportTicket::findOrFail($id);

        $request->validate([
            'status' => 'required|in:open,in_progress,closed',
        ]);

        $oldStatus = $ticket->status;
        $ticket->update(['status' => $request->status]);

        // Log status update
        $this->logUpdated(
            'SupportTicket',
            $ticket->id,
            'Updated ticket #' . $ticket->id . ' status from ' . $oldStatus . ' to ' . $request->status,
            [
                'old_status' => $oldStatus,
                'new_status' => $request->status,
            ]
        );

        Log::info('Ticket status updated', [
            'ticket_id' => $ticket->id,
            'old_status' => $oldStatus,
            'new_status' => $request->status,
            'admin_id' => Auth::guard('admin')->id()
        ]);

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'message' => 'Ticket status updated to ' . ucfirst(str_replace('_', ' ', $request->status))
        ]);
    }

    /**
     * Close a ticket
     */
    public function close($id)
    {
        // Check permission
        if (!Auth::guard('admin')->user()->canManageSupport()) {
            abort(403, 'Unauthorized access');
        }

        $ticket = SupportTicket::findOrFail($id);
        $oldStatus = $ticket->status;
        $ticket->update(['status' => 'closed']);

        // Log closing ticket
        $this->logUpdated(
            'SupportTicket',
            $ticket->id,
            'Closed support ticket #' . $ticket->id . ': ' . $ticket->subject,
            [
                'old_status' => $oldStatus,
                'new_status' => 'closed',
            ]
        );

        Log::info('Ticket closed', [
            'ticket_id' => $ticket->id,
            'admin_id' => Auth::guard('admin')->id()
        ]);

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'message' => 'Ticket has been closed successfully.'
        ]);
    }

    /**
     * Reopen a ticket
     */
    public function reopen($id)
    {
        // Check permission
        if (!Auth::guard('admin')->user()->canManageSupport()) {
            abort(403, 'Unauthorized access');
        }

        $ticket = SupportTicket::findOrFail($id);
        $oldStatus = $ticket->status;
        $ticket->update(['status' => 'open']);

        // Log reopening ticket
        $this->logUpdated(
            'SupportTicket',
            $ticket->id,
            'Reopened support ticket #' . $ticket->id . ': ' . $ticket->subject,
            [
                'old_status' => $oldStatus,
                'new_status' => 'open',
            ]
        );

        Log::info('Ticket reopened', [
            'ticket_id' => $ticket->id,
            'admin_id' => Auth::guard('admin')->id()
        ]);

        return redirect()->back()->with('alert', [
            'type' => 'success',
            'message' => 'Ticket has been reopened.'
        ]);
    }

    /**
     * Delete a ticket (Super Admin only)
     */
    public function destroy($id)
    {
        // Check permission
        if (!Auth::guard('admin')->user()->isSuperAdmin()) {
            abort(403, 'Only Super Admin can delete tickets');
        }

        $ticket = SupportTicket::findOrFail($id);
        
        // Log deletion before deleting
        $this->logDeleted(
            'SupportTicket',
            $ticket->id,
            'Deleted support ticket #' . $ticket->id . ': ' . $ticket->subject
        );

        Log::warning('Ticket deleted', [
            'ticket_id' => $ticket->id,
            'subject' => $ticket->subject,
            'admin_id' => Auth::guard('admin')->id()
        ]);

        $ticket->delete();

        return redirect()->route('admin.support.index')->with('alert', [
            'type' => 'success',
            'message' => 'Ticket has been deleted.'
        ]);
    }

    /**
 * Fetch messages via AJAX
 */
    public function fetchMessages($id)
    {
        if (!Auth::guard('admin')->user()->canViewSupport()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $ticket = SupportTicket::findOrFail($id);
        $messages = $ticket->messages()->with(['user', 'admin'])->orderBy('created_at', 'asc')->get();

        $html = view('admin.support.partials.messages', compact('messages'))->render();

        return response()->json([
            'success' => true,
            'html' => $html,
            'messageCount' => $messages->count(),
            'lastMessageId' => $messages->last()->id ?? null,
        ]);
    }
}