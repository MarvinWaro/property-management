<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update the enum to include 'declined'
        DB::statement("ALTER TABLE ris_slips MODIFY COLUMN status ENUM('draft', 'approved', 'posted', 'declined') DEFAULT 'draft'");

        // Then add the decline fields
        Schema::table('ris_slips', function (Blueprint $table) {
            $table->unsignedBigInteger('declined_by')->nullable()->after('received_at');
            $table->timestamp('declined_at')->nullable()->after('declined_by');
            $table->text('decline_reason')->nullable()->after('declined_at');

            // Add foreign key constraint
            $table->foreign('declined_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ris_slips', function (Blueprint $table) {
            $table->dropForeign(['declined_by']);
            $table->dropColumn(['declined_by', 'declined_at', 'decline_reason']);
        });

        // Revert the enum back
        DB::statement("ALTER TABLE ris_slips MODIFY COLUMN status ENUM('draft', 'approved', 'posted') DEFAULT 'draft'");
    }
};
