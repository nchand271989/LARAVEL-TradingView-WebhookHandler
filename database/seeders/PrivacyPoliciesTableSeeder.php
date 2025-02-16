<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class PrivacyPoliciesTableSeeder extends Seeder
{
    /** Run the database seeds. */
    public function run(): void
    {
        $ppContent = View::make('legal.default-pp')->render();          // Render the Blade template and store the output as HTML

        DB::table('privacy_policies')->insert([
            'pid'        => env('PP_ID', 110000000000100002),
            'content'    => $ppContent,               
            'version'    => '1.0',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
