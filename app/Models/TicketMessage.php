<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class TicketMessage extends Model
{
    use HasUuids;

    protected $fillable = [
        'ticket_id',
        'user_id',
        'admin_id',
        'is_admin',
        'message',
    ];

    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    // Get the sender (either user or admin)
    public function sender()
    {
        return $this->is_admin ? $this->admin : $this->user;
    }
}