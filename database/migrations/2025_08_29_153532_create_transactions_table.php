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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('reference')->unique();
            $table->decimal('amount', 10, 2);
            $table->string('status')->default('pending'); // pending, completed, failed, cancelled
            $table->string('payment_method');
            $table->json('metadata')->nullable();
            $table->text('description')->nullable();
            $table->string('transaction_id')->nullable(); // ID de la transaction Noupia
            $table->timestamps();
            
            $table->index(['reference', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
