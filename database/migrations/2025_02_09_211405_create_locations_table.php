<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('location_name'); // Alphanumeric input
            $table->boolean('active')->default(1); // 1 = Active, 0 = Inactive
            $table->boolean('excluded')->default(0); // 1 = Excluded, 0 = Not Excluded
            $table->timestamps();
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};

