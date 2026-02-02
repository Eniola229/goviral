<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->unique(); // The referrer
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            
            $table->string('referral_code')->unique();
            $table->decimal('referral_balance', 15, 2)->default(0);
            
            $table->timestamps();
            
            $table->index('referral_code');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};