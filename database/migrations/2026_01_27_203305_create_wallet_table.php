<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wallet', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            
            // Financial State
            $table->decimal('balance_before', 15, 2);
            $table->decimal('amount', 15, 2);
            $table->decimal('balance_after', 15, 2);
            
            // Transaction Details
            $table->enum('type', ['credit', 'debit']);
            $table->string('description')->nullable();
            
            // Auditing & Bank Stuff
            $table->string('reference')->unique(); // Unique transaction ID
            $table->string('payment_method'); // e.g., Fincra, Refund
            $table->string('status')->default('success'); // success, failed, pending
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet');
    }
};
