<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\WalletController;

Route::prefix('admin')->name('admin.')->group(function () {
    
    // Guest routes (login)
    Route::middleware('guest:admin')->group(function () {
        Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    });

    // Authenticated admin routes
    Route::middleware('admin')->group(function () {
        
        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Logout
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        
        // Customers Management
        Route::prefix('customers')->name('customers.')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('index');
            Route::get('/{id}', [CustomerController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('edit');
            Route::put('/{id}', [CustomerController::class, 'update'])->name('update');
            Route::post('/{id}/adjust-balance', [CustomerController::class, 'adjustBalance'])->name('adjust-balance');
        });

        // Order Management
        Route::prefix('orders')->name('orders.')->group(function () {
            Route::get('/', [OrderController::class, 'index'])->name('index');
            Route::get('/{id}', [OrderController::class, 'show'])->name('show');
            Route::post('/{id}/update-status', [OrderController::class, 'updateStatus'])->name('update-status');
            Route::post('/{id}/refund', [OrderController::class, 'refund'])->name('refund');
            Route::delete('/{id}', [OrderController::class, 'destroy'])->name('destroy');
        });

        // Wallet Management
        Route::prefix('wallet')->name('wallet.')->group(function () {
            Route::get('/', [WalletController::class, 'index'])->name('index');
            Route::get('/{id}', [WalletController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [WalletController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [WalletController::class, 'reject'])->name('reject');
            Route::delete('/{id}', [WalletController::class, 'destroy'])->name('destroy');
        });
        
        // Placeholder routes (we'll create these in next steps)
        Route::get('/orders', function() { return 'Orders - Coming in Step 7'; })->name('orders.index');
        Route::get('/orders/{id}', function() { return 'Order Details - Coming in Step 7'; })->name('orders.show');
        Route::get('/wallet', function() { return 'Wallet - Coming in Step 8'; })->name('wallet.index');
        Route::get('/wallet/{id}', function() { return 'Transaction Details - Coming in Step 8'; })->name('wallet.show');
        Route::get('/support', function() { return 'Support - Coming soon'; })->name('support.index');
        Route::get('/admins', function() { return 'Admins - Coming soon'; })->name('admins.index');
        Route::get('/admins/create', function() { return 'Add Admin - Coming soon'; })->name('admins.create');
        
    });
});
