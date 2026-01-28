<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // 1. Wallet Balance
        $balance = $user->balance;

        // 2. Order Statistics
        $totalOrders = $user->orders()->count();
        $completedOrders = $user->orders()->where('status', 'completed')->count();
        $processingOrders = $user->orders()->where('status', 'processing')->count();
        $pendingOrders = $user->orders()->where('status', 'pending')->count();
        $totalSpent = $user->orders()->sum('charge');

        // 3. Recent Orders (Last 5)
        $recentOrders = $user->orders()->latest()->limit(5)->get();

        return view('dashboard', compact(
            'balance', 
            'totalOrders', 
            'completedOrders', 
            'processingOrders',
            'pendingOrders',
            'totalSpent', 
            'recentOrders'
        ));
    }
}