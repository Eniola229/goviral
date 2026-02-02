<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referral_wallet_transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('referral_id');
            $table->foreign('referral_id')->references('id')->on('referrals')->onDelete('cascade');
            
            $table->decimal('balance_before', 15, 2);
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_after', 15, 2);
            $table->enum('type', ['credit', 'debit']);
            $table->text('description');
            $table->string('reference')->unique();
            
            // For withdrawals
            $table->enum('withdrawal_method', ['wallet', 'bank'])->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('account_name')->nullable();
            
            $table->enum('status', ['pending', 'approved', 'declined', 'success'])->default('success');
            $table->uuid('approved_by')->nullable(); // Admin who approved/declined
            $table->foreign('approved_by')->references('id')->on('admins')->onDelete('set null');
            $table->timestamp('approved_at')->nullable();
            $table->text('admin_note')->nullable();
            
            $table->timestamps();
            
            $table->index('referral_id');
            $table->index('status');
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referral_wallet_transactions');
    }
};