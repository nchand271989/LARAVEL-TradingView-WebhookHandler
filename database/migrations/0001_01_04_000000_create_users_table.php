<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();                        // Store Snowflake ID as an integer for unique user identification
            $table->string('name');                                             // User's full name
            $table->string('email')->unique();                                  // User's email (must be unique for authentication)
            $table->timestamp('email_verified_at')->nullable();                 // Stores the timestamp when the email is verified
            $table->string('password');                                         // User's hashed password for authentication
            $table->text('two_factor_secret')->nullable();                      // Stores 2FA secret key (if enabled)
            $table->text('two_factor_recovery_codes')->nullable();              // Stores 2FA recovery codes (if enabled)
            $table->timestamp('two_factor_confirmed_at')->nullable();           // Timestamp when 2FA was confirmed
            $table->rememberToken();                                            // Token for "Remember Me" authentication feature
            $table->foreignId('current_team_id')->nullable();                   // Reference to the current team (if using Laravel Jetstream's team feature)
            $table->string('profile_photo_path', 2048)->nullable();             // Stores the path to the user's profile photo
            $table->boolean('is_admin')->default(false);                        // Flag to indicate if the user is an admin
            $table->unsignedBigInteger('terms_and_conditions_id')->nullable();  // Foreign key for terms and conditions
            $table->unsignedBigInteger('privacy_policy_id')->nullable();        // Foreign key for privacy policy
            $table->timestamps();                                               // Created_at and Updated_at timestamps

            // Define foreign key constraints
            $table->foreign('terms_and_conditions_id')
                    ->references('tid')->on('terms_and_conditions')
                    ->onDelete('set null');                                     // Set to NULL if referenced record is deleted
            $table->foreign('privacy_policy_id')
                    ->references('pid')->on('privacy_policies')
                    ->onDelete('set null');                                     // Set to NULL if referenced record is deleted
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('users');                                          // Drop the table if the migration is rolled back
    }
};
