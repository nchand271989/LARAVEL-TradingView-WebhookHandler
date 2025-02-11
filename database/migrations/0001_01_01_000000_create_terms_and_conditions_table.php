<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('terms_and_conditions', function (Blueprint $table) {
            $table->unsignedBigInteger('tid')->primary();                          // Terms & Conditions Id
            $table->text('content');                                               // Terms & Conditions Content
            $table->string('version');                                             // Version to track changes
            $table->timestamps();                                                  // Timestamps (created_at, updated_at)
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('terms_and_conditions');                               // Drop the table if the migration is rolled back
    }
};
