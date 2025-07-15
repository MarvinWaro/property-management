<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supply_transactions', function (Blueprint $table) {
            $table->id('transaction_id');

            $table->foreignId('supply_id')
                ->constrained('supplies', 'supply_id')
                ->cascadeOnDelete();

            $table->enum('transaction_type', ['receipt', 'issue', 'adjustment']);
            $table->date('transaction_date');
            $table->string('reference_no');

            $table->integer('quantity');               // 0 for adjustment
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('total_cost', 12, 2);

            $table->integer('balance_quantity');       // running balance after txn

            // Make department_id nullable here
            $table->foreignId('department_id')
                ->nullable()
                ->constrained('departments');

            $table->foreignId('user_id')->constrained('users');

            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supply_transactions');
    }
};
