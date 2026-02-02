<?php

namespace App\Observers;

use App\Models\User;
use App\Services\ReferralService;

class UserObserver
{
    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        // Automatically create referral account for new user
        ReferralService::createReferralAccount($user);
    }
}