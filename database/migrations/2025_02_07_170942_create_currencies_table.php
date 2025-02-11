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
            $table->unsignedBigInteger('curid')->primary(); // Store Snowflake ID as an integer
            $table->string('name', 100); // Limited to 100 characters
            $table->string('shortcode', 10)->unique(); // Limited to 10 characters, unique constraint
            $table->unsignedBigInteger('createdBy');
            $table->unsignedBigInteger('lastUpdatedBy');
            $table->timestamps(); // Automatically creates created_at & updated_at
            $table->enum('status', ['Active', 'Inactive'])->default('Active'); // Only allowed values

            // Add foreign key constraint AFTER column definition
            $table->foreign('createdBy')->references('id')->on('users')->onDelete('cascade'); // Foreign key reference
            $table->foreign('lastUpdatedBy')->references('id')->on('users')->onDelete('cascade'); // Foreign key reference
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
