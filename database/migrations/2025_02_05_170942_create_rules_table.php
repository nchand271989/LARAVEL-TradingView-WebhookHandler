<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->unsignedBigInteger('rid')->primary();       /** Primary key for the rule */
            $table->string('name')->unique();                   /** Rule name, must be unique to identify different rules */
            $table->timestamps();                               /** Timestamps for created_at and updated_at */
            $table                                              /** Status column, can be either 'Active' or 'Inactive' with 'Active' as the default value */
                ->enum('status', ['Active', 'Inactive'])
                ->default('Active');
        });
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        Schema::dropIfExists('rules');
    }
};
