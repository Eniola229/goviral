<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Wallet extends Model
{
    use HasFactory, HasUuid;

    protected $table = 'wallet';

    protected $fillable = [
        'user_id', 
        'balance_before',
        'amount',
        'balance_after',
        'type', 
        'description', 
        'reference',
        'payment_method',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}