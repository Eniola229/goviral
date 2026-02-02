<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class ReferralWalletTransaction extends Model
{
    use HasFactory, HasUuid;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'referral_id',
        'balance_before',
        'amount',
        'balance_after',
        'type',
        'description',
        'reference',
        'withdrawal_method',
        'bank_name',
        'account_number',
        'account_name',
        'status',
        'approved_by',
        'approved_at',
        'admin_note',
    ];

    protected $casts = [
        'balance_before' => 'decimal:2',
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the referral this transaction belongs to
     */
    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }

    /**
     * Get the admin who approved/declined
     */
    public function approvedBy()
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }
}