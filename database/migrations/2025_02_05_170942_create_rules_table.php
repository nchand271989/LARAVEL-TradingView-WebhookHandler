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
        Schema::create('rules', function (Blueprint $table) {
            $table->unsignedBigInteger('rid')->primary();
            $table->string('name')->unique(); // Rule name (must be unique)
            $table->timestamps(); // Created_at & updated_at
            $table->enum('status', ['Active', 'Inactive'])->default('Active'); // Status column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rules');
    }
};
