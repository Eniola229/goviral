<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logged extends Model
{
    use HasFactory;

    protected $table = 'logged';

    protected $fillable = [
        'user_id',
        'reference',
        'type',
        'method',
        'amount',
        'status',
        'description',
        'request_data',
        'response_data',
        'error_message',
        'ip_address',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'request_data' => 'array',
        'response_data' => 'array',
    ];

    /**
     * Get the user that owns the log
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}