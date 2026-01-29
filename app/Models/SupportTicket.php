<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class SupportTicket extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'user_id',
        'subject',
        'message',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the ticket
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all messages for this ticket
     */
    public function messages()
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id');
    }

    /**
     * Get the latest message
     */
    public function latestMessage()
    {
        return $this->hasOne(TicketMessage::class, 'ticket_id')->latestOfMany();
    }

    /**
     * Scope for open tickets
     */
    public function scopeOpen($query)
    {
        return $query->where('status', 'open');
    }

    /**
     * Scope for in progress tickets
     */
    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    /**
     * Scope for closed tickets
     */
    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }

    /**
     * Check if ticket is open
     */
    public function isOpen()
    {
        return $this->status === 'open';
    }

    /**
     * Check if ticket is closed
     */
    public function isClosed()
    {
        return $this->status === 'closed';
    }

    /**
     * Get status badge color
     */
    public function getStatusBadgeClass()
    {
        return match($this->status) {
            'open' => 'bg-success',
            'in_progress' => 'bg-warning',
            'closed' => 'bg-secondary',
            default => 'bg-info'
        };
    }
}