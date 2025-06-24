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
        Schema::table('ris_slips', function (Blueprint $table) {
            // Change status from enum to string for better flexibility
            $table->string('status', 20)->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ris_slips', function (Blueprint $table) {
            // Revert back to original enum (only works if no 'declined' records exist)
            $table->enum('status', ['draft', 'approved', 'posted'])->default('draft')->change();
        });
    }
};
