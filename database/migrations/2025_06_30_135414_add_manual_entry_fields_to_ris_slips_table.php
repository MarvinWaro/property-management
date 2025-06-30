<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add fields to track manual RIS entries for audit purposes
     */
    public function up(): void
    {
        Schema::table('ris_slips', function (Blueprint $table) {
            // Track manual entries
            $table->boolean('is_manual_entry')->default(false)->after('decline_reason');
            $table->string('reference_source')->nullable()->after('is_manual_entry');
            $table->unsignedBigInteger('manual_entry_by')->nullable()->after('reference_source');
            $table->timestamp('manual_entry_at')->nullable()->after('manual_entry_by');
            $table->text('manual_entry_notes')->nullable()->after('manual_entry_at');

            // Add foreign key for manual entry user
            $table->foreign('manual_entry_by')->references('id')->on('users')->nullOnDelete();

            // Add indexes for better performance
            $table->index(['is_manual_entry', 'ris_date']);
            $table->index(['manual_entry_by', 'manual_entry_at']);
        });
    }

    /**
     * Reverse the migration
     */
    public function down(): void
    {
        Schema::table('ris_slips', function (Blueprint $table) {
            $table->dropForeign(['manual_entry_by']);
            $table->dropIndex(['is_manual_entry', 'ris_date']);
            $table->dropIndex(['manual_entry_by', 'manual_entry_at']);
            $table->dropColumn([
                'is_manual_entry',
                'reference_source',
                'manual_entry_by',
                'manual_entry_at',
                'manual_entry_notes'
            ]);
        });
    }
};
