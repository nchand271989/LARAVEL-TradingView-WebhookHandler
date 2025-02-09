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
        Schema::create('exchanges', function (Blueprint $table) {
            $table->uuid('exid')->primary();
            $table->string('name', 255)->unique();
            $table->uuid('createdBy');
            $table->timestamps();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');

            // Add foreign key constraint AFTER column definition
            $table->foreign('createdBy')->references('id')->on('users')->onDelete('cascade'); // Foreign key reference
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchanges');
    }
};
