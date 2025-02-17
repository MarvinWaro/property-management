<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('end_users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('department');
            $table->string('phone_number')->unique();
            $table->string('picture')->nullable(); // New column for the picture
            $table->boolean('active')->default(1); // 1 = Active, 0 = Inactive
            $table->boolean('excluded')->default(0); // 1 = Excluded, 0 = Not Excluded
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('end_users');
    }
};

