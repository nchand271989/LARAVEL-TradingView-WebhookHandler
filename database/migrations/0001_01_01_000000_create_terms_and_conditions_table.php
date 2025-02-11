<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Services\Snowflake;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::dropIfExists('terms_and_conditions'); // Drop the table if it exists
        Schema::create('terms_and_conditions', function (Blueprint $table) {
            $table->unsignedBigInteger('tid')->primary(); // Store Snowflake ID as an integer
            $table->text('content')->nullable(); // Allow nullable content column (no default value)
            $table->string('version'); // Version to track changes
            $table->timestamps(); // Timestamps (created_at, updated_at)
        });

        // Generate Snowflake ID
        $snowflake = new Snowflake(1); // Machine ID = 1

        // Optionally insert a default row
        DB::table('terms_and_conditions')->insert([
            'tid' => $snowflake->generateId(),
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
