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
        Schema::create('user_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Correctly reference the transaction_id column
            $table->unsignedBigInteger('transaction_id');
            $table->foreign('transaction_id')
                ->references('transaction_id')
                ->on('supply_transactions')
                ->onDelete('cascade');

            $table->enum('role', ['requester', 'receiver']);
            $table->timestamps();

            // Prevent duplicate entries
            $table->unique(['user_id', 'transaction_id', 'role']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_transactions');
    }
};
