<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\Logged;
use Illuminate\Http\Request;
use App\Traits\LogsAdminActivity;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    use LogsAdminActivity;

    /**
     * Display all customers
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Filter by date
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Get customers with counts
        $customers = $query->withCount(['orders', 'tickets'])
            ->latest()
            ->paginate(20);

        // Statistics
        $totalCustomers = User::count();
        $todayCustomers = User::whereDate('created_at', today())->count();
        $activeCustomers = User::whereHas('orders', function($q) {
            $q->where('created_at', '>=', now()->subDays(30));
        })->count();

        // Log the view
        $this->logActivity(
            'viewed',
            auth('admin')->user()->name . ' viewed customers list',
            'User',
            null
        );

        return view('admin.customers.index', compact(
            'customers',
            'totalCustomers',
            'todayCustomers',
            'activeCustomers'
        ));
    }

    /**
     * Show customer details
     */
    public function show(Request $request, $id)
    {
        $customer = User::with(['orders', 'wallet', 'tickets'])->findOrFail($id);
        
        // Customer statistics
        $totalOrders = $customer->orders()->count();
        $completedOrders = $customer->orders()->where('status', 'completed')->count();
        $pendingOrders = $customer->orders()->where('status', 'pending')->count();
        $processingOrders = $customer->orders()->where('status', 'processing')->count();
        $totalSpent = $customer->orders()->sum('charge');
        
        // Get wallet balance (latest balance_after)
        $latestWallet = Wallet::where('user_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->first();
        $walletBalance = $latestWallet ? $latestWallet->balance_after : 0;
        
        // Total deposits
        $totalDeposits = Wallet::where('user_id', $customer->id)
            ->where('type', 'credit')
            ->where('status', 'success')
            ->sum('amount');
        
        // Paginated orders (10 per page)
        $recentOrders = $customer->orders()
            ->latest()
            ->paginate(10, ['*'], 'orders_page');
        
        // Recent wallet transactions (10 items, not paginated)
        $recentTransactions = $customer->wallet()->latest()->take(10)->get();
        
        // Get customer logs with pagination (only if Super Admin)
        $logs = null;
        if (auth('admin')->user()->canViewCustomerLogs()) {
            $logs = Logged::where('user_id', $customer->id)
                ->latest()
                ->paginate(15, ['*'], 'logs_page');
        }

        // Log the view
        $this->logViewed(
            'User',
            $customer->id,
            auth('admin')->user()->name . ' viewed customer: ' . $customer->name
        );

        return view('admin.customers.show', compact(
            'customer',
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'processingOrders',
            'totalSpent',
            'walletBalance',
            'totalDeposits',
            'recentOrders',
            'recentTransactions',
            'logs'
        ));
    }

    /**
     * Edit customer (Super Admin & Accountant only)
     */
    public function edit($id)
    {
        if (!auth('admin')->user()->canEditCustomer()) {
            abort(403, 'Unauthorized action.');
        }

        $customer = User::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    /**
     * Update customer
     */
    public function update(Request $request, $id)
    {
        if (!auth('admin')->user()->canEditCustomer()) {
            abort(403, 'Unauthorized action.');
        }

        $customer = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:6',
        ]);

        $oldData = [
            'name' => $customer->name,
            'email' => $customer->email,
        ];

        $data = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $customer->update($data);

        // Log the update
        $this->logUpdated(
            'User',
            $customer->id,
            auth('admin')->user()->name . ' updated customer: ' . $customer->name,
            [
                'name' => ['old' => $oldData['name'], 'new' => $customer->name],
                'email' => ['old' => $oldData['email'], 'new' => $customer->email],
            ]
        );

        return redirect()->route('admin.customers.show', $id)
            ->with('success', 'Customer updated successfully');
    }

    /**
     * Adjust customer wallet balance (Super Admin & Accountant only)
     */
    public function adjustBalance(Request $request, $id)
    {
        if (!auth('admin')->user()->canAdjustBalance()) {
            abort(403, 'Unauthorized action.');
        }
        
        $customer = User::findOrFail($id);
        
        $request->validate([
            'type' => 'required|in:credit,debit',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string',
        ]);
        
        // Get current balance
        $latestWallet = Wallet::where('user_id', $customer->id)
            ->orderBy('created_at', 'desc')
            ->first();
        $currentBalance = $latestWallet ? $latestWallet->balance_after : 0;
        
        // Check if customer has enough balance for debit
        if ($request->type === 'debit' && $currentBalance < $request->amount) {
            return back()->with('error', 'Insufficient balance. Customer has ₦' . number_format($currentBalance, 2) . ' but you are trying to debit ₦' . number_format($request->amount, 2));
        }
        
        // Calculate new balance
        $newBalance = $request->type === 'credit' 
            ? $currentBalance + $request->amount 
            : $currentBalance - $request->amount;
        
        // Create wallet transaction
        $wallet = Wallet::create([
            'user_id' => $customer->id,
            'balance_before' => $currentBalance,
            'amount' => $request->amount,
            'balance_after' => $newBalance,
            'type' => $request->type,
            'description' => $request->description . ' (Admin Adjustment by ' . auth('admin')->user()->name . ')',
            'reference' => 'ADJ-' . uniqid(),
            'payment_method' => 'admin_adjustment',
            'status' => 'success',
        ]);
        
        // Update user's balance in users table
        if ($request->type === 'credit') {
            $customer->increment('balance', $request->amount);
        } else {
            $customer->decrement('balance', $request->amount);
        }
        
        // Log the adjustment
        $this->logActivity(
            'adjusted_balance',
            auth('admin')->user()->name . ' adjusted balance for ' . $customer->name . ' - ' . ucfirst($request->type) . ' ₦' . number_format($request->amount, 2),
            'Wallet',
            $wallet->id,
            [
                'type' => $request->type,
                'amount' => $request->amount,
                'balance_before' => $currentBalance,
                'balance_after' => $newBalance,
            ]
        );
        
        // Also log in customer logs
        Logged::create([
            'user_id' => $customer->id,
            'reference' => $wallet->reference,
            'type' => 'wallet',
            'method' => 'admin_adjustment',
            'amount' => $request->amount,
            'status' => 'success',
            'description' => $request->description . ' (By Admin: ' . auth('admin')->user()->name . ')',
            'ip_address' => $request->ip(),
        ]);
        
        return back()->with('success', 'Balance adjusted successfully. New balance: ₦' . number_format($newBalance, 2));
    }
}