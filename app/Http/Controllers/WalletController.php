<?php

namespace App\Http\Controllers;

use App\Services\FincraService;
use App\Services\WalletService;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    protected $fincraService;

    public function __construct(FincraService $fincraService)
    {
        $this->fincraService = $fincraService;
    }

    public function index()
    {
        return view('wallet.index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:100',
        ]);

        $amount = $request->amount;
        $user = Auth::user();
        $redirectUrl = route('wallet.callback'); 

        // Initialize Fincra Transaction
        $result = $this->fincraService->initializeTransaction($amount, $user, $redirectUrl);

        if ($result['success']) {
            // Store the reference in session to verify later
            session(['pending_payment_reference' => $result['reference']]);
            
            return redirect()->away($result['data']['checkout_url']);
        }

        return redirect()->back()->with('alert', [
            'type' => 'error',
            'message' => $result['message']
        ]);
    }

    public function callback(Request $request)
    {
        $user = Auth::user();
        
        // FIX 1: Get reference from the correct source
        // Fincra may send it as 'reference', 'merchantReference', or in the session
        $reference = $request->query('reference') 
                  ?? $request->query('merchantReference') 
                  ?? session('pending_payment_reference');

        if (!$reference) {
            Log::error('Fincra Callback: No reference found', [
                'query_params' => $request->all(),
                'session_ref' => session('pending_payment_reference')
            ]);
            
            return redirect()->route('wallet.index')->with('alert', [
                'type' => 'error',
                'message' => 'Invalid payment reference.'
            ]);
        }

        Log::info('Fincra Callback received', [
            'reference' => $reference,
            'all_params' => $request->all()
        ]);

        // FIX 2: Check if already processed BEFORE verifying with Fincra
        $existing = Wallet::where('reference', $reference)->first();
        if ($existing) {
            Log::info('Fincra: Transaction already processed', ['reference' => $reference]);
            
            return redirect()->route('wallet.index')->with('alert', [
                'type' => 'info',
                'message' => 'This transaction has already been processed.'
            ]);
        }

        // FIX 3: Verify with Fincra
        $result = $this->fincraService->verifyTransaction($reference);

        if ($result['success']) {
            // Credit the Wallet
            WalletService::deposit(
                $user, 
                $result['amount'], 
                $reference, 
                'Fincra', 
                "Top-up via Fincra (Ref: {$reference})"
            );

            // Clear the session
            session()->forget('pending_payment_reference');

            Log::info('Fincra: Payment successful and wallet credited', [
                'reference' => $reference,
                'amount' => $result['amount']
            ]);

            return redirect()->route('wallet.index')->with('alert', [
                'type' => 'success',
                'message' => "Wallet topped up successfully with ₦" . number_format($result['amount'], 2)
            ]);
        }

        Log::warning('Fincra: Payment verification failed', [
            'reference' => $reference,
            'result' => $result
        ]);

        return redirect()->route('wallet.index')->with('alert', [
            'type' => 'error',
            'message' => 'Payment verification failed. Please contact support if your account was debited.'
        ]);
    }

    /**
     * Optional: Webhook handler for asynchronous payment notifications
     * This is the RECOMMENDED approach for production
     */
    public function webhook(Request $request)
    {
        Log::info('Fincra Webhook received', $request->all());

        // Verify webhook signature (implement this based on Fincra docs)
        // $signature = $request->header('X-Fincra-Signature');
        // if (!$this->verifyWebhookSignature($request->getContent(), $signature)) {
        //     return response()->json(['error' => 'Invalid signature'], 401);
        // }

        $data = $request->all();
        $reference = $data['merchantReference'] ?? null;
        $status = $data['status'] ?? null;

        if (!$reference || strtolower($status) !== 'success') {
            return response()->json(['error' => 'Invalid webhook data'], 400);
        }

        // Check if already processed
        $existing = Wallet::where('reference', $reference)->first();
        if ($existing) {
            return response()->json(['message' => 'Already processed'], 200);
        }

        // Find user from metadata
        $userId = $data['metadata']['user_id'] ?? null;
        if (!$userId) {
            return response()->json(['error' => 'User not found'], 400);
        }

        $user = \App\Models\User::find($userId);
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Credit wallet
        WalletService::deposit(
            $user,
            $data['amount'],
            $reference,
            'Fincra',
            "Top-up via Fincra Webhook (Ref: {$reference})"
        );

        return response()->json(['message' => 'Webhook processed'], 200);
    }
}