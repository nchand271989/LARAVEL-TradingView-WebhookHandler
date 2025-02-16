<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class TermsAndConditionsTableSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        // Render the Blade template and store the output as HTML
        $tncContent = View::make('legal.default-tnc')->render();            

        DB::table('terms_and_conditions')->insert([
            'tid'        => generate_snowflake_id(),   // Generate unique Snowflake ID
            'content'    => $tncContent,              // Store the rendered Blade template
            'version'    => '1.0',                    // Initial version
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
