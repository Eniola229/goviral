<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUuid;

class Referral extends Model
{
    use HasFactory, HasUuid;

    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'user_id',
        'referral_code',
        'referral_balance',
    ];

    protected $casts = [
        'referral_balance' => 'decimal:2',
    ];

    /**
     * Get the user (referrer) who owns this referral
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get all users referred by this referrer
     */
    public function referredUsers()
    {
        return $this->hasMany(ReferredUser::class, 'referrer_id', 'user_id');
    }

    /**
     * Get all wallet transactions for this referral
     */
    public function transactions()
    {
        return $this->hasMany(ReferralWalletTransaction::class);
    }

    /**
     * Generate unique referral code
     */
    public static function generateUniqueCode()
    {
        do {
            $code = 'REF-' . strtoupper(\Str::random(8));
        } while (self::where('referral_code', $code)->exists());

        return $code;
    }
}