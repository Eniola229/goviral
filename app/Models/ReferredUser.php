<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class ReferredUser extends Model
{
    use HasFactory, HasUuid;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'referrer_id',
        'referred_user_id',
        'has_deposited',
        'has_ordered',
        'bonus_paid',
        'bonus_paid_at',
    ];

    protected $casts = [
        'has_deposited' => 'boolean',
        'has_ordered' => 'boolean',
        'bonus_paid' => 'boolean',
        'bonus_paid_at' => 'datetime',
    ];

    /**
     * Get the referrer (the person who referred)
     */
    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Get the referred user (the person who was referred)
     */
    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id');
    }
}