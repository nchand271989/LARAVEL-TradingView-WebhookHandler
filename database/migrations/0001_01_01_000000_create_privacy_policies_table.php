<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('privacy_policies', function (Blueprint $table) {
            $table->unsignedBigInteger('pid')->primary();                       // Privacy Policy Id
            $table->text('content');                                            // Privacy policy Content
            $table->string('version');                                          // Version to track changes
            $table->timestamps();                                               // Timestamps (created_at, updated_at)
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('privacy_policies');                               // Drop the table if the migration is rolled back
    }
};
