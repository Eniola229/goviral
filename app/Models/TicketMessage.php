<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class TicketMessage extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'ticket_id', 'user_id', 'is_admin', 'message'
    ];

    // Explicitly define the foreign key column name
    public function ticket()
    {
        return $this->belongsTo(SupportTicket::class, 'ticket_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}