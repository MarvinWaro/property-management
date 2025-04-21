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
            // division now FK to departments.id
            $table->foreignId('division')
                ->constrained('departments')    // references departments.id
                ->cascadeOnDelete();
            // office stays a simple string, but nullable
            $table->string('office')->nullable();

            $table->string('fund_cluster')->nullable();
            $table->string('responsibility_center_code')->nullable();

            // who requested it
            $table->foreignId('requested_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('purpose')->nullable();
            $table->enum('status', ['draft','approved','posted'])->default('draft');

            // sign‑offs
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('issued_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('issued_at')->nullable();
            $table->foreignId('received_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('received_at')->nullable();

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
