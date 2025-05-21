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
            // Add signature type fields for each role
            $table->string('requester_signature_type')->default('sgd')->after('requested_by');
            $table->string('approver_signature_type')->default('sgd')->after('approved_at');
            $table->string('issuer_signature_type')->default('sgd')->after('issued_at');
            $table->string('receiver_signature_type')->default('sgd')->after('received_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ris_slips', function (Blueprint $table) {
            $table->dropColumn([
                'requester_signature_type',
                'approver_signature_type',
                'issuer_signature_type',
                'receiver_signature_type'
            ]);
        });
    }
};
