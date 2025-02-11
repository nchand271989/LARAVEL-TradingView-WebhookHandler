<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        DB::table('users')->insert([
            'id' => generate_snowflake_id(),                                                            // Generate a unique Snowflake ID for the unique user id
            'name' => env('ADMIN_NAME', 'Admin User'),                                                  // Default admin name
            'email' => env('ADMIN_EMAIL', 'admin@example.com'),                                         // Change this to your desired admin email
            'email_verified_at' => now(),                                                               // Mark email as verified
            'password' => Hash::make(env('ADMIN_PASSWORD', 'password123')),                             // Change this to a secure password
            'is_admin' => true,                                                                         // Grant admin privileges
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        DB::table('users')->where('email', 'admin@example.com')->delete();                              // Delete user if the migration is rolled back
    }
};
