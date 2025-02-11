<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();                                 // Store the user's email as the primary key for quick lookup
            $table->string('token');                                            // Store the password reset token securely
            $table->timestamp('created_at')->nullable();                        // Store the timestamp when the token was created, nullable for flexibility
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_tokens');                          // Drop the table if the migration is rolled back
    }
};
