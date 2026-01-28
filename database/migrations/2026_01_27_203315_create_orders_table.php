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
        Schema::create('orders', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained()->onDelete('cascade');
            
            // Storing the API ID
            $table->unsignedBigInteger('service_id'); 
            
            // Storing the name locally for your dashboard history
            $table->string('service_name'); 
            
            $table->string('link'); // Social media link
            $table->integer('quantity'); 
            $table->decimal('charge', 10, 2); 
            
            // Status
            $table->enum('status', ['pending', 'processing', 'completed', 'cancelled', 'refunded'])->default('pending');
            
            // The ID returned by api
            $table->string('api_order_id')->nullable(); 
            $table->text('api_response')->nullable(); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
