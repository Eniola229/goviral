<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referred_users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('referrer_id'); // The person who referred
            $table->foreign('referrer_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->uuid('referred_user_id'); // The person who was referred
            $table->foreign('referred_user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->boolean('has_deposited')->default(false);
            $table->boolean('has_ordered')->default(false);
            $table->boolean('bonus_paid')->default(false); // Track if ₦100 bonus has been paid
            $table->timestamp('bonus_paid_at')->nullable();
            
            $table->timestamps();
            
            $table->index('referrer_id');
            $table->index('referred_user_id');
            $table->unique(['referrer_id', 'referred_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referred_users');
    }
};