<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\DashboardController; 
use App\Http\Controllers\OrderController; 
use App\Http\Controllers\SupportController; 
use App\Http\Controllers\ReferralController; 


Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Legal & Info Pages
Route::get('/refund-policy', function () {
    return view('legal.refund-policy');
})->name('refund-policy');

Route::get('/terms-of-use', function () {
    return view('legal.terms-of-use');
})->name('terms-of-use');

Route::get('/faq', function () {
    return view('legal.faq');
})->name('faq');

// Korapay Webhook
Route::post('/webhook/korapay', [App\Http\Controllers\KorapayWebhookController::class, 'handleWebhook'])
    ->name('wallet.webhook');

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
    Route::get('/orders/{order}/check-status', [OrderController::class, 'checkStatus'])
        ->name('orders.check-status');
    
    Route::post('/orders/{order}/request-refill', [OrderController::class, 'requestRefill'])
        ->name('order.request-refill');

    // User Support Routes
    Route::prefix('support')->name('support.')->group(function () {
        Route::get('/', [SupportController::class, 'index'])->name('index');
        Route::get('/create', [SupportController::class, 'create'])->name('create');
        Route::post('/store', [SupportController::class, 'store'])->name('store');
        Route::get('/{id}', [SupportController::class, 'show'])->name('show');
        Route::post('/{id}/reply', [SupportController::class, 'reply'])->name('reply');
        
        // AJAX route for fetching messages
        Route::get('/{id}/fetch-messages', [SupportController::class, 'fetchMessages'])->name('fetch-messages');
    });

    Route::prefix('referral')->name('referral.')->group(function () {
        Route::get('/', [ReferralController::class, 'index'])->name('index');
        Route::get('/withdraw', [ReferralController::class, 'withdraw'])->name('withdraw');
        Route::post('/withdraw/wallet', [ReferralController::class, 'withdrawToWallet'])->name('withdraw.wallet');
        Route::post('/withdraw/bank', [ReferralController::class, 'withdrawToBank'])->name('withdraw.bank');
    });

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