<?php

namespace App\Http\Controllers;

use App\Services\KorapayService;
use App\Services\WalletService;
use App\Models\Wallet;
use App\Models\Logged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    protected $korapayService;

    public function __construct(KorapayService $korapayService)
    {
        $this->korapayService = $korapayService;
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
        
        // Ensure absolute URL - Korapay requires this
        $redirectUrl = route('wallet.callback', [], true);
        
        Log::info('Initiating Korapay payment', [
            'amount' => $amount,
            'user_id' => $user->id,
            'redirect_url' => $redirectUrl
        ]);

        // Initialize Korapay Transaction
        // This will create a Logged entry with status = 'pending'
        $result = $this->korapayService->initializeTransaction($amount, $user, $redirectUrl);

        if ($result['success']) {
            // Store the reference in session to verify later
            session(['pending_payment_reference' => $result['reference']]);
            
            Log::info('Korapay checkout URL generated', [
                'reference' => $result['reference'],
                'checkout_url' => $result['data']['checkout_url']
            ]);
            
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
        
        // Get reference from the correct source
        // Korapay sends it as 'reference' in the query params
        $reference = $request->query('reference') 
                  ?? session('pending_payment_reference');

        if (!$reference) {
            Log::error('Korapay Callback: No reference found', [
                'query_params' => $request->all(),
                'session_ref' => session('pending_payment_reference')
            ]);
            
            return redirect()->route('wallet.index')->with('alert', [
                'type' => 'error',
                'message' => 'Invalid payment reference.'
            ]);
        }

        Log::info('Korapay Callback received', [
            'reference' => $reference,
            'all_params' => $request->all()
        ]);

        // Check if already processed by checking the Logged table status
        $loggedEntry = Logged::where('reference', $reference)->first();
        
        if (!$loggedEntry) {
            Log::error('Korapay: No logged entry found', ['reference' => $reference]);
            
            return redirect()->route('wallet.index')->with('alert', [
                'type' => 'error',
                'message' => 'Transaction record not found.'
            ]);
        }

        // If the logged entry status is already 'success', it means we've processed it
        if ($loggedEntry->status === 'success') {
            // Check if wallet was already credited
            $walletEntry = Wallet::where('reference', $reference)->first();
            
            if ($walletEntry) {
                Log::info('Korapay: Transaction already processed and wallet credited', [
                    'reference' => $reference
                ]);
                
                return redirect()->route('wallet.index')->with('alert', [
                    'type' => 'success',
                    'message' => 'Payment already processed. Wallet balance: ₦' . number_format($user->balance, 2)
                ]);
            }
        }

        // Verify with Korapay
        $result = $this->korapayService->verifyTransaction($reference);

        if ($result['success']) {
            // Check one more time before crediting (race condition protection)
            $walletEntry = Wallet::where('reference', $reference)->first();
            
            if ($walletEntry) {
                Log::info('Korapay: Wallet already credited (race condition check)', [
                    'reference' => $reference
                ]);
                
                session()->forget('pending_payment_reference');
                
                return redirect()->route('wallet.index')->with('alert', [
                    'type' => 'success',
                    'message' => 'Payment successful. Current balance: ₦' . number_format($user->wallet_balance, 2)
                ]);
            }

            // Credit the Wallet with the ORIGINAL amount (not amount_paid with fees)
            WalletService::deposit(
                $user, 
                $result['amount'], // This is now the original amount from Logged table
                $reference, 
                'Korapay', 
                "Top-up via Korapay (Ref: {$reference})"
            );

            // Clear the session
            session()->forget('pending_payment_reference');

            Log::info('Korapay: Payment successful and wallet credited', [
                'reference' => $reference,
                'amount' => $result['amount']
            ]);

            return redirect()->route('wallet.index')->with('alert', [
                'type' => 'success',
                'message' => "Wallet topped up successfully with ₦" . number_format($result['amount'], 2)
            ]);
        }

        Log::warning('Korapay: Payment verification failed', [
            'reference' => $reference,
            'result' => $result
        ]);

        return redirect()->route('wallet.index')->with('alert', [
            'type' => 'error',
            'message' => $result['message'] ?? 'Payment verification failed. Please contact support if your account was debited.'
        ]);
    }

    /**
     * Webhook handler for asynchronous payment notifications
     */
    public function webhook(Request $request)
    {
        Log::info('Korapay Webhook received', $request->all());

        // Verify webhook signature
        $signature = $request->header('x-korapay-signature');
        if (!$signature || !$this->korapayService->verifyWebhookSignature($request->all(), $signature)) {
            Log::error('Korapay Webhook: Invalid signature');
            return response()->json(['error' => 'Invalid signature'], 401);
        }

        $data = $request->all();
        $event = $data['event'] ?? null;
        $paymentData = $data['data'] ?? [];
        
        // We only process successful charges
        if ($event !== 'charge.success') {
            Log::info('Korapay Webhook: Non-success event received', ['event' => $event]);
            return response()->json(['message' => 'Event acknowledged'], 200);
        }

        $reference = $paymentData['payment_reference'] ?? null; // Korapay uses payment_reference for merchant reference
        $status = strtolower($paymentData['status'] ?? '');

        if (!$reference || $status !== 'success') {
            Log::error('Korapay Webhook: Invalid data', [
                'reference' => $reference,
                'status' => $status
            ]);
            return response()->json(['error' => 'Invalid webhook data'], 400);
        }

        // Find the logged entry
        $loggedEntry = Logged::where('reference', $reference)->first();
        if (!$loggedEntry) {
            Log::error('Korapay Webhook: Log entry not found', ['reference' => $reference]);
            return response()->json(['error' => 'Transaction not found'], 404);
        }

        // Check if already processed
        if ($loggedEntry->status === 'success') {
            $walletEntry = Wallet::where('reference', $reference)->first();
            if ($walletEntry) {
                Log::info('Korapay Webhook: Already processed', ['reference' => $reference]);
                return response()->json(['message' => 'Already processed'], 200);
            }
        }

        $user = \App\Models\User::find($loggedEntry->user_id);
        if (!$user) {
            Log::error('Korapay Webhook: User not found', ['user_id' => $loggedEntry->user_id]);
            return response()->json(['error' => 'User not found'], 404);
        }

        // Check if wallet already credited (race condition protection)
        $walletEntry = Wallet::where('reference', $reference)->first();
        if ($walletEntry) {
            Log::info('Korapay Webhook: Wallet already credited', ['reference' => $reference]);
            return response()->json(['message' => 'Already processed'], 200);
        }

        // Credit wallet with ORIGINAL amount from Logged table
        WalletService::deposit(
            $user,
            $loggedEntry->amount, 
            $reference,
            'Korapay',
            "Top-up via Korapay Webhook (Ref: {$reference})"
        );

        // Update log entry
        $loggedEntry->update([
            'status' => 'success',
            'response_data' => array_merge(
                $loggedEntry->response_data ?? [],
                ['webhook' => $paymentData]
            ),
        ]);

        Log::info('Korapay Webhook: Payment processed successfully', [
            'reference' => $reference,
            'amount' => $loggedEntry->amount,
            'user_id' => $user->id
        ]);

        return response()->json(['message' => 'Webhook processed'], 200);
    }
}