<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDesignationIdToUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add the designation_id column, nullable if it's optional
            $table->unsignedBigInteger('designation_id')->nullable()->after('department_id');

            // Set up a foreign key constraint if you have a designations table
            $table->foreign('designation_id')
                ->references('id')->on('designations')
                ->onDelete('set null'); // or 'cascade' if needed
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign key first then the column
            $table->dropForeign(['designation_id']);
            $table->dropColumn('designation_id');
        });
    }
}
