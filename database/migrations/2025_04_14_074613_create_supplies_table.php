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
        Schema::create('supplies', function (Blueprint $table) {
            $table->id('supply_id'); // Primary Key
            $table->string('stock_no')->unique(); // Unique identifier
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->string('unit_of_measurement');
            $table->foreignId('category_id')->constrained('categories');
            $table->integer('reorder_point')->default(0);
            $table->decimal('acquisition_cost', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies');
    }
};
