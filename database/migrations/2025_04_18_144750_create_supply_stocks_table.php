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
        Schema::create('supply_stocks', function (Blueprint $table) {
            $table->id('stock_id');
            $table->foreignId('supply_id')->constrained('supplies', 'supply_id');
            $table->integer('quantity_on_hand')->default(0);
            $table->decimal('unit_cost', 10, 2)->default(0.00);
            $table->decimal('total_cost', 12, 2)->default(0.00);
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['available', 'reserved', 'expired', 'depleted'])->default('available');
            $table->string('fund_cluster')->nullable();
            $table->integer('days_to_consume')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_stocks');
    }
};
