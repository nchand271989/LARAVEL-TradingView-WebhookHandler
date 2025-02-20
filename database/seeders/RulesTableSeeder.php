<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RulesTableSeeder extends Seeder
{
    public function run()
    {
        $rules = [
            [
                'rid'         => env('RULE_NO_CONDITION', 100000000000200001),
                'name'        => 'No Conditions',
                'status'      => 'Active',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'rid'         => env('RULE_REVERSE_TRADE', 100000000000200002),
                'name'        => 'Reverse Trade',
                'status'      => 'Active',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'rid'         => env('RULE_STOP_AUTO_NEW_TRADE', 100000000000200003),
                'name'        => 'Stop Auto Opening of Trade',
                'status'      => 'Active',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'rid'         => env('ONLY_LONG_TRADE', 100000000000200004),
                'name'        => 'Only Long Orders',
                'status'      => 'Active',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
            [
                'rid'         => env('ONLY_SHORT_TRADE', 100000000000200005),
                'name'        => 'Only Short Orders',
                'status'      => 'Active',
                'created_at'  => Carbon::now(),
                'updated_at'  => Carbon::now(),
            ],
        ];

        // Insert the rules while ignoring any duplicates based on 'rid'
        DB::table('rules')->insertOrIgnore($rules);
    }
}
