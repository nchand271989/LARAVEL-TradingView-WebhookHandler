<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** Run the migrations. */
    public function up(): void
    {
        $tncContent = View::make('legal.default-tnc')->render();            // Render the Blade template and store the output as HTML

        DB::table('terms_and_conditions')->insert([
            'tid' => generate_snowflake_id(),                               // Generate a unique Snowflake ID for the privacy policy entry
            'content' => $tncContent,                                       // Store the rendered Blade template as the content
            'version' => '1.0',                                             // Set the initial version of the privacy policy
            'created_at' => now(),                                          // Record the current timestamp as the creation time
            'updated_at' => now(),                                          // Record the current timestamp as the last update time
        ]);
    }

    /** Reverse the migrations. */
    public function down(): void
    {
        DB::table('terms_and_conditions')->truncate();                       // Clear seeded data on rollback
    }
};
