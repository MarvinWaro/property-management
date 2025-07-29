<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supply_stocks', function (Blueprint $table) {
            $table->id('stock_id');

            /* FK to supplies table */
            $table->foreignId('supply_id')
                ->constrained('supplies', 'supply_id')
                ->cascadeOnDelete();

            /* NEW: FK to suppliers and departments */
            $table->foreignId('supplier_id')
                ->nullable()
                ->constrained('suppliers')
                ->nullOnDelete();

            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments')
                ->nullOnDelete();

            /* summary values (ONE row per supply + fund‑cluster) */
            $table->integer('quantity_on_hand')->default(0);
            $table->decimal('unit_cost', 10, 4)->default(0.0000);
            $table->decimal('total_cost', 12, 4)->default(0.0000);

            /* metadata */
            $table->date('expiry_date')->nullable();
            $table->enum('status', ['available', 'reserved', 'expired', 'depleted'])
                ->default('available');
            $table->string('fund_cluster')->nullable();       // 101 | 151
            $table->integer('days_to_consume')->nullable();
            $table->text('remarks')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supply_stocks');
    }
};
