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
        Schema::dropIfExists('privacy_policies'); // Drop the table if it exists
        Schema::create('privacy_policies', function (Blueprint $table) {
            $table->id('sl_no'); // Auto-incrementing primary key (sl.no)
            $table->uuid('pid')->unique(); // UUID for Privacy Policy ID (pid)
            $table->text('content')->nullable(); // Allow nullable content column (no default value)
            $table->string('version'); // Version to track changes
            $table->timestamps(); // Timestamps (created_at, updated_at)
        });

        // Setting a default UUID value for `pid` column using model events or directly using factory
        DB::table('privacy_policies')->insert([
            'pid' => Str::uuid()->toString(),
            'version' => '1.0', // Provide version if needed
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop the table if the migration is rolled back
        Schema::dropIfExists('privacy_policies');
    }
};
