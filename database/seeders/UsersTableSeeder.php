<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'id'                => generate_snowflake_id(),    // Generate unique Snowflake ID
                'name'              => env('ADMIN_NAME', 'Admin User'),
                'email'             => env('ADMIN_EMAIL', 'admin@example.com'),
                'email_verified_at' => now(),
                'password'          => Hash::make(env('ADMIN_PASSWORD', 'password123')), // Secure password
                'is_admin'          => true, // Admin user
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            ],
        ]);
    }
}
