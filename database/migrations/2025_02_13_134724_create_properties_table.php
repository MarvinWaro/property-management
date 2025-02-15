<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
// In your properties migration
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->text('item_description')->nullable();
            $table->string('serial_no')->unique()->nullable();
            $table->string('model_no')->nullable();
            $table->date('acquisition_date')->nullable();
            $table->decimal('acquisition_cost', 15, 2)->nullable();
            $table->string('unit_of_measure')->nullable();
            $table->integer('quantity_per_physical_count')->default(1);
            $table->string('fund')->nullable();
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade');
            $table->foreignId('end_user_id')->constrained('end_users')->onDelete('cascade');
            $table->string('condition');
            $table->text('remarks')->nullable();
            // Add these two lines:
            $table->boolean('active')->default(1);     // 1 = Active
            $table->boolean('excluded')->default(0);   // 1 = Excluded
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('properties');
    }
};

