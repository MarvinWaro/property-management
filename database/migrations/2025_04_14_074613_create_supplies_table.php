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
            $table->id('supply_id');
            $table->string('stock_no')->unique();
            $table->string('item_name');
            $table->text('description')->nullable();
            $table->string('unit_of_measurement');
            $table->unsignedBigInteger('category_id')->nullable(); // Made nullable
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('department_id')->nullable(); // Made nullable
            $table->integer('reorder_point')->default(0);
            $table->decimal('acquisition_cost', 10, 2)->default(0.00);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null');
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
