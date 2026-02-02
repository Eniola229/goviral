<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReferralService;
use App\Services\KorapayService;
use App\Models\ReferredUser;
use Illuminate\Support\Facades\Auth;

class ReferralController extends Controller
{
    protected $korapayService;

    public function __construct(KorapayService $korapayService)
    {
        $this->korapayService = $korapayService;
    }

    /**
     * Show referral dashboard
     */
    public function index()
    {
        $user = Auth::user();
        
        // Create referral if doesn't exist
        if (!$user->referral) {
            ReferralService::createReferralAccount($user);
            $user->refresh();
        }

        // Process any pending bonuses
        $bonusAdded = ReferralService::processPendingBonuses($user);

        if ($bonusAdded > 0) {
            session()->flash('alert', [
                'type' => 'success',
                'message' => '₦' . number_format($bonusAdded, 2) . ' referral bonus has been added to your account!'
            ]);
            $user->refresh();
        }

        $referral = $user->referral;

        // Get referred users stats
        $totalReferred = ReferredUser::where('referrer_id', $user->id)->count();
        $depositedCount = ReferredUser::where('referrer_id', $user->id)
            ->where('has_deposited', true)
            ->count();
        $orderedCount = ReferredUser::where('referrer_id', $user->id)
            ->where('has_ordered', true)
            ->count();
        $bonusPaidCount = ReferredUser::where('referrer_id', $user->id)
            ->where('bonus_paid', true)
            ->count();

        // Get referred users list
        $referredUsers = ReferredUser::with('referredUser')
            ->where('referrer_id', $user->id)
            ->latest()
            ->paginate(20);

        // Get recent transactions
        $transactions = $referral->transactions()
            ->latest()
            ->paginate(10);

        return view('referral.index', compact(
            'referral',
            'totalReferred',
            'depositedCount',
            'orderedCount',
            'bonusPaidCount',
            'referredUsers',
            'transactions'
        ));
    }

    /**
     * Show withdrawal page
     */
    public function withdraw()
    {
        $user = Auth::user();
        $referral = $user->referral;

        if (!$referral) {
            return redirect()->route('referral.index');
        }

        // Fetch banks from Korapay
        $banksResponse = $this->korapayService->getBanks();
        $banks = [];
        
        if (isset($banksResponse['status']) && $banksResponse['status'] === true && isset($banksResponse['data'])) {
            $banks = $banksResponse['data'];
        }

        return view('referral.withdraw', compact('referral', 'banks'));
    }


    /**
     * Withdraw to wallet
     */
    public function withdrawToWallet(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
        ]);
        
        // Get user's referral balance
        $referral = Auth::user()->referral; // Assuming the relationship exists
        
        if (!$referral || $referral->referral_balance < $request->amount) {
            return back()->with('alert', [
                'type' => 'error',
                'message' => 'Insufficient referral balance. Available balance: ₦' . number_format($referral->referral_balance ?? 0, 2)
            ]);
        }
        
        try {
            $withdrawal = ReferralService::withdrawToWallet(Auth::user(), $request->amount);
            return redirect()->route('referral.index')->with('alert', [
                'type' => 'success',
                'message' => 'Withdrawal request submitted! Awaiting admin approval. Reference: ' . $withdrawal->reference
            ]);
        } catch (\Exception $e) {
            return back()->with('alert', [
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Process withdrawal to bank
     */
    public function withdrawToBank(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'bank_name' => 'required|string',
            'account_number' => 'required|string|min:10|max:10',
            'account_name' => 'required|string',
        ]);
        
        // Get user's referral balance
        $referral = Auth::user()->referral;
        
        if (!$referral || $referral->referral_balance < $request->amount) {
            return back()->with('alert', [
                'type' => 'error',
                'message' => 'Insufficient referral balance. Available balance: ₦' . number_format($referral->referral_balance ?? 0, 2)
            ]);
        }
        
        try {
            $withdrawal = ReferralService::withdrawToBank(
                Auth::user(),
                $request->amount,
                $request->bank_name,
                $request->account_number,
                $request->account_name
            );
            return redirect()->route('referral.index')->with('alert', [
                'type' => 'success',
                'message' => 'Bank withdrawal request submitted! Awaiting admin approval. Reference: ' . $withdrawal->reference
            ]);
        } catch (\Exception $e) {
            return back()->with('alert', [
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }
}