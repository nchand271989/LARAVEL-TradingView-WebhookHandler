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
        $tncContent = View::make('legal.default-tnc')->render();            // Render the Blade template and store the output as HTML

        DB::table('terms_and_conditions')->insert([
            'tid'        => env('TNC_ID', 110000000000100001),
            'content'    => $tncContent,
            'version'    => '1.0',     
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
