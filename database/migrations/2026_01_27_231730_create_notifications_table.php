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
        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary(); // Notification ID
            $table->string('type');
            
            // We use string (char 36) for notifiable_id because our Users use UUIDs
            $table->string('notifiable_type');
            $table->uuid('notifiable_id');
            
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Index for faster lookups
            $table->index(['notifiable_id', 'notifiable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
