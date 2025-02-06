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
        Schema::dropIfExists('terms_and_conditions'); // Drop the table if it exists
        Schema::create('terms_and_conditions', function (Blueprint $table) {
            $table->id('sl_no'); // Auto-incrementing primary key (sl.no)
            $table->uuid('tid')->unique(); // UUID for Terms and Conditions ID (tid)
            $table->text('content')->nullable(); // Allow nullable content column (no default value)
            $table->string('version'); // Version to track changes
            $table->timestamps(); // Timestamps (created_at, updated_at)
        });

        // Optionally insert a default row
        DB::table('terms_and_conditions')->insert([
            'tid' => Str::uuid()->toString(),
            'version' => '1.0', // Provide version if needed
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the table if the migration is rolled back
        Schema::dropIfExists('terms_and_conditions');
    }
};
