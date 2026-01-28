<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\OrderController; 
use App\Http\Controllers\SupportController; 


Route::get('/', function () {
    return view('welcome');
});

Route::post('/webhook/fincra', [WalletController::class, 'webhook'])->name('wallet.webhook');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('/wallet/topup', [WalletController::class, 'store'])->name('wallet.topup');
    Route::get('/wallet/callback', [WalletController::class, 'callback'])->name('wallet.callback');

    Route::get('/orders/new', [OrderController::class, 'create'])->name('order.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('order.store');
    
    // Order History
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    
    // Order Status & Management
    Route::post('/orders/{order}/check-status', [OrderController::class, 'checkStatus'])
        ->name('order.check-status');
    
    Route::post('/orders/{order}/request-refill', [OrderController::class, 'requestRefill'])
        ->name('order.request-refill');

    Route::get('/support', [SupportController::class, 'index'])->name('support.index');
    Route::get('/support/{id}', [SupportController::class, 'show'])->name('support.show'); // View Chat
    Route::post('/support', [SupportController::class, 'store'])->name('support.store');
    Route::post('/support/reply/{id}', [SupportController::class, 'reply'])->name('support.reply');

    // Profile & Settings
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('/notifications/settings', [ProfileController::class, 'notifications'])->name('notifications.settings');
    Route::put('/notifications/read', function () {
        if (auth()->check()) {
            auth()->user()->unreadNotifications->markAsRead();
        }
        return redirect()->back();
    })->name('notifications.mark.read');
});

require __DIR__.'/auth.php';
