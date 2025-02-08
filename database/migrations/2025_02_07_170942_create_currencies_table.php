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
        Schema::create('currencies', function (Blueprint $table) {
            $table->uuid('curid')->primary(); // 36-character unique identifier
            $table->string('name', 100); // Limited to 100 characters
            $table->string('shortcode', 10)->unique(); // Limited to 10 characters, unique constraint
            $table->foreignId('createdBy')->constrained('users')->onDelete('cascade'); // Foreign key reference
            $table->timestamps(); // Automatically creates created_at & updated_at
            $table->enum('status', ['Active', 'Inactive'])->default('Active'); // Only allowed values
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currencies');
    }
};
