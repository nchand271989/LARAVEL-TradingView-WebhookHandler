<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RulesTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('rules')->insert([
            [
                'rid'         => generate_snowflake_id(),
                'name'       => 'No Conditions',
                'status'     => 'Active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        usleep(1); // Ensure unique Snowflake ID

        DB::table('rules')->insert([
            [
                'rid'         => generate_snowflake_id(),
                'name'       => 'Reverse Trade',
                'status'     => 'Active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

        usleep(1); // Ensure unique Snowflake ID

        DB::table('rules')->insert([
            [
                'rid'         => generate_snowflake_id(),
                'name'       => 'Stop Auto Opening of Trade',
                'status'     => 'Active',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ]);

    }
}
