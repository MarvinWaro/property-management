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
        Schema::create('ris_slips', function (Blueprint $table) {
            $table->id('ris_id');
            $table->string('ris_no')->unique();
            $table->date('ris_date')->default(now());

            $table->string('entity_name');
            $table->foreignId('division')
                  ->constrained('departments')
                  ->cascadeOnDelete();
            $table->string('office')->nullable();

            $table->string('fund_cluster')->nullable();
            $table->string('responsibility_center_code')->nullable();

            $table->foreignId('requested_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            $table->text('purpose')->nullable();

            // ← here we use string instead of enum
            $table->string('status', 20)
                  ->default('draft');

            // sign-offs
            $table->foreignId('approved_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('approved_at')->nullable();

            $table->foreignId('issued_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('issued_at')->nullable();

            $table->foreignId('received_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('received_at')->nullable();

            // ↓↓↓ DECLINE FIELDS ↓↓↓
            $table->foreignId('declined_by')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();
            $table->timestamp('declined_at')->nullable();
            $table->enum('decline_signature_type', ['esign','sgd'])
                  ->nullable();
            $table->text('decline_reason')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ris_slips');
    }
};
