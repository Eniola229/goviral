<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Admin\SupportController;

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
            Route::post('/{id}/check-status', [OrderController::class, 'checkStatus'])->name('check-status');
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
        
       
        // WALLET ROUTES
        Route::prefix('wallet')->name('wallet.')->group(function () {
            Route::get('/', [WalletController::class, 'index'])->name('index');
            Route::get('/{id}', [WalletController::class, 'show'])->name('show');
            Route::post('/{id}/approve', [WalletController::class, 'approve'])->name('approve');
            Route::post('/{id}/reject', [WalletController::class, 'reject'])->name('reject');
            Route::delete('/{id}', [WalletController::class, 'destroy'])->name('destroy');
        });
        
        Route::prefix('support')->name('support.')->group(function () {
            Route::get('/', [SupportController::class, 'index'])->name('index');
            Route::get('/{id}', [SupportController::class, 'show'])->name('show');
            Route::post('/{id}/reply', [SupportController::class, 'reply'])->name('reply');
            Route::post('/{id}/status', [SupportController::class, 'updateStatus'])->name('status');
            Route::post('/{id}/close', [SupportController::class, 'close'])->name('close');
            Route::post('/{id}/reopen', [SupportController::class, 'reopen'])->name('reopen');
            Route::delete('/{id}', [SupportController::class, 'destroy'])->name('destroy');
            
            // AJAX route for fetching messages
            Route::get('/{id}/fetch-messages', [SupportController::class, 'fetchMessages'])->name('fetch-messages');
        });
        Route::get('/admins', function() { return 'Admins - Coming soon'; })->name('admins.index');
        Route::get('/admins/create', function() { return 'Add Admin - Coming soon'; })->name('admins.create');
        
    });
});
