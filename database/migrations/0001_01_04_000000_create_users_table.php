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
            $table->unsignedBigInteger('id')->primary();                        /** Primary key for the users table, using an unsigned big integer to store a unique Snowflake ID */
            $table->string('name');                                             /** The user's full name, stored as a string */
            $table->string('email')->unique();                                  /** User's email, must be unique for user authentication and login */ 
            $table->timestamp('email_verified_at')->nullable();                 /** Timestamp for when the user's email was verified, can be null until verification */
            $table->string('password');                                         /** The user's hashed password for authentication */
            $table->text('two_factor_secret')->nullable();                      /** Stores the 2FA secret key, used when two-factor authentication is enabled (can be null if not enabled) */
            $table->text('two_factor_recovery_codes')->nullable();              /** Stores the 2FA recovery codes, used if 2FA is enabled (can be null if not enabled) */
            $table->timestamp('two_factor_confirmed_at')->nullable();           /** Timestamp for when the 2FA was confirmed, can be null if not confirmed */
            $table->rememberToken();                                            /** Token used for "Remember Me" feature in user authentication */
            $table->foreignId('current_team_id')->nullable();                   /** Foreign key to reference the current team for the user (if using Laravel Jetstream's team feature) */
            $table->string('profile_photo_path', 2048)->nullable();             /** Path to the user's profile photo, with a maximum length of 2048 characters */
            $table->boolean('is_admin')->default(false);                        /** Flag to indicate if the user is an admin (default value is false) */
            $table->unsignedBigInteger('terms_and_conditions_id')->nullable();  /** Foreign key to the terms_and_conditions table, nullable in case terms and conditions are not agreed to yet */
            $table->unsignedBigInteger('privacy_policy_id')->nullable();        /** Foreign key to the privacy_policies table, nullable in case privacy policy is not agreed to yet */
            $table->timestamps();                                               /** Timestamps for created_at and updated_at */

            /** Define foreign key constraints */ 
            $table                                                              /** If the related terms_and_conditions record is deleted, the foreign key value will be set to null */
                ->foreign('terms_and_conditions_id')
                ->references('tid')->on('terms_and_conditions')
                ->onDelete('set null');                                     
            $table                                                              /** If the related privacy_policy record is deleted, the foreign key value will be set to null */
                ->foreign('privacy_policy_id')
                ->references('pid')->on('privacy_policies')
                ->onDelete('set null');                                     
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
