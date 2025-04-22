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
        Schema::create('ris_items', function (Blueprint $table) {
            $table->id('item_id');

            // Foreign key to ris_slips table
            $table->foreignId('ris_id')
                ->constrained('ris_slips', 'ris_id')
                ->cascadeOnDelete();

            // Foreign key to supplies table
            $table->foreignId('supply_id')
                ->constrained('supplies', 'supply_id')
                ->cascadeOnDelete();

            // Item details
            $table->integer('quantity_requested');
            $table->boolean('stock_available')->default(false);
            $table->integer('quantity_issued')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ris_items');
    }
};
