<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class SupportTicket extends Model
{
    use HasFactory, HasUuid;

    protected $fillable = [
        'user_id', 'subject', 'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function messages()
    {
        return $this->hasMany(TicketMessage::class, 'ticket_id')->oldest();
    }
}