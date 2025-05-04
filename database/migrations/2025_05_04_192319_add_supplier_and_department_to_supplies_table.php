<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('supplies', function (Blueprint $table) {
            $table->foreignId('supplier_id')->nullable()->after('category_id')->constrained('suppliers');
            $table->foreignId('department_id')->nullable()->after('supplier_id')->constrained('departments');
        });
    }

    public function down(): void
    {
        Schema::table('supplies', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropForeign(['department_id']);
            $table->dropColumn(['supplier_id', 'department_id']);
        });
    }
};
