<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_logged', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('admin_id');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            
            $table->string('action');
            $table->text('description');
            
            $table->string('target_type')->nullable();
            $table->string('target_id')->nullable();
            
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            
            $table->json('changes')->nullable();
            
            $table->timestamps();
            
            $table->index('admin_id');
            $table->index('action');
            $table->index('target_type');
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_logged');
    }
};