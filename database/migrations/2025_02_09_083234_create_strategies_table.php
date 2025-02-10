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
        Schema::create('strategies', function (Blueprint $table) {
            $table->uuid('stratid')->primary();
            $table->string('name');
            $table->text('pineScript');
            $table->boolean('auto_reverse_order')->default(true);
            $table->uuid('createdBy');
            $table->uuid('lastUpdatedBy');
            $table->timestamps();
            $table->enum('status', ['Active', 'Inactive'])->default('Active');

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
        Schema::dropIfExists('strategies');
    }
};
