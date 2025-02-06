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
        // Modify terms_and_conditions table
        Schema::table('terms_and_conditions', function (Blueprint $table) {
            // Drop the 'sl_no' column
            $table->dropColumn('sl_no');

            // Make 'tid' the primary key
            $table->primary('tid');
        });

        // Modify privacy_policies table
        Schema::table('privacy_policies', function (Blueprint $table) {
            // Drop the 'sl_no' column
            $table->dropColumn('sl_no');

            // Make 'pid' the primary key
            $table->primary('pid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse changes for terms_and_conditions
        Schema::table('terms_and_conditions', function (Blueprint $table) {
            // Drop the primary key on 'tid'
            $table->dropPrimary('tid');

            // Re-add 'sl_no' column
            $table->id('sl_no')->primary();

            // Make 'sl_no' the primary key
            $table->primary('sl_no');
        });

        // Reverse changes for privacy_policies
        Schema::table('privacy_policies', function (Blueprint $table) {
            // Drop the primary key on 'pid'
            $table->dropPrimary('pid');

            // Re-add 'sl_no' column
            $table->id('sl_no')->primary();

            // Make 'sl_no' the primary key
            $table->primary('sl_no');
        });
    }
};
